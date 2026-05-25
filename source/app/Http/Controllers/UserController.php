<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Address;
use App\Models\CusBankingInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username'      => ['required', 'string', 'max:255', 'unique:users'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'role'          => ['required', 'in:customer,vendor'],
            'store_name'    => ['required_if:role,vendor', 'nullable', 'string', 'max:255'],
            'store_phone'   => ['required_if:role,vendor', 'nullable', 'string', 'max:255'],
            'store_website' => ['nullable', 'url', 'max:255'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = null;

        DB::transaction(function () use (&$user, $validated, $request) {

            $user = User::create([
                'username' => $validated['username'],
                'email'    => $validated['email'],
                'password' => $validated['password'],
                'role'     => $validated['role'],
            ]);

            if ($validated['role'] === 'vendor') {
                $user->verification_token = \Illuminate\Support\Str::random(60);
                $user->save();

                Vendor::create([
                    'vendor_id' => $user->user_id,
                    'name'      => $validated['store_name'] ?? $user->username,
                    'phone'     => $validated['store_phone'] ?? null,
                    'website'   => $validated['store_website'] ?? null,
                    'active'    => false, // newly registered vendors must be approved by admin!
                ]);
            }
        });

        if ($validated['role'] === 'vendor') {
            // Send Verification Email via PHPMailer
            $verifyUrl = route('email.verify', ['token' => $user->verification_token]);
            $emailBody = \App\Services\MailService::getVerificationTemplate($user->username, $verifyUrl);
            
            \App\Services\MailService::send(
                $user->email,
                'Verify Your DormDash Vendor Account',
                $emailBody
            );

            return redirect('/login')->with('success', 'Registration successful! A verification email has been sent to your address. Please verify your email first, then wait for administrator approval.');
        }

        auth()->login($user);

        return redirect('/');
    }

    public function verifyEmail($token)
    {
        if (empty($token) || strlen($token) !== 60) {
            return redirect('/login')->withErrors([
                'email' => 'Invalid email verification token.',
            ]);
        }

        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect('/login')->withErrors([
                'email' => 'Invalid or expired email verification token.',
            ]);
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return redirect('/login')->with('success', 'Email verified successfully! Your account is now pending administrator approval.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            // Check if vendor email is verified first
            if ($user->role === 'vendor' && !$user->email_verified_at) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Please verify your email address first. We sent a verification link to your email.',
                ]);
            }

            // Check if vendor account is active/approved
            if ($user->role === 'vendor' && (!$user->vendor || !$user->vendor->active)) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your vendor account is pending approval. Please wait for an administrator to review your request.',
                ]);
            }

            $request->session()->regenerate();

            return match ($user->role) {
                'vendor'   => redirect()->route('vendor.home'),
                'customer' => redirect('/products'),
                'admin'    => redirect()->route('admin.dashboard'),
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

        if ($user->role === 'vendor') {
            return redirect('/vendor-profile');
        }

        // Load related profile
        $profile = $user->customer()->with(['primaryAddress', 'bankingInfos'])->first();
        $addresses = $user->addresses;

        return view('pages.profile', compact('user', 'profile', 'addresses'));
    }

    // Show vendor profile page
    public function showVendor()
    {
        $user = auth()->user();
        $vendor = $user->vendor()->with('address')->first();
        $addresses = Address::where('user_id', $user->user_id)->get();

        return view('pages.vendor-profile', compact('user', 'vendor', 'addresses'));
    }

    // Show vendor profile edit form
    public function editVendor()
    {
        $user = auth()->user();
        $vendor = $user->vendor()->with('address')->first();
        $addresses = Address::where('user_id', $user->user_id)->get();

        return view('pages.vendor-profile-edit', compact('user', 'vendor', 'addresses'));
    }

    // Show profile edit form
    public function edit()
    {
        $user = auth()->user();

        if ($user->role === 'vendor') {
            return redirect('/vendor-profile/vendor-profile-edit');
        }

        // Load related profile
        $profile = $user->customer()->with('primaryAddress')->first();

        return view('pages.profile-edit', compact('user', 'profile'));
    }

    // Update profile information, optional profile image, password and address
    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'phone'    => 'nullable|string|max:50',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:4096',
            'website'  => 'nullable|url|max:255',
            'address'  => 'nullable|string|max:500',
            'city'     => 'nullable|string|max:100',
            'country'  => 'nullable|string|max:100',
        ];

        $validated = $request->validate($rules);

        // Update user basic fields
        $user->username = strip_tags($validated['username']);
        $user->email = strip_tags($validated['email']);
        $user->save();

        // Sync vendor name & email with user fields for vendor accounts
        if ($user->role === 'vendor') {
            $vendor = $user->vendor;
            if ($vendor) {
                $vendor->name = $user->username;
                $vendor->email = $user->email;
                $vendor->save();
            }
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

        // Update phone if provided
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

        // Handle vendor-specific fields (website)
        if ($user->role === 'vendor' && $request->filled('website')) {
            $vendor = $user->vendor;
            if ($vendor) {
                $vendor->website = $validated['website'];
                $vendor->save();
            }
        }

        // Handle address_id (set default address from profile page)
        if ($request->filled('address_id')) {
            if ($user->role === 'vendor') {
                $vendor = $user->vendor;
                if ($vendor) {
                    $vendor->address_id = $request->input('address_id');
                    $vendor->save();
                }
            } elseif ($user->role === 'customer') {
                $customer = $user->customer;
                if ($customer) {
                    $customer->primary_address_id = $request->input('address_id');
                    $customer->save();
                }
            }
        }

        // Handle address fields from edit form
        if ($request->filled('address')) {
            $address = null;
            if ($user->role === 'vendor') {
                $vendor = $user->vendor;
                if ($vendor && $vendor->address) {
                    $address = $vendor->address;
                }
            } else {
                $customer = $user->customer;
                if ($customer && $customer->primaryAddress) {
                    $address = $customer->primaryAddress;
                }
            }

            if (!$address) {
                $address = new Address();
                $address->user_id = $user->user_id;
            }

            $address->street = strip_tags($validated['address']);
            $address->city = strip_tags($validated['city'] ?? '');
            $address->province_state = strip_tags($validated['city'] ?? 'Batangas');
            $address->postal_code = '4200';
            $address->country = strip_tags($validated['country'] ?? 'Philippines');
            $address->phone = strip_tags($validated['phone'] ?? '');
            $address->email = $user->email;
            $address->save();

            if ($user->role === 'vendor') {
                $vendor = $user->vendor;
                if ($vendor) {
                    $vendor->address_id = $address->address_id;
                    $vendor->save();
                }
            } else {
                $customer = $user->customer;
                if ($customer && $request->has('is_default_address')) {
                    $customer->primary_address_id = $address->address_id;
                    $customer->save();
                }
            }
        }

        // Handle password changes
        if ($request->filled('current_password') || $request->filled('new_password')) {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if (!Hash::check($request->input('current_password'), $user->password)) {
                return back()->withErrors(['current_password' => 'The current password you entered is incorrect.']);
            }

            $user->password = Hash::make($request->input('new_password'));
            $user->save();
        }

        $redirect = $user->role === 'vendor' ? '/vendor-profile' : '/profile';
        return redirect($redirect)->with('success', 'Profile updated successfully');
    }

    // Direct profile image upload from view page
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
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

        $redirect = $user->role === 'vendor' ? '/vendor-profile' : '/profile';
        return redirect($redirect)->with('success', 'Profile photo uploaded successfully!');
    }

    // Add Address
    public function addAddress(Request $request)
    {
        $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();

        $address = Address::create([
            'user_id' => $user->user_id,
            'street' => $request->street,
            'city' => $request->city,
            'province_state' => $request->city, // fallback
            'postal_code' => $request->postal_code ?: '4200',
            'phone' => $request->phone ?: $user->phone ?: '',
            'email' => $user->email,
            'country' => $request->country,
        ]);

        if ($request->has('is_default')) {
            if ($user->role === 'vendor') {
                $vendor = $user->vendor;
                if ($vendor) {
                    $vendor->address_id = $address->address_id;
                    $vendor->save();
                }
            } else {
                $customer = $user->customer;
                if ($customer) {
                    $customer->primary_address_id = $address->address_id;
                    $customer->save();
                }
            }
        }

        $redirect = $user->role === 'vendor' ? '/vendor-profile' : '/profile';
        return redirect($redirect)->with('success', 'Address added successfully.');
    }

    // Delete Address
    public function deleteAddress($id)
    {
        if (!is_numeric($id)) {
            return back()->withErrors(['address' => 'Invalid address ID.']);
        }

        $user = auth()->user();
        $address = Address::where('user_id', $user->user_id)->findOrFail($id);

        // Prevent deleting if it is the only address left
        $addressCount = Address::where('user_id', $user->user_id)->count();
        if ($addressCount <= 1) {
            return back()->withErrors(['address' => 'You must have at least one address on your profile.']);
        }

        // Prevent deleting the primary/default address
        if ($user->role === 'vendor') {
            $vendor = $user->vendor;
            if ($vendor && $vendor->address_id == $id) {
                return back()->withErrors(['address' => 'You cannot delete your default delivery address. Please set another address as default first.']);
            }
        } else {
            $customer = $user->customer;
            if ($customer && $customer->primary_address_id == $id) {
                return back()->withErrors(['address' => 'You cannot delete your default delivery address. Please set another address as default first.']);
            }
        }

        $address->delete();

        $redirect = $user->role === 'vendor' ? '/vendor-profile' : '/profile';
        return redirect($redirect)->with('success', 'Address removed successfully.');
    }

    // Add Payment Card
    public function addPayment(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:100',
            'card_number' => 'required|string',
            'card_type' => 'required|in:visa,mastercard,amex,discover',
        ]);

        $user = auth()->user();

        if ($user->role === 'customer') {
            $customer = $user->customer;
            if ($customer) {
                $accLast4 = substr(preg_replace('/\s+/', '', $request->card_number), -4);
                
                $banking = CusBankingInfo::create([
                    'customer_id' => $customer->customer_id,
                    'payment_method' => $request->card_type,
                    'provider' => ucfirst($request->card_type),
                    'account_name' => strtoupper($request->account_name),
                    'acc_last4_no' => $accLast4,
                    'token' => 'TOK_' . strtoupper(uniqid()),
                ]);

                if ($request->has('is_default')) {
                    $customer->primary_banking_info = $banking->banking_id;
                    $customer->save();
                }
            }
        }

        $redirect = $user->role === 'vendor' ? '/vendor-profile' : '/profile';
        return redirect($redirect)->with('success', 'Payment card added successfully.');
    }

    // Delete Payment Card
    public function deletePayment($id)
    {
        if (!is_numeric($id)) {
            return back()->withErrors(['payment' => 'Invalid payment ID.']);
        }

        $user = auth()->user();
        
        if ($user->role === 'customer') {
            $customer = $user->customer;
            if ($customer) {
                $banking = CusBankingInfo::where('customer_id', $customer->customer_id)->findOrFail($id);

                if ($customer->primary_banking_info == $id) {
                    $customer->primary_banking_info = null;
                    $customer->save();
                }

                $banking->delete();
            }
        }

        $redirect = $user->role === 'vendor' ? '/vendor-profile' : '/profile';
        return redirect($redirect)->with('success', 'Payment card removed successfully.');
    }
}
