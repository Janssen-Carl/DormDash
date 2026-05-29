@extends('layouts.main')

@section('title', 'Password Reset Successful')

@section('content')
<div class="flex min-h-[80vh] items-center justify-center px-4">
    <div class="w-full max-w-md text-center">
        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="mb-2 text-3xl font-bold tracking-tight text-gray-900">Password Reset Successful</h1>
        <p class="mb-8 text-gray-600">Your password has been changed successfully. You can now log in with your new password.</p>
        <a href="/login" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-8 py-3 text-sm font-bold text-white shadow-sm transition-colors hover:bg-green-700">
            Go to Login
        </a>
    </div>
</div>
@endsection