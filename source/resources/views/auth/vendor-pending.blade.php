@extends('layouts.main')

@section('title', 'Vendor Registration Pending')

@section('content')
<div class="flex min-h-[80vh] items-center justify-center px-4">
    <div class="w-full max-w-md text-center">
        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-amber-100">
            <svg class="h-8 w-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="mb-2 text-3xl font-bold tracking-tight text-gray-900">Registration Submitted</h1>
        <p class="mb-2 text-gray-600">Your vendor account has been created and is now pending administrator approval. You will be able to log in once an administrator reviews and approves your account.</p>
        <p class="mb-8 text-sm text-gray-500">We'll notify you when your account has been verified.</p>
        <a href="/login" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-8 py-3 text-sm font-bold text-white shadow-sm transition-colors hover:bg-green-700">
            Go to Login
        </a>
    </div>
</div>
@endsection