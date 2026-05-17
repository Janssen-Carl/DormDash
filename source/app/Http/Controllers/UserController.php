<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function register(Request $request) {
        $validated_fields = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:customer,vendor']
        ]);

        $validated_fields['password'] = bcrypt($validated_fields['password']);
        $validated_fields['role'] = $validated_fields['role'] ?? 'customer';

        $user = User::create($validated_fields);
        auth()->login($user);
        return redirect('/');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            $user = auth()->user();

            return match ($user->role) {
                'vendor' => redirect()->route('vendor.home'),
                'customer' => redirect('/products'),
                default => redirect('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
