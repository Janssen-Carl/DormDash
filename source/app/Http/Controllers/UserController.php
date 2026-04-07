<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function register(Request $request) {
        $validated_fields = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $validated_fields['password'] = bcrypt($validated_fields['password']);

        $user = User::create($validated_fields);
        auth()->login($user);
        return redirect('/');
    }
}
