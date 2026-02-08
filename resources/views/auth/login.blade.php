@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 px-4">
    <div class="max-w-md w-full">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4">
                <i class="fas fa-wifi text-3xl text-blue-600"></i>
            </div>
            <h2 class="text-3xl font-bold text-white">WiFi Management</h2>
            <p class="text-blue-100 mt-2">Sistem Manajemen Pembayaran WiFi</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-lg shadow-2xl p-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Login</h3>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-envelope mr-1"></i> Email
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Masukkan email Anda"
                        required
                        autofocus
                    >
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-lock mr-1"></i> Password
                    </label>
                    <div class="relative" x-data="{ showPassword: false }">
                        <input 
                            :type="showPassword ? 'text' : 'password'"
                            name="password" 
                            id="password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Masukkan password Anda"
                            required
                        >
                        <button 
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                        >
                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                    </label>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lupa Password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Demo Account:
                </p>
                <div class="mt-2 text-xs text-gray-500 space-y-1">
                    <p><strong>Admin:</strong> admin@wifi.com / password</p>
                    <p><strong>Petugas:</strong> petugas@wifi.com / password</p>
                    <p><strong>Pelanggan:</strong> pelanggan@wifi.com / password</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-white text-sm">
            <p>&copy; {{ date('Y') }} WiFi Management System. All rights reserved.</p>
        </div>
    </div>
</div>
@endsection