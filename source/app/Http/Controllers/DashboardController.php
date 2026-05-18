<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

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

        try {
            $forecastResp = Http::timeout(5)
                ->get($aiBase . '/forecast/revenue', [
                    'vendor_id' => $vendorId,
                    'days' => 90,
                    'horizon' => 7,
                ]);

            $forecast = $forecastResp->successful() ? $forecastResp->json() : null;

            $itemsResp = Http::timeout(5)
                ->get($aiBase . '/forecast/items', [
                    'vendor_id' => $vendorId,
                    'days' => 90,
                    'horizon' => 7,
                    'top' => 5,
                ]);

            $itemsForecast = $itemsResp->successful() ? $itemsResp->json() : [];

            $summaryResp = Http::timeout(5)
                ->get($aiBase . '/forecast/summary', [
                    'vendor_id' => $vendorId,
                    'days' => 90,
                    'horizon' => 1,
                ]);

            $summary = $summaryResp->successful() ? $summaryResp->json() : null;

            $inventoryResp = Http::timeout(5)
                ->get($aiBase . '/inventory/alerts', [
                    'vendor_id' => $vendorId,
                    'threshold' => 10,
                ]);

            $inventory = $inventoryResp->successful() ? $inventoryResp->json() : [];
        } catch (\Exception $e) {
            $forecast = null;
            $itemsForecast = [];
            $summary = null;
            $inventory = [];
        }

        return view('pages.vendor-analytics', compact('forecast', 'itemsForecast', 'summary', 'inventory'));
    }
}
