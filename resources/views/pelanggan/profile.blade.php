@extends('layouts.pelanggan-app')

@section('title', 'Profile Saya')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Profile Saya</h1>
        <p class="text-gray-600 mt-1">Kelola informasi akun dan profil Anda</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-24"></div>
                <div class="px-6 pb-6">
                    <div class="flex flex-col items-center -mt-12">
                        <div class="h-24 w-24 rounded-full bg-white border-4 border-white shadow-lg flex items-center justify-center text-blue-600 font-bold text-3xl">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mt-4">{{ Auth::user()->name }}</h3>
                        <p class="text-gray-600 text-sm">{{ Auth::user()->email }}</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-3">
                            <i class="fas fa-circle text-xs mr-2"></i>
                            {{ ucfirst($pelanggan->status ?? 'N/A') }}
                        </span>
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-id-card w-5 text-gray-400"></i>
                            <span class="ml-3 text-gray-600">ID:</span>
                            <span class="ml-auto font-mono font-semibold">{{ $pelanggan->kode_pelanggan ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-calendar w-5 text-gray-400"></i>
                            <span class="ml-3 text-gray-600">Bergabung:</span>
                            <span class="ml-auto font-semibold">{{ Auth::user()->created_at->isoFormat('MMM Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-md p-6 mt-6">
                <h3 class="font-semibold text-gray-800 mb-4">Statistik</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Tagihan</span>
                        <span class="font-bold text-blue-600">{{ $stats['total_tagihan'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Sudah Lunas</span>
                        <span class="font-bold text-green-600">{{ $stats['tagihan_lunas'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Belum Bayar</span>
                        <span class="font-bold text-red-600">{{ $stats['tagihan_belum_bayar'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Informasi Pribadi -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Informasi Pribadi
                    </h2>
                    <button onclick="toggleEdit('personal')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </button>
                </div>
                
                <div class="p-6">
                    <form id="personalForm" action="{{ route('pelanggan.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}" required disabled
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}" required disabled
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor HP <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="no_hp" value="{{ $pelanggan->no_hp ?? '' }}" required disabled
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Lahir
                                </label>
                                <input type="date" name="tanggal_lahir" value="{{ $pelanggan->tanggal_lahir ?? '' }}" disabled
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea name="alamat" rows="3" required disabled
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100">{{ $pelanggan->alamat ?? '' }}</textarea>
                            </div>
                        </div>
                        
                        <div id="personalButtons" class="hidden mt-6 flex gap-3">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="cancelEdit('personal')" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-semibold transition-colors">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Informasi Langganan -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-wifi mr-2 text-purple-600"></i>
                        Informasi Langganan
                    </h2>
                </div>
                
                <div class="p-6">
                    @if(isset($paket_aktif))
                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $paket_aktif->nama_paket }}</h3>
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Kecepatan</p>
                                        <p class="text-lg font-bold text-purple-600">{{ $paket_aktif->kecepatan }} Mbps</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Biaya/Bulan</p>
                                        <p class="text-lg font-bold text-blue-600">Rp {{ number_format($paket_aktif->harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mt-4">{{ $paket_aktif->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                            <div class="ml-4">
                                <div class="h-16 w-16 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-purple-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-600">Belum ada paket aktif</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Keamanan Akun -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-lock mr-2 text-red-600"></i>
                        Keamanan Akun
                    </h2>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('pelanggan.profile.change-password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Lama <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="current_password" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Baru <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="new_password" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="new_password_confirmation" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                                <i class="fas fa-key mr-2"></i>
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
        
    </div>
    
</div>

@push('scripts')
<script>
function toggleEdit(section) {
    const form = document.getElementById(section + 'Form');
    const inputs = form.querySelectorAll('input, textarea');
    const buttons = document.getElementById(section + 'Buttons');
    
    inputs.forEach(input => {
        input.disabled = false;
    });
    
    buttons.classList.remove('hidden');
}

function cancelEdit(section) {
    const form = document.getElementById(section + 'Form');
    const inputs = form.querySelectorAll('input, textarea');
    const buttons = document.getElementById(section + 'Buttons');
    
    inputs.forEach(input => {
        input.disabled = true;
    });
    
    buttons.classList.add('hidden');
    form.reset();
}
</script>
@endpush
@endsection