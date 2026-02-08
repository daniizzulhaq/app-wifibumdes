@extends('layouts.admin')

@section('title', 'Detail Tagihan')

@section('content')
<style>
    .container-custom {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .info-label {
        font-weight: 600;
        color: #6b7280;
    }
    
    .info-value {
        color: #111827;
        font-weight: 500;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
    }
    
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fed7aa; color: #92400e; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-success { background: #10b981; color: white; }
    .btn-success:hover { background: #059669; }
    .btn-danger { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2626; }
    .btn-secondary { background: #6b7280; color: white; }
    .btn-secondary:hover { background: #4b5563; }
    
    .bukti-bayar-img {
        max-width: 100%;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
    }
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    }
    
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border-left: 4px solid #10b981;
    }
    
    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .modal {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-dialog {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }

    .modal-header {
        padding: 20px;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .modal-header .close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 20px;
        border-top: 2px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
    }
</style>

<div class="container-custom">
    <!-- Header -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.tagihan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <!-- Detail Tagihan -->
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0;">Detail Tagihan</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Pelanggan:</span>
                        <span class="info-value">{{ $tagihan->pelanggan->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Periode:</span>
                        <span class="info-value">
                            @php
                                $namaBulan = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            {{ $namaBulan[$tagihan->bulan] ?? 'N/A' }} {{ $tagihan->tahun }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Paket:</span>
                        <span class="info-value">{{ $tagihan->pelanggan->paket->nama ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jumlah:</span>
                        <span class="info-value" style="font-size: 18px; color: #667eea;">
                            Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Jatuh Tempo:</span>
                        <span class="info-value">
                            {{ $tagihan->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d/m/Y') : '-' }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span>
                            @if($tagihan->status == 'lunas')
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                            @elseif($tagihan->status == 'menunggu_konfirmasi')
                                <span class="badge badge-info"><i class="fas fa-clock"></i> Menunggu Konfirmasi</span>
                            @elseif($tagihan->status == 'nunggak')
                                <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Nunggak</span>
                            @else
                                <span class="badge badge-warning"><i class="fas fa-clock"></i> Belum Bayar</span>
                            @endif
                        </span>
                    </div>
                    @if($tagihan->tanggal_bayar)
                    <div class="info-row">
                        <span class="info-label">Tanggal Bayar:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($tagihan->tanggal_bayar)->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    @if($tagihan->metode_pembayaran)
                    <div class="info-row">
                        <span class="info-label">Metode Pembayaran:</span>
                        <span class="info-value">{{ ucfirst(str_replace('_', ' ', $tagihan->metode_pembayaran)) }}</span>
                    </div>
                    @endif
                    @if($tagihan->catatan_pembayaran)
                    <div class="info-row" style="border-bottom: none;">
                        <span class="info-label">Catatan:</span>
                        <span class="info-value">{{ $tagihan->catatan_pembayaran }}</span>
                    </div>
                    @endif

                    {{-- Tampilkan info konfirmator jika tagihan sudah lunas --}}
                    @if($tagihan->konfirmator)
                        <div style="margin-top: 20px; padding: 15px; background: #f0fdf4; border-left: 4px solid #10b981; border-radius: 8px;">
                            <h6 style="margin: 0 0 10px 0; color: #065f46; font-size: 13px; font-weight: 700;">
                                <i class="fas fa-user-check"></i> DIKONFIRMASI OLEH
                            </h6>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 16px;">
                                    {{ strtoupper(substr($tagihan->konfirmator->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #111827;">{{ $tagihan->konfirmator->name }}</div>
                                    <small style="color: #6b7280;">
                                        <i class="fas fa-user-shield"></i> {{ ucfirst($tagihan->konfirmator->role) }} • 
                                        <i class="fas fa-envelope"></i> {{ $tagihan->konfirmator->email }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Bukti Pembayaran & Actions -->
        <div>
            @if($tagihan->bukti_pembayaran)
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0;">Bukti Pembayaran</h3>
                </div>
                <div class="card-body">
                    <img src="{{ Storage::url($tagihan->bukti_pembayaran) }}" 
                         alt="Bukti Pembayaran" 
                         class="bukti-bayar-img">
                    
                    <div style="margin-top: 15px;">
                        <a href="{{ Storage::url($tagihan->bukti_pembayaran) }}" 
                           target="_blank" 
                           class="btn btn-secondary" 
                           style="width: 100%; justify-content: center;">
                            <i class="fas fa-download"></i> Download Bukti
                        </a>
                    </div>
                </div>
            </div>
            @endif
            
            @if($tagihan->status == 'menunggu_konfirmasi')
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0;">Aksi Pembayaran</h3>
                </div>
                <div class="card-body">
                    <!-- Form Terima Pembayaran -->
                    <form action="{{ route('admin.tagihan.konfirmasi', $tagihan->id) }}" 
                          method="POST" 
                          style="margin-bottom: 10px;" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menerima pembayaran ini?')">
                        @csrf
                        <input type="hidden" name="metode_bayar" value="{{ $tagihan->metode_pembayaran ?? 'transfer' }}">
                        <input type="hidden" name="keterangan" value="Pembayaran dikonfirmasi via upload bukti transfer">
                        
                        <button type="submit" class="btn btn-success" style="width: 100%; justify-content: center;">
                            <i class="fas fa-check"></i> Terima Pembayaran
                        </button>
                    </form>
                    
                    <!-- Button Tolak -->
                    <button type="button" onclick="openTolakModal()" class="btn btn-danger" style="width: 100%; justify-content: center;">
                        <i class="fas fa-times"></i> Tolak Pembayaran
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Tolak Pembayaran -->
<div class="modal" id="tolakModal" style="display: none;">
    <div class="modal-dialog">
        <form action="{{ route('admin.tagihan.tolak', $tagihan->id) }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5>Tolak Pembayaran</h5>
                <button type="button" class="close" onclick="closeTolakModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Alasan Penolakan <span style="color: #ef4444;">*</span></label>
                    <textarea name="alasan_penolakan" class="form-control" rows="4" required 
                              placeholder="Berikan alasan mengapa pembayaran ditolak..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeTolakModal()">Batal</button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Tolak Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openTolakModal() {
    document.getElementById('tolakModal').style.display = 'flex';
}

function closeTolakModal() {
    document.getElementById('tolakModal').style.display = 'none';
}
</script>
@endsection