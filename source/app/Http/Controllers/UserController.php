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

    // Show profile page
    public function show()
    {
        $user = auth()->user();

        // Load related profile
        $profile = null;
        if ($user->role === 'vendor') {
            $profile = $user->vendor;
        } elseif ($user->role === 'customer') {
            $profile = $user->customer;
        }

        return view('pages.profile', compact('user', 'profile'));
    }

    // Show profile edit form
    public function edit()
    {
        $user = auth()->user();

        // Load related profile
        $profile = null;
        if ($user->role === 'vendor') {
            $profile = $user->vendor;
        } elseif ($user->role === 'customer') {
            $profile = $user->customer;
        }

        return view('pages.profile-edit', compact('user', 'profile'));
    }

    // Update profile information and optional profile image
    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'phone'    => 'nullable|string|max:50',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:4096',
        ];

        $validated = $request->validate($rules);

        // Update user basic fields
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->save();

        // Handle profile image if provided
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            // store in public disk under profiles/
            $path = $file->store('profiles', 'public');

            if ($user->role === 'vendor') {
                $vendor = $user->vendor;
                if ($vendor) {
                    $vendor->profile_img = 'storage/' . $path;
                    $vendor->save();
                }
            } elseif ($user->role === 'customer') {
                $customer = $user->customer;
                if ($customer) {
                    $customer->profile_img = 'storage/' . $path;
                    $customer->save();
                }
            }
        }

        // Update phone/address if provided (simple fields)
        if ($request->filled('phone')) {
            if ($user->role === 'vendor') {
                $vendor = $user->vendor;
                if ($vendor) {
                    $vendor->phone = $validated['phone'];
                    $vendor->save();
                }
            } elseif ($user->role === 'customer') {
                $customer = $user->customer;
                if ($customer) {
                    $customer->phone = $validated['phone'];
                    $customer->save();
                }
            }
        }

        return redirect('/profile')->with('success', 'Profile updated successfully');
    }
}
