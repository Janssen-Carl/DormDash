<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        try {

            $forecastResponse = Http::timeout(3)
                ->get('http://192.168.1.108:5000/forecast/revenue');

            $forecast = $forecastResponse->successful()
                ? $forecastResponse->json()
                : null;

            $inventoryResponse = Http::timeout(3)
                ->get('http://192.168.1.108:5000/inventory/alerts');

            $inventory = $inventoryResponse->successful()
                ? $inventoryResponse->json()
                : [];

        } catch (\Exception $e) {
            $forecast = null;
            $inventory = [];
        }

        return view('pages.vendor-dashboard', [
            'forecast' => $forecast,
            'inventory' => $inventory
        ]);
    }
}
