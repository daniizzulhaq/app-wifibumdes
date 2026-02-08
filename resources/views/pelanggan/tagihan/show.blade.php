@extends('layouts.pelanggan-app')

@section('title', 'Detail Tagihan')

@section('content')
<div class="space-y-6">
    
    <!-- Breadcrumb -->
    <nav class="text-sm">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('pelanggan.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                    Dashboard
                </a>
                <i class="fas fa-chevron-right mx-2 text-gray-400 text-xs"></i>
            </li>
            <li class="flex items-center">
                <a href="{{ route('pelanggan.tagihan.index') }}" class="text-blue-600 hover:text-blue-800">
                    Tagihan
                </a>
                <i class="fas fa-chevron-right mx-2 text-gray-400 text-xs"></i>
            </li>
            <li class="text-gray-600">Detail</li>
        </ol>
    </nav>
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Tagihan</h1>
            <p class="text-gray-600 mt-1">
                Periode {{ \Carbon\Carbon::parse($tagihan->bulan . '-01-' . $tagihan->tahun)->isoFormat('MMMM YYYY') }}
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            @if($tagihan->status == 'lunas')
                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-2"></i>
                    Lunas
                </span>
            @elseif($tagihan->status == 'menunggu_konfirmasi')
                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-clock mr-2"></i>
                    Menunggu Konfirmasi
                </span>
            @else
                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-800">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Belum Dibayar
                </span>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="flex items-start">
            <i class="fas fa-check-circle text-green-600 text-xl mt-1"></i>
            <div class="ml-3">
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle text-red-600 text-xl mt-1"></i>
            <div class="ml-3">
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Detail Tagihan -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Info Tagihan -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white">
                        <i class="fas fa-file-invoice mr-2"></i>
                        Informasi Tagihan
                    </h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Nomor Tagihan:</span>
                        <span class="font-semibold font-mono">{{ $tagihan->nomor_tagihan ?? 'INV-' . str_pad($tagihan->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Periode:</span>
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($tagihan->bulan . '-01-' . $tagihan->tahun)->isoFormat('MMMM YYYY') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Tanggal Tagihan:</span>
                        <span class="font-semibold">{{ $tagihan->created_at->isoFormat('D MMMM YYYY') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Jatuh Tempo:</span>
                        <span class="font-semibold {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->isPast() && $tagihan->status != 'lunas' ? 'text-red-600' : '' }}">
                            {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->isoFormat('D MMMM YYYY') }}
                        </span>
                    </div>
                    
                    @if($tagihan->tanggal_bayar)
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Tanggal Pembayaran:</span>
                        <span class="font-semibold text-green-600">{{ \Carbon\Carbon::parse($tagihan->tanggal_bayar)->isoFormat('D MMMM YYYY') }}</span>
                    </div>
                    @endif

                    @if($tagihan->metode_pembayaran)
                    <div class="flex justify-between items-center pb-3 border-b">
                        <span class="text-gray-600">Metode Pembayaran:</span>
                        <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $tagihan->metode_pembayaran)) }}</span>
                    </div>
                    @endif
                    
                    <!-- Rincian Biaya -->
                    <div class="pt-4">
                        <h3 class="font-semibold text-gray-800 mb-3">Rincian Biaya:</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Biaya Paket WiFi</span>
                                <span class="font-medium">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total -->
                    <div class="pt-4 border-t-2 border-gray-300">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-800">Total Tagihan:</span>
                            <span class="text-2xl font-bold text-blue-600">
                                Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Paket -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-box mr-2 text-purple-600"></i>
                    Informasi Paket
                </h3>
                @if($tagihan->pelanggan && $tagihan->pelanggan->paket)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nama Paket</p>
                        <p class="font-semibold text-gray-800">{{ $tagihan->pelanggan->paket->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Kecepatan</p>
                        <p class="font-semibold text-gray-800">{{ $tagihan->pelanggan->paket->kecepatan }} Mbps</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600 mb-1">Harga</p>
                        <p class="font-semibold text-gray-800">Rp {{ number_format($tagihan->pelanggan->paket->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endif
            </div>
            
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Upload Bukti Pembayaran -->
            @if($tagihan->status == 'nunggak' || $tagihan->status == 'belum_bayar')
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-upload mr-2 text-blue-600"></i>
                    Upload Bukti Pembayaran
                </h3>
                
                <form action="{{ route('pelanggan.tagihan.upload-bukti', $tagihan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Upload File -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Transfer <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="bukti_pembayaran" accept="image/*" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   onchange="previewImage(event)">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max: 2MB)</p>
                            @error('bukti_pembayaran')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Preview -->
                        <div id="imagePreview" class="hidden">
                            <img id="preview" class="w-full rounded-lg border" alt="Preview">
                        </div>
                        
                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="metode_pembayaran" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Metode</option>
                                <option value="transfer_bank">Transfer Bank</option>
                                <option value="e_wallet">E-Wallet (OVO, GoPay, DANA)</option>
                                <option value="tunai">Tunai</option>
                            </select>
                            @error('metode_pembayaran')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Catatan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea name="catatan" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Catatan tambahan (opsional)"></textarea>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
            @endif
            
            <!-- Status Pembayaran - Menunggu Konfirmasi -->
            @if($tagihan->status == 'menunggu_konfirmasi')
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <div class="flex items-start">
                    <i class="fas fa-clock text-yellow-600 text-2xl mt-1"></i>
                    <div class="ml-4">
                        <h3 class="font-semibold text-yellow-800 mb-2">Menunggu Konfirmasi</h3>
                        <p class="text-sm text-yellow-700 mb-3">
                            Bukti pembayaran Anda sedang diproses oleh admin. Mohon tunggu konfirmasi.
                        </p>
                        
                        @if($tagihan->metode_pembayaran)
                        <div class="mb-3 pb-3 border-b border-yellow-200">
                            <p class="text-xs text-yellow-600 mb-1">Metode Pembayaran:</p>
                            <p class="text-sm font-semibold text-yellow-800">
                                {{ ucfirst(str_replace('_', ' ', $tagihan->metode_pembayaran)) }}
                            </p>
                        </div>
                        @endif

                        @if($tagihan->catatan_pembayaran && !str_contains($tagihan->catatan_pembayaran, 'Ditolak'))
                        <div class="mb-3">
                            <p class="text-xs text-yellow-600 mb-1">Catatan Anda:</p>
                            <p class="text-sm text-yellow-700 italic">{{ $tagihan->catatan_pembayaran }}</p>
                        </div>
                        @endif

                        @if($tagihan->bukti_pembayaran)
                        <div class="mt-4">
                            <p class="text-xs text-yellow-600 mb-2">Bukti yang telah diupload:</p>
                            <img src="{{ Storage::url($tagihan->bukti_pembayaran) }}" 
                                 alt="Bukti Pembayaran" 
                                 class="w-full rounded-lg border-2 border-yellow-300 mb-3">
                            <a href="{{ Storage::url($tagihan->bukti_pembayaran) }}" target="_blank" 
                               class="block w-full text-center bg-yellow-600 hover:bg-yellow-700 text-white py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-image mr-1"></i>
                                Lihat Bukti Pembayaran
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Status Pembayaran - Ditolak -->
            @if($tagihan->status == 'nunggak' && $tagihan->catatan_pembayaran && str_contains($tagihan->catatan_pembayaran, 'Ditolak'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-600 text-2xl mt-1"></i>
                    <div class="ml-4">
                        <h3 class="font-semibold text-red-800 mb-2">Pembayaran Ditolak</h3>
                        <p class="text-sm text-red-700 mb-3">
                            {{ $tagihan->catatan_pembayaran }}
                        </p>
                        <p class="text-xs text-red-600">
                            Silakan upload ulang bukti pembayaran yang benar.
                        </p>
                    </div>
                </div>
            </div>
            @endif
            
           <!-- Status Lunas -->
@if($tagihan->status == 'lunas')
<div class="bg-green-50 border border-green-200 rounded-xl p-6">
    <div class="flex items-start">
        <i class="fas fa-check-circle text-green-600 text-2xl mt-1"></i>
        <div class="ml-4 flex-1">
            <h3 class="font-semibold text-green-800 mb-2">Pembayaran Berhasil</h3>
            <p class="text-sm text-green-700 mb-3">
                Terima kasih! Pembayaran Anda telah dikonfirmasi.
            </p>
            @if($tagihan->tanggal_bayar)
            <p class="text-xs text-green-600 mb-3">
                Dikonfirmasi pada: {{ \Carbon\Carbon::parse($tagihan->tanggal_bayar)->isoFormat('D MMMM YYYY, HH:mm') }}
            </p>
            @endif
            <a href="{{ route('pelanggan.tagihan.cetak-invoice', $tagihan->id) }}" 
               target="_blank"
               class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                <i class="fas fa-print mr-2"></i>
                Cetak Invoice
            </a>
        </div>
    </div>
</div>
@endif
            
            <!-- Informasi Rekening -->
            @if($tagihan->status != 'lunas')
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="font-semibold text-blue-800 mb-3">
                    <i class="fas fa-university mr-2"></i>
                    Informasi Pembayaran
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-blue-600 font-medium">Bank BCA</p>
                        <p class="text-blue-900 font-mono font-bold">1234567890</p>
                        <p class="text-blue-700">a.n. PT WiFi Indonesia</p>
                    </div>
                    <hr class="border-blue-200">
                    <div>
                        <p class="text-blue-600 font-medium">Bank Mandiri</p>
                        <p class="text-blue-900 font-mono font-bold">0987654321</p>
                        <p class="text-blue-700">a.n. PT WiFi Indonesia</p>
                    </div>
                    <hr class="border-blue-200">
                    <div>
                        <p class="text-blue-600 font-medium">E-Wallet</p>
                        <p class="text-blue-700">OVO / GoPay / DANA</p>
                        <p class="text-blue-900 font-mono font-bold">081234567890</p>
                        <p class="text-blue-700">a.n. PT WiFi Indonesia</p>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Bantuan -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-md p-6 text-white">
                <h3 class="font-semibold mb-2">
                    <i class="fas fa-headset mr-2"></i>
                    Butuh Bantuan?
                </h3>
                <p class="text-sm text-purple-100 mb-4">
                    Hubungi customer service untuk bantuan pembayaran
                </p>
                <a href="https://wa.me/6281234567890" target="_blank" 
                   class="block w-full bg-white text-purple-600 text-center py-2 rounded-lg font-semibold hover:bg-purple-50 transition-colors">
                    <i class="fab fa-whatsapp mr-2"></i>
                    Chat WhatsApp
                </a>
            </div>
            
        </div>
        
    </div>
    
</div>

@push('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection