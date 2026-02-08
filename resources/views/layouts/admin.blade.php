<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
        >
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 bg-blue-900">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-wifi text-2xl"></i>
                    <span class="text-xl font-bold">WiFi Admin</span>
                </div>
                <button 
                    @click="sidebarOpen = false"
                    class="lg:hidden text-white hover:text-gray-300"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="mt-6 px-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                    <i class="fas fa-home w-6"></i>
                    <span class="ml-3">Dashboard</span>
                </a>

                <a href="{{ route('admin.pelanggan.index') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.pelanggan.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                    <i class="fas fa-users w-6"></i>
                    <span class="ml-3">Data Pelanggan</span>
                </a>

                <a href="{{ route('admin.tagihan.index') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.tagihan.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                    <i class="fas fa-file-invoice w-6"></i>
                    <span class="ml-3">Tagihan</span>
                </a>

                <a href="{{ route('admin.tagihan.nunggak') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.tagihan.nunggak') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                    <i class="fas fa-exclamation-triangle w-6"></i>
                    <span class="ml-3">Tagihan Nunggak</span>
                </a>

                <a href="{{ route('admin.paket.index') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.paket.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                    <i class="fas fa-box w-6"></i>
                    <span class="ml-3">Paket WiFi</span>
                </a>

                <a href="{{ route('admin.buku_kas.index') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.buku_kas') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                    <i class="fas fa-chart-line w-6"></i>
                    <span class="ml-3">Buku Kas</span>
                </a>

                <a href="{{ route('admin.laporan.tagihan') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.laporan') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                    <i class="fas fa-chart-line w-6"></i>
                    <span class="ml-3">Laporan Tagihan</span>
                </a>

                <div class="border-t border-blue-700 my-4"></div>

                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-user-cog w-6"></i>
                    <span class="ml-3">Kelola User</span>
                </a>

                <a href="{{ route('admin.settings') }}" 
                   class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-cog w-6"></i>
                    <span class="ml-3">Settings</span>
                </a>
            </nav>

            <!-- User Info -->
            <div class="absolute bottom-0 w-64 p-4 bg-blue-900">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-blue-700 flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-blue-300">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-6">
                    <div class="flex items-center">
                        <button 
                            @click="sidebarOpen = true"
                            class="lg:hidden text-gray-600 hover:text-gray-800 mr-4"
                        >
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-2xl font-bold text-gray-800">@yield('page-title')</h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-xs">
                                3
                            </span>
                        </button>

                        <!-- User Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button 
                                @click="open = !open"
                                class="flex items-center space-x-2 text-gray-700 hover:text-gray-900"
                            >
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>

                            <div 
                                x-show="open"
                                @click.away="open = false"
                                x-cloak
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50"
                            >
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <div class="border-t my-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span>{{ session('warning') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false"
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
    ></div>

    @stack('scripts')
</body>
</html>