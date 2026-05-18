@extends('layouts.vendor-main')

@section('title', 'Analytics - DormDash')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Analytics & Revenue</h1>
        <p class="mt-2 text-gray-600">Track your sales performance and revenue.</p>
    </div>

    <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-md text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 mb-4 text-indigo-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Not enough data</h3>
        <p class="text-gray-500 max-w-md mx-auto">Analytics and revenue charts will be generated once you start receiving orders.</p>
    </div>
</div>
@endsection
