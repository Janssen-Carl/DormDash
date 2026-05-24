<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $aiBase = env('AI_API_URL');

        // Determine vendor id if available
        $vendorId = null;
        try {
            $vendorId = Auth::user()?->vendor?->vendor_id ?? null;
        } catch (\Throwable $e) {
            $vendorId = null;
        }

        // Fallback to 1 for demo if vendor not present
        if (is_null($vendorId)) {
            $vendorId = 1;
        }

        // Try a list of candidate base URLs to handle Docker / host networking differences
        $candidates = array_filter([
            $aiBase,
            env('AI_API_URL_ALT', null),
            'http://ai:5000',
            'http://host.docker.internal:5000',
            'http://127.0.0.1:5000',
        ]);

        $forecast = null;
        $itemsForecast = [];
        $summary = null;
        $inventory = [];

        foreach ($candidates as $base) {
            try {
                Log::info("Dashboard: trying AI base URL: {$base}");

                $forecastResp = Http::timeout(5)
                    ->get($base . '/forecast/revenue', [
                        'vendor_id' => $vendorId,
                        'days' => 90,
                        'horizon' => 7,
                    ]);

                if (! $forecastResp->successful()) {
                    Log::warning("Dashboard: forecast call to {$base} failed with status " . $forecastResp->status());
                    continue;
                }

                $forecast = $forecastResp->json();

                $itemsResp = Http::timeout(5)
                    ->get($base . '/forecast/items', [
                        'vendor_id' => $vendorId,
                        'days' => 90,
                        'horizon' => 7,
                        'top' => 5,
                    ]);

                $itemsForecast = $itemsResp->successful() ? $itemsResp->json() : [];

                // Enrich item forecasts with product names from the database
                if (!empty($itemsForecast)) {
                    $itemIds = array_column($itemsForecast, 'item_id');
                    $itemNames = Item::whereIn('item_id', $itemIds)->pluck('name', 'item_id')->toArray();
                    foreach ($itemsForecast as &$itm) {
                        $itm['name'] = $itemNames[$itm['item_id']] ?? ('Item ' . $itm['item_id']);
                    }
                    unset($itm);
                }

                $summaryResp = Http::timeout(5)
                    ->get($base . '/forecast/summary', [
                        'vendor_id' => $vendorId,
                        'days' => 90,
                        'horizon' => 1,
                    ]);

                $summary = $summaryResp->successful() ? $summaryResp->json() : null;

                $inventoryResp = Http::timeout(5)
                    ->get($base . '/inventory/alerts', [
                        'vendor_id' => $vendorId,
                        'threshold' => 10,
                    ]);

                $inventory = $inventoryResp->successful() ? $inventoryResp->json() : [];

                // Successful fetch — stop trying candidates
                Log::info("Dashboard: successfully fetched data from {$base}");
                break;

            } catch (\Exception $e) {
                Log::error("Dashboard: error calling AI at {$base}: " . $e->getMessage());
                // continue to next candidate
            }
        }

        if (is_null($forecast)) {
            Log::warning('Dashboard: no forecast data available from any AI endpoints');
        }

        // Calculate 7-day insights
        $past7Total = 0;
        $next7Total = 0;
        $growth = 0;
        
        if (!empty($forecast['historical'])) {
            $last7 = array_slice($forecast['historical'], -7);
            $past7Total = array_sum(array_column($last7, 'revenue'));
        }
        if (!empty($forecast['predicted'])) {
            $next7Total = array_sum($forecast['predicted']);
        }
        if ($past7Total > 0) {
            $growth = (($next7Total - $past7Total) / $past7Total) * 100;
        }

        $hasData = !empty($forecast['historical']) || !empty($itemsForecast);
        $totalRevenue = $past7Total;
        $statusBreakdown = [];
        $labels = [];
        $data = [];
        if (!empty($forecast['historical'])) {
            foreach ($forecast['historical'] as $row) {
                $labels[] = $row['order_date'] ?? '';
                $data[] = $row['revenue'] ?? 0;
            }
        }

        return view('pages.vendor-analytics', compact(
            'forecast', 'itemsForecast', 'summary', 'inventory',
            'past7Total', 'next7Total', 'growth', 'hasData',
            'totalRevenue', 'statusBreakdown', 'labels', 'data'
        ));
    }
}
