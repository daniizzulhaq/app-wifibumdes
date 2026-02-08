<!-- Sidebar Overlay (Mobile) -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false"
     x-cloak
     class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden">
</div>

<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col">
    
    <!-- Logo -->
    <div class="flex items-center justify-between px-6 py-5 bg-blue-900">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-wifi text-blue-600 text-xl"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold">WiFi Portal</h1>
                <p class="text-xs text-blue-200">Pelanggan</p>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden text-white">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    
    <!-- User Info Card -->
    <div class="px-4 py-4 bg-blue-700 bg-opacity-50">
        <div class="flex items-center gap-3">
            <div class="h-12 w-12 rounded-full bg-white flex items-center justify-center text-blue-600 font-bold text-lg">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-blue-200 truncate">
                    @if(Auth::user()->pelanggan)
                        ID: {{ Auth::user()->pelanggan->kode_pelanggan ?? 'N/A' }}
                    @endif
                </p>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        
        <!-- Dashboard -->
        <a href="{{ route('pelanggan.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('pelanggan.dashboard') ? 'bg-white bg-opacity-20 font-semibold' : 'hover:bg-white hover:bg-opacity-10' }}">
            <i class="fas fa-home text-lg w-5"></i>
            <span>Dashboard</span>
        </a>
        
        <!-- Tagihan -->
        <div x-data="{ open: {{ request()->routeIs('pelanggan.tagihan.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg transition-all hover:bg-white hover:bg-opacity-10">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-invoice text-lg w-5"></i>
                    <span>Tagihan</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-cloak class="ml-4 mt-1 space-y-1">
                <a href="{{ route('pelanggan.tagihan.index') }}" 
                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('pelanggan.tagihan.index') ? 'bg-white bg-opacity-20 font-semibold' : 'hover:bg-white hover:bg-opacity-10' }}">
                    <i class="fas fa-list w-5"></i>
                    <span>Semua Tagihan</span>
                </a>

            </div>
        </div>
        
        
        <!-- Paket Saya -->
        <a href="{{ route('pelanggan.paket.index') }}#paket" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all hover:bg-white hover:bg-opacity-10">
            <i class="fas fa-box text-lg w-5"></i>
            <span>Paket Saya</span>
        </a>
        
        <hr class="border-blue-500 my-4">
        
        <!-- Profile -->
        <a href="{{ route('pelanggan.profile') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('pelanggan.profile') ? 'bg-white bg-opacity-20 font-semibold' : 'hover:bg-white hover:bg-opacity-10' }}">
            <i class="fas fa-user text-lg w-5"></i>
            <span>Profile</span>
        </a>
        
        <!-- Bantuan -->
        <a href="#" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all hover:bg-white hover:bg-opacity-10">
            <i class="fas fa-question-circle text-lg w-5"></i>
            <span>Bantuan</span>
        </a>
        
    </nav>
    
    <!-- Logout Button -->
    <div class="px-4 py-4 border-t border-blue-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-red-500 hover:bg-red-600 rounded-lg transition-all font-semibold">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
    
</aside>