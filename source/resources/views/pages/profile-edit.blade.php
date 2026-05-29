@extends('layouts.main')

@section('title', 'Edit Profile')

@section('content')
    <div class="mx-auto max-w-2xl px-8 py-12">
        <div class="mb-8">
            <a href="/profile" class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-green-600 transition-colors hover:text-green-700">
                <x-heroicon-o-arrow-left class="h-4 w-4" />
                Back to Profile
            </a>
            <h1 class="text-4xl font-bold tracking-tight text-gray-900">Edit Profile</h1>
            <p class="mt-2 text-gray-600">Update your personal information</p>
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

                <form class="space-y-6" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" x-data="{
                    showCurrent: false,
                    showNew: false,
                    showConfirm: false,
                    newPassword: '',
                    get satisfiedCount() {
                        let count = 0;
                        if (this.newPassword.length >= 8) count++;
                        if (/[A-Z]/.test(this.newPassword)) count++;
                        if (/[a-z]/.test(this.newPassword)) count++;
                        if (/[0-9]/.test(this.newPassword)) count++;
                        if (/[^A-Za-z0-9]/.test(this.newPassword)) count++;
                        return count;
                    },
                    get isPasswordInvalid() {
                        return this.newPassword.length > 0 && this.satisfiedCount < 5;
                    },
                    get strengthText() {
                        if (!this.newPassword) return 'Pending';
                        let count = this.satisfiedCount;
                        if (count <= 1) return 'Very Weak';
                        if (count === 2) return 'Weak';
                        if (count === 3) return 'Medium';
                        if (count === 4) return 'Strong';
                        if (count === 5) return 'Very Strong';
                        return 'Weak';
                    },
                    get strengthColorClass() {
                        if (!this.newPassword) return 'text-gray-400';
                        let count = this.satisfiedCount;
                        if (count <= 1) return 'text-rose-500';
                        if (count === 2) return 'text-amber-500';
                        if (count === 3) return 'text-yellow-500';
                        if (count === 4) return 'text-lime-500';
                        if (count === 5) return 'text-emerald-500';
                        return 'text-rose-500';
                    },
                    get barColorClass() {
                        if (!this.newPassword) return 'bg-gray-300';
                        let count = this.satisfiedCount;
                        if (count <= 1) return 'bg-rose-500';
                        if (count === 2) return 'bg-amber-500';
                        if (count === 3) return 'bg-yellow-500';
                        if (count === 4) return 'bg-lime-500';
                        if (count === 5) return 'bg-emerald-500';
                        return 'bg-rose-500';
                    }
                }">                  @csrf
                    {{-- Personal Information Section --}}
                    <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                        <h3 class="mb-8 text-lg font-bold text-gray-900">Personal Information</h3>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Full Name</label>
                                <input
                                    type="text"
                                    name="username"
                                    value="{{ old('username', $user->username ?? '') }}"
                                    placeholder="Enter your full name"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Email Address</label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $user->email ?? '') }}"
                                    placeholder="Enter your email"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Phone Number</label>
                                <input
                                    type="tel"
                                    name="phone"
                                    value="{{ old('phone', $profile->phone ?? '') }}"
                                    placeholder="Enter your phone number"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Password Change Section --}}
                    <div class="rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-gray-50 p-8 shadow-lg">
                        <h3 class="mb-8 text-lg font-bold text-gray-900">Change Password</h3>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Current Password</label>
                                <div class="relative">
                                    <input
                                        :type="showCurrent ? 'text' : 'password'"
                                        name="current_password"
                                        placeholder="Enter current password"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 pr-12 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                    />
                                    <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                                        <x-heroicon-o-eye x-show="!showCurrent" class="h-5 w-5" />
                                        <x-heroicon-o-eye-slash x-show="showCurrent" x-cloak class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">New Password</label>
                                <div class="relative">
                                    <input
                                        :type="showNew ? 'text' : 'password'"
                                        name="new_password"
                                        x-model="newPassword"
                                        placeholder="Enter new password"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 pr-12 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                    />
                                    <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                                        <x-heroicon-o-eye x-show="!showNew" class="h-5 w-5" />
                                        <x-heroicon-o-eye-slash x-show="showNew" x-cloak class="h-5 w-5" />
                                    </button>
                                </div>

                                {{-- Real-time Password Strength and Checklist --}}
                                <div class="mt-3 p-4 rounded-2xl border border-gray-200 bg-gray-50/50 space-y-3" x-show="newPassword.length > 0" x-transition>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Password Strength</span>
                                        <span class="text-xs font-bold transition-colors duration-300" :class="strengthColorClass" x-text="strengthText">Weak</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full transition-all duration-500 rounded-full" :class="barColorClass" :style="'width: ' + (satisfiedCount / 5 * 100) + '%'"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-2 pt-1 text-xs">
                                        <div class="flex items-center gap-2 transition-colors duration-200" :class="newPassword.length >= 8 ? 'text-emerald-600 font-medium' : 'text-gray-500'">
                                            <div class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full transition-all duration-200 border" :class="newPassword.length >= 8 ? 'bg-emerald-500 text-white border-transparent' : 'bg-gray-200 text-gray-400 border-gray-300'">
                                                <x-heroicon-s-check class="h-2.5 w-2.5" x-show="newPassword.length >= 8" />
                                            </div>
                                            <span>Min. 8 characters</span>
                                        </div>
                                        <div class="flex items-center gap-2 transition-colors duration-200" :class="/[A-Z]/.test(newPassword) ? 'text-emerald-600 font-medium' : 'text-gray-500'">
                                            <div class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full transition-all duration-200 border" :class="/[A-Z]/.test(newPassword) ? 'bg-emerald-500 text-white border-transparent' : 'bg-gray-200 text-gray-400 border-gray-300'">
                                                <x-heroicon-s-check class="h-2.5 w-2.5" x-show="/[A-Z]/.test(newPassword)" />
                                            </div>
                                            <span>Uppercase letter (A-Z)</span>
                                        </div>
                                        <div class="flex items-center gap-2 transition-colors duration-200" :class="/[a-z]/.test(newPassword) ? 'text-emerald-600 font-medium' : 'text-gray-500'">
                                            <div class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full transition-all duration-200 border" :class="/[a-z]/.test(newPassword) ? 'bg-emerald-500 text-white border-transparent' : 'bg-gray-200 text-gray-400 border-gray-300'">
                                                <x-heroicon-s-check class="h-2.5 w-2.5" x-show="/[a-z]/.test(newPassword)" />
                                            </div>
                                            <span>Lowercase letter (a-z)</span>
                                        </div>
                                        <div class="flex items-center gap-2 transition-colors duration-200" :class="/[0-9]/.test(newPassword) ? 'text-emerald-600 font-medium' : 'text-gray-500'">
                                            <div class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full transition-all duration-200 border" :class="/[0-9]/.test(newPassword) ? 'bg-emerald-500 text-white border-transparent' : 'bg-gray-200 text-gray-400 border-gray-300'">
                                                <x-heroicon-s-check class="h-2.5 w-2.5" x-show="/[0-9]/.test(newPassword)" />
                                            </div>
                                            <span>Numeric digit (0-9)</span>
                                        </div>
                                        <div class="flex items-center gap-2 transition-colors duration-200" :class="/[^A-Za-z0-9]/.test(newPassword) ? 'text-emerald-600 font-medium' : 'text-gray-500'">
                                            <div class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full transition-all duration-200 border" :class="/[^A-Za-z0-9]/.test(newPassword) ? 'bg-emerald-500 text-white border-transparent' : 'bg-gray-200 text-gray-400 border-gray-300'">
                                                <x-heroicon-s-check class="h-2.5 w-2.5" x-show="/[^A-Za-z0-9]/.test(newPassword)" />
                                            </div>
                                            <span>Special character (!@#...)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Confirm New Password</label>
                                <div class="relative">
                                    <input
                                        :type="showConfirm ? 'text' : 'password'"
                                        name="new_password_confirmation"
                                        placeholder="Confirm new password"
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 pr-12 text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-green-500 focus:ring-2 focus:ring-green-100 focus:outline-none"
                                    />
                                    <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                                        <x-heroicon-o-eye x-show="!showConfirm" class="h-5 w-5" />
                                        <x-heroicon-o-eye-slash x-show="showConfirm" x-cloak class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Profile Image</label>
                        <input type="file" name="profile_image" accept="image/*" class="w-full" />
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 pt-6">
                        <a href="/profile" class="flex-1 inline-flex items-center justify-center rounded-xl border-2 border-gray-200 px-6 py-3 text-sm font-semibold text-gray-900 transition-all duration-200 hover:bg-gray-50 hover:border-gray-300">
                            Cancel
                        </a>
                        <button type="submit" 
                            :disabled="isPasswordInvalid"
                            :class="isPasswordInvalid ? 'from-gray-400 to-gray-300 cursor-not-allowed opacity-60' : 'from-green-600 to-green-500 hover:shadow-lg hover:from-green-700 hover:to-green-600'"
                            class="flex-1 inline-flex items-center justify-center rounded-xl bg-gradient-to-r px-6 py-3 text-sm font-semibold text-white transition-all duration-200 shadow-md">
                            <x-heroicon-o-check class="h-4 w-4 mr-2" />
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
@endsection
