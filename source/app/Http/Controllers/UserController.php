<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Address;
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
                    'vendor_id' => $user->user_id,
                    'name'      => $user->username,
                    'email'     => $user->email,
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

    public function showVendor()
    {
        $user = auth()->user();
        $profile = $user->vendor()->with('address')->first();

        return view('pages.vendor-profile', compact('user', 'profile'));
    }

    public function editVendor()
    {
        $user = auth()->user();
        $profile = $user->vendor()->with('address')->first();

        return view('pages.vendor-profile-edit', compact('user', 'profile'));
    }

    // Update profile information and optional profile image
    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'phone'    => 'nullable|string|max:50',
            'website'  => 'nullable|string|max:255',
            'street'   => 'nullable|string|max:150',
            'city'     => 'nullable|string|max:100',
            'province_state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country'  => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:4096',
        ];

        $validated = $request->validate($rules);

        // Update user basic fields
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->save();

        // If vendor, also sync vendor.brand/name with updated username (brand)
        if ($user->role === 'vendor') {
            $vendor = $user->vendor ?: new Vendor(['vendor_id' => $user->user_id]);
            $vendor->name = $validated['username'];
            $vendor->email = $validated['email'] ?? $vendor->email;
            $vendor->phone = $validated['phone'] ?? null;
            $vendor->website = $validated['website'] ?? null;
            $vendor->save();
        }

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

        if ($user->role === 'vendor') {
            $vendor = $user->vendor;
            $hasAddressInput = collect(['street', 'city', 'province_state', 'postal_code', 'country'])
                ->contains(fn ($field) => $request->filled($field));

            if ($vendor && $hasAddressInput) {
                $address = $vendor->address ?: new Address(['user_id' => $user->user_id]);
                $address->fill([
                    'user_id' => $user->user_id,
                    'street' => $validated['street'] ?? '',
                    'city' => $validated['city'] ?? '',
                    'province_state' => $validated['province_state'] ?? '',
                    'postal_code' => $validated['postal_code'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'country' => $validated['country'] ?? '',
                ]);
                $address->save();

                $vendor->address_id = $address->address_id;
                $vendor->save();
            }
        } elseif ($request->filled('phone')) {
            if ($user->role === 'customer') {
                $customer = $user->customer;
                if ($customer) {
                    $customer->phone = $validated['phone'];
                    $customer->save();
                }
            }
        }

        $redirect = $user->role === 'vendor' ? '/vendor-profile' : '/profile';

        return redirect($redirect)->with('success', 'Profile updated successfully');
    }
}
