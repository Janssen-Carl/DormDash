@extends('layouts.main')

@section('title', 'My Profile')

@section('content')
@php
    // Ensure $user and $profile are always available to the view (fallbacks for callers that don't pass them)
    $user = $user ?? auth()->user();
    $profile = $profile ?? null;
    if (!$profile && $user) {
        if ($user->role === 'vendor') {
            $profile = $user->vendor;
        } elseif ($user->role === 'customer') {
            $profile = $user->customer;
        }
    }
@endphp
            <div class="mx-auto max-w-6xl px-8 py-12" x-data="{ showPhotoModal: false, hasFile: false, fileName: '' }">
                <div class="mb-12 flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
                    <div>
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900">My Profile</h1>
                        <p class="mt-2 text-gray-600">View and manage your account information</p>
                    </div>
                    <a href="/profile/edit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-6 py-3 text-sm font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-green-700 hover:to-green-600 shadow-md">
                        <x-heroicon-o-pencil class="h-4 w-4" />
                        Edit Profile
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    {{-- Sidebar Profile Card --}}
                    <div class="lg:col-span-1">
                        <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="flex flex-col items-center text-center">
                                {{-- Profile Picture with Edit Button --}}
                                <div class="relative group">
                                    @if(!empty($profile->profile_img))
                                        <img src="{{ asset($profile->profile_img) }}" alt="Profile" class="h-24 w-24 rounded-full object-cover shadow-2xl" />
                                    @else
                                        <div class="flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-green-500 via-green-600 to-emerald-600 text-4xl font-bold text-white shadow-2xl">
                                            {{ strtoupper(substr($user->username ?? 'U', 0, 2)) }}
                                        </div>
                                    @endif

                                    <button 
                                        @click="showPhotoModal = true"
                                        type="button" 
                                        class="absolute bottom-0 right-0 flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-white shadow-lg transition-all duration-200 hover:bg-green-700 hover:shadow-xl opacity-0 group-hover:opacity-100"
                                    >
                                        <x-heroicon-o-camera class="h-4 w-4" />
                                    </button>
                                </div>

                                <h2 class="mt-8 text-xl font-bold text-gray-900">{{ $user->username }}</h2>
                                <p class="mt-1 text-sm text-green-600 font-semibold">{{ ucfirst($user->role) }} Account</p>
                                <p class="mt-2 text-gray-600">{{ $user->email }}</p>
                                <p class="mt-1 text-xs text-gray-500">Member since {{ optional($user->created_at)->format('M Y') ?? '' }}</p>

                                <form action="/logout" method="POST" class="mt-8 w-full border-t border-gray-200 pt-6">
                                    @csrf
                                    <button type="submit" class="w-full rounded-xl border border-red-200 bg-gradient-to-r from-red-50 to-pink-50 py-3 text-sm font-semibold text-red-600 transition-all duration-200 hover:from-red-100 hover:to-pink-100 hover:border-red-300 hover:shadow-md">
                                        <x-heroicon-o-arrow-right-start-on-rectangle class="inline h-4 w-4 mr-2" />
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Personal Information --}}
                        <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                            <h3 class="mb-8 text-lg font-bold text-gray-900">Personal Information</h3>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 cursor-not-allowed">{{ $user->username }}</div>
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                                            <x-heroicon-o-user class="h-5 w-5" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 cursor-not-allowed">{{ $user->email }}</div>
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                            <x-heroicon-o-envelope class="h-5 w-5" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-600 cursor-not-allowed">{{ $profile->phone ?? '-' }}</div>
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-600">
                                            <x-heroicon-o-phone class="h-5 w-5" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Delivery Addresses --}}
                        <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="mb-8 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-900">Delivery Addresses</h3>
                                <a href="/address-payment/add" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-2 text-xs font-semibold text-green-600 transition-all duration-200 hover:bg-green-100 hover:border-green-300 hover:shadow-md">
                                    <x-heroicon-o-plus class="h-4 w-4" />
                                    Add Address
                                </a>
                            </div>

                             <div class="space-y-3">
                                 @forelse($addresses as $address)
                                     @php
                                         $isDefault = false;
                                         if ($user->role === 'vendor' && $profile) {
                                             $isDefault = ($profile->address_id == $address->address_id);
                                         } elseif ($user->role === 'customer' && $profile) {
                                             $isDefault = ($profile->primary_address_id == $address->address_id);
                                         }
                                     @endphp
                                     <div class="group rounded-2xl border-2 {{ $isDefault ? 'border-green-200 bg-gradient-to-br from-green-50 to-emerald-50' : 'border-gray-200 bg-white' }} p-5 transition-all duration-200 hover:shadow-md">
                                         <div class="flex items-start justify-between">
                                             <div>
                                                 <div class="flex items-center gap-3">
                                                     <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $isDefault ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                         @if($isDefault)
                                                             <x-heroicon-o-check-circle class="h-5 w-5" />
                                                         @else
                                                             <x-heroicon-o-map-pin class="h-5 w-5" />
                                                         @endif
                                                     </div>
                                                     <p class="font-bold text-gray-900">
                                                         {{ $isDefault ? 'Default Address' : 'Secondary Address' }}
                                                     </p>
                                                 </div>
                                                 <p class="mt-3 text-sm text-gray-700 font-medium">{{ $address->street }}</p>
                                                 <p class="mt-1 text-xs text-gray-600">{{ $address->city }}, {{ $address->country }}</p>
                                             </div>
                                             <div class="flex items-center gap-2">
                                                 <form action="{{ route('address.delete', $address->address_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?')">
                                                     @csrf
                                                     @method('DELETE')
                                                     <button type="submit" class="text-gray-400 transition-all duration-200 hover:text-red-600 opacity-0 group-hover:opacity-100">
                                                         <x-heroicon-o-trash class="h-5 w-5" />
                                                     </button>
                                                 </form>
                                             </div>
                                         </div>
                                     </div>
                                 @empty
                                     <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">
                                         <x-heroicon-o-map-pin class="mx-auto h-8 w-8 text-gray-400 mb-2" />
                                         <p class="text-sm font-semibold">No saved addresses</p>
                                         <p class="text-xs mt-1">Add a new delivery address to get started.</p>
                                     </div>
                                 @endforelse
                             </div>
                        </div>

                        {{-- Payment Methods --}}
                        <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg transition-all duration-300 hover:shadow-xl">
                            <div class="mb-8 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-900">Payment Methods</h3>
                                <a href="/address-payment/add?tab=payment" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-2 text-xs font-semibold text-green-600 transition-all duration-200 hover:bg-green-100 hover:border-green-300 hover:shadow-md">
                                    <x-heroicon-o-plus class="h-4 w-4" />
                                    Add Card
                                </a>
                            </div>

                            <div class="space-y-3">
                                @if($user->role === 'customer' && $profile)
                                    @forelse($profile->bankingInfos as $card)
                                        @php
                                            $isDefault = ($profile->primary_banking_info == $card->banking_id);
                                            // Determine gradient color based on payment_method / card brand
                                            $brand = strtolower($card->payment_method);
                                            $gradient = 'from-blue-600 to-blue-700'; // Default visa
                                            if ($brand === 'mastercard') {
                                                $gradient = 'from-orange-600 to-red-600';
                                            } elseif ($brand === 'amex') {
                                                $gradient = 'from-teal-600 to-emerald-600';
                                            } elseif ($brand === 'discover') {
                                                $gradient = 'from-purple-600 to-indigo-600';
                                            }
                                        @endphp
                                        <div class="group rounded-2xl border-2 border-transparent bg-gradient-to-br {{ $gradient }} p-5 shadow-lg transition-all duration-200 hover:shadow-xl overflow-hidden relative">
                                            <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-5 rounded-full -mr-12 -mt-12"></div>
                                            <div class="flex items-center justify-between relative z-10">
                                                <div class="text-white w-full">
                                                    <div class="flex justify-between items-start">
                                                        <p class="text-xs font-semibold opacity-90">{{ strtoupper($card->payment_method) }}</p>
                                                        @if($isDefault)
                                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-bold text-green-700 shadow-sm">Default</span>
                                                        @endif
                                                    </div>
                                                    <p class="mt-6 text-lg font-bold tracking-widest">•••• •••• •••• {{ $card->acc_last4_no }}</p>
                                                    <div class="mt-4 flex justify-between items-end">
                                                        <div>
                                                            <p class="text-xs opacity-75">Card Holder</p>
                                                            <p class="text-sm font-semibold">{{ $card->account_name }}</p>
                                                        </div>
                                                        <div class="flex items-center gap-3">
                                                            <form action="{{ route('payment.delete', $card->banking_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this payment card?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-white/80 transition-all duration-200 hover:text-white opacity-0 group-hover:opacity-100">
                                                                    <x-heroicon-o-trash class="h-5 w-5" />
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">
                                            <x-heroicon-o-credit-card class="mx-auto h-8 w-8 text-gray-400 mb-2" />
                                            <p class="text-sm font-semibold">No payment methods saved</p>
                                            <p class="text-xs mt-1">Add a credit or debit card for faster checkouts.</p>
                                        </div>
                                    @endforelse
                                @else
                                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6 text-center text-gray-500">
                                        <p class="text-sm font-semibold">Payment Methods Disabled</p>
                                        <p class="text-xs mt-1">Payment cards are only managed on Customer accounts.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Upload Photo Modal --}}
                <div 
                    x-show="showPhotoModal" 
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/10"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                >
                    <div 
                        @click.away="showPhotoModal = false"
                        class="relative w-full max-w-md transform rounded-3xl bg-white shadow-xl transition-all duration-300"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                    >
                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between border-b border-gray-200 px-8 py-6">
                            <h2 class="text-xl font-bold text-gray-900">Upload Profile Photo</h2>
                            <button 
                                @click="showPhotoModal = false"
                                type="button" 
                                class="text-gray-400 transition-colors duration-200 hover:text-gray-600"
                            >
                                <x-heroicon-o-x-mark class="h-6 w-6" />
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="px-8 py-6">
                            <form class="space-y-6" action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                {{-- Upload Area --}}
                                <div 
                                    class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-300 bg-gradient-to-br from-gray-50 to-gray-100 p-8 transition-all duration-200 hover:border-green-400 hover:bg-green-50 cursor-pointer"
                                    @dragover.prevent="$el.classList.add('border-green-500', 'bg-green-50')"
                                    @dragleave.prevent="$el.classList.remove('border-green-500', 'bg-green-50')"
                                    @drop.prevent="$el.classList.remove('border-green-500', 'bg-green-50')"
                                    @click="$refs.fileInput.click()"
                                >
                                    <x-heroicon-o-arrow-up-tray class="h-12 w-12 text-gray-400 mb-3" />
                                    <p class="text-sm font-semibold text-gray-900">Click to upload or drag and drop</p>
                                    <p class="mt-1 text-xs text-gray-600">PNG, JPG, GIF up to 4MB</p>
                                    <input 
                                        type="file" 
                                        name="profile_image" 
                                        x-ref="fileInput" 
                                        class="hidden" 
                                        accept="image/*" 
                                        @change="if ($event.target.files[0]) { hasFile = true; fileName = $event.target.files[0].name; }" 
                                    />
                                </div>

                                {{-- File Info --}}
                                <div class="rounded-xl bg-blue-50 border border-blue-200 p-4">
                                    <div class="flex gap-3">
                                        <div class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                                            <x-heroicon-o-information-circle class="h-4 w-4" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-blue-900">Best for profile photos</p>
                                            <p class="mt-1 text-xs text-blue-700">Use clear, square images for best results. Recommended size: 400x400px</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Preview (if file selected) --}}
                                <div class="relative">
                                    <div class="flex h-32 items-center justify-center rounded-xl border-2 border-gray-200 bg-gray-50">
                                        <div class="text-center" x-show="!hasFile">
                                            <x-heroicon-o-photo class="mx-auto h-8 w-8 text-gray-400" />
                                            <p class="mt-2 text-sm text-gray-600">No image selected</p>
                                        </div>
                                        <div class="text-center" x-show="hasFile" x-cloak>
                                            <x-heroicon-o-check-circle class="mx-auto h-8 w-8 text-green-600" />
                                            <p class="mt-2 text-sm font-semibold text-gray-900" x-text="fileName"></p>
                                            <p class="mt-1 text-xs text-green-600">Ready to upload!</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Form Actions --}}
                                <div class="flex gap-3 pt-4">
                                    <button 
                                        @click="showPhotoModal = false; hasFile = false; fileName = ''"
                                        type="button" 
                                        class="flex-1 rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-50 hover:border-gray-300"
                                    >
                                        Cancel
                                    </button>
                                    <button 
                                        type="submit"
                                        class="flex-1 rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-4 py-3 text-sm font-semibold text-white transition-all duration-200 hover:shadow-lg hover:from-green-700 hover:to-green-600 shadow-md"
                                    >
                                        Upload Photo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
