<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Pelanggan') - Sistem Tagihan</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        @include('layouts.pelanggan-navigation')
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 focus:outline-none lg:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Search Bar (Optional) -->
                    <div class="flex-1 max-w-xl mx-4 hidden md:block">
                        <div class="relative">
                            <input type="text" placeholder="Cari tagihan..." 
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="flex items-center gap-4" x-data="{ dropdownOpen: false }">
                        
                        <!-- Notifications -->
                        <button class="relative text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                3
                            </span>
                        </button>
                        
                        <!-- User Dropdown -->
                        <div class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" 
                                    class="flex items-center gap-3 focus:outline-none">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Pelanggan</p>
                                </div>
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="dropdownOpen" 
                                 @click.away="dropdownOpen = false"
                                 x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                                <a href="{{ route('pelanggan.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    
                    <!-- Breadcrumb -->
                    @if(isset($breadcrumbs))
                    <nav class="text-sm mb-4">
                        <ol class="list-none p-0 inline-flex">
                            @foreach($breadcrumbs as $breadcrumb)
                                @if(!$loop->last)
                                    <li class="flex items-center">
                                        <a href="{{ $breadcrumb['url'] }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $breadcrumb['label'] }}
                                        </a>
                                        <i class="fas fa-chevron-right mx-2 text-gray-400 text-xs"></i>
                                    </li>
                                @else
                                    <li class="text-gray-600">{{ $breadcrumb['label'] }}</li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                    @endif
                    
                    <!-- Flash Messages -->
                    @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3"></i>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle mr-3 mt-1"></i>
                            <div>
                                <p class="font-bold mb-2">Terjadi kesalahan:</p>
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Page Content -->
                    @yield('content')
                    
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white shadow-sm py-4 px-6">
                <div class="text-center text-sm text-gray-600">
                    &copy; {{ date('Y') }} Sistem Tagihan WiFi. All rights reserved.
                </div>
            </footer>
            
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>