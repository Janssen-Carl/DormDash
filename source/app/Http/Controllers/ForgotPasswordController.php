<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link to user.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $user  = User::where('email', $email)->first();

        // Countermeasure: If account does not exist, return success immediately to prevent user enumeration
        if (!$user) {
            return back()->with('success', "If there is an account registered on that email, we'll send an email.");
        }

        // Generate secure random token
        $token = Str::random(60);

        // Store or update in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token'      => $token,
                'created_at' => now(),
            ]
        );

        // Generate the recovery URL
        $resetUrl = route('password.reset', ['token' => $token]) . '?email=' . urlencode($email);

        // Render beautiful HTML template
        $body = MailService::getPasswordResetTemplate($user->username, $resetUrl);

        // Send the email
        $sent = MailService::send($email, 'Reset Your DormDash Password', $body);

        if (!$sent) {
            \Illuminate\Support\Facades\Log::error("SMTP Delivery failed for password reset link requested by: " . $email);
        }

        return back()->with('success', "If there is an account registered on that email, we'll send an email.");
    }

    /**
     * Show password reset form.
     */
    public function showResetForm($token, Request $request)
    {
        $email = $request->query('email');
        return view('auth.reset-password', compact('token', 'email'));
    }

    /**
     * Process password reset.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email|exists:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $email    = $request->input('email');
        $token    = $request->input('token');
        $password = $request->input('password');

        // Check for token in DB
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record || $record->token !== $token) {
            return back()->withErrors(['email' => 'This password reset token is invalid or expired.']);
        }

        // Check expiration (60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->withErrors(['email' => 'This password reset token has expired.']);
        }

        // Update password
        $user = User::where('email', $email)->firstOrFail();
        $user->password = Hash::make($password);
        $user->save();

        // Clear token
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect('/login')->with('success', 'Your password has been reset successfully! You can now log in.');
    }
}
