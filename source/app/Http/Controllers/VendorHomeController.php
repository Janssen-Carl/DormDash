<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class VendorHomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vendor = $user->vendor;
        
        $totalProducts = 0;
        if ($vendor) {
            $totalProducts = $vendor->items()->count();
        }

        return view('vendor-home', compact('vendor', 'totalProducts'));
    }
}
