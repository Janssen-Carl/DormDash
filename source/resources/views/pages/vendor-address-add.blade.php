@extends('layouts.vendor-main')

@section('title', 'Add Address')

@section('content')
    <div class="mx-auto max-w-2xl px-8 py-12">
        <div class="mb-8">
            <a href="{{ route('vendor.profile') }}" class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 transition-colors hover:text-emerald-700">
                <x-heroicon-o-arrow-left class="h-4 w-4" />
                Back to Profile
            </a>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Add Address</h1>
            <p class="mt-1 text-sm text-zinc-500">Add a new business address to your vendor profile</p>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-3">
                <ul class="space-y-1 text-sm font-medium text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/address/add" class="space-y-6">
            @csrf

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h3 class="mb-6 text-base font-bold text-zinc-900">Address Details</h3>

                <div class="space-y-5">
                    <div>
                        <label for="street" class="mb-1.5 block text-xs font-semibold text-zinc-500 uppercase tracking-wider">Street / Building / Unit</label>
                        <input
                            id="street"
                            name="street"
                            type="text"
                            value="{{ old('street') }}"
                            placeholder="e.g., Room 123, Dormitory A, University Campus"
                            class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition-all focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"
                            required
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label for="city" class="mb-1.5 block text-xs font-semibold text-zinc-500 uppercase tracking-wider">City</label>
                            <input
                                id="city"
                                name="city"
                                type="text"
                                value="{{ old('city') }}"
                                placeholder="e.g., Metro Manila"
                                class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition-all focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"
                                required
                            />
                        </div>
                        <div>
                            <label for="country" class="mb-1.5 block text-xs font-semibold text-zinc-500 uppercase tracking-wider">Country</label>
                            <input
                                id="country"
                                name="country"
                                type="text"
                                value="{{ old('country', 'Philippines') }}"
                                placeholder="e.g., Philippines"
                                class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition-all focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <label for="postal_code" class="mb-1.5 block text-xs font-semibold text-zinc-500 uppercase tracking-wider">Postal Code</label>
                        <input
                            id="postal_code"
                            name="postal_code"
                            type="text"
                            value="{{ old('postal_code') }}"
                            placeholder="e.g., 4200"
                            class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition-all focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"
                        />
                    </div>

                    <div>
                        <label for="phone" class="mb-1.5 block text-xs font-semibold text-zinc-500 uppercase tracking-wider">Phone (optional)</label>
                        <input
                            id="phone"
                            name="phone"
                            type="text"
                            value="{{ old('phone') }}"
                            placeholder="e.g., +63 912 345 6789"
                            class="w-full rounded-xl border border-zinc-200 px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition-all focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none"
                        />
                    </div>

                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-zinc-200 bg-zinc-50 p-4 transition hover:bg-zinc-100">
                        <input type="checkbox" name="is_default" value="1" class="rounded border-zinc-300 text-emerald-600 focus:ring-emerald-600" />
                        <span class="text-sm font-semibold text-zinc-700">Set as default business address</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('vendor.profile') }}" class="flex-1 inline-flex items-center justify-center rounded-xl border border-zinc-200 px-5 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100">
                    Cancel
                </a>
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-700">
                    <x-heroicon-o-check class="h-4 w-4" />
                    Add Address
                </button>
            </div>
        </form>
    </div>
@endsection
