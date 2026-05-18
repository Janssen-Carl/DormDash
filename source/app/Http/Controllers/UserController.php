<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:customer,vendor']
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = null;

        DB::transaction(function () use (&$user, $validated) {

            $user = User::create([
                'username' => $validated['username'],
                'email'    => $validated['email'],
                'password' => $validated['password'],
                'role'     => $validated['role'],
            ]);

            if ($validated['role'] === 'vendor') {
                Vendor::create([
                    'user_id' => $user->user_id, // correct PK usage
                    'name'    => $user->username,
                ]);
            }
        });

        auth()->login($user);

        return redirect('/');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            $user = auth()->user();

            return match ($user->role) {
                'vendor'   => redirect()->route('vendor.home'),
                'customer' => redirect('/products'),
                default    => redirect('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
