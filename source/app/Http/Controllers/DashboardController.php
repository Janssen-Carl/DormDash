<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $aiBase = env('AI_API_URL', 'http://127.0.0.1:5000');

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

        return view('pages.vendor-analytics', compact('forecast', 'itemsForecast', 'summary', 'inventory'));
    }
}
