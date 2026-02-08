@extends('layouts.petugas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Detail Tagihan Pembayaran</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('petugas.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('petugas.pembayaran.index') }}">Pembayaran</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('petugas.pembayaran.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Info Pelanggan -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Informasi Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small">Nama Pelanggan</label>
                                <h6 class="mb-0">{{ $tagihan->pelanggan->user->name }}</h6>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Email</label>
                                <p class="mb-0">{{ $tagihan->pelanggan->user->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Nomor Telepon</label>
                                <p class="mb-0">{{ $tagihan->pelanggan->no_telepon ?? '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Alamat</label>
                                <p class="mb-0">{{ $tagihan->pelanggan->alamat ?? '-' }}</p>
                            </div>
                            <hr>
                            <div class="mb-2">
                                <label class="text-muted small">Paket Internet</label>
                                <h6 class="mb-0">{{ $tagihan->pelanggan->paket->nama_paket }}</h6>
                            </div>
                            <div class="mb-0">
                                <label class="text-muted small">Kecepatan</label>
                                <p class="mb-0">{{ $tagihan->pelanggan->paket->kecepatan }} Mbps</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Tagihan -->
                <div class="col-md-8 mb-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Detail Tagihan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Periode Tagihan</label>
                                    <h6>
                                        {{ \Carbon\Carbon::create()->month($tagihan->bulan)->translatedFormat('F') }} 
                                        {{ $tagihan->tahun }}
                                    </h6>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Tanggal Jatuh Tempo</label>
                                    <h6>{{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d F Y') }}</h6>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Jumlah Tagihan</label>
                                    <h4 class="text-primary mb-0">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Status</label>
                                    <div>
                                        @if($tagihan->status == 'lunas')
                                            <span class="badge bg-success fs-6">
                                                <i class="bi bi-check-circle"></i> Lunas
                                            </span>
                                        @elseif($tagihan->status == 'menunggu_konfirmasi')
                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="bi bi-clock-history"></i> Menunggu Konfirmasi
                                            </span>
                                        @elseif($tagihan->status == 'nunggak')
                                            <span class="badge bg-danger fs-6">
                                                <i class="bi bi-exclamation-triangle"></i> Nunggak
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">
                                                <i class="bi bi-dash-circle"></i> Belum Bayar
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if($tagihan->status == 'lunas')
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small">Tanggal Bayar</label>
                                        <h6>{{ \Carbon\Carbon::parse($tagihan->tanggal_bayar)->format('d F Y, H:i') }}</h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small">Metode Pembayaran</label>
                                        <h6 class="text-capitalize">
                                            @if($tagihan->metode_bayar == 'tunai')
                                                <i class="bi bi-cash"></i> Tunai
                                            @elseif($tagihan->metode_bayar == 'transfer')
                                                <i class="bi bi-bank"></i> Transfer Bank
                                            @elseif($tagihan->metode_bayar == 'qris')
                                                <i class="bi bi-qr-code"></i> QRIS
                                            @endif
                                        </h6>
                                    </div>
                                    @if($tagihan->jumlah_bayar)
                                        <div class="col-md-6 mb-3">
                                            <label class="text-muted small">Jumlah Dibayar</label>
                                            <h6>Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</h6>
                                        </div>
                                    @endif
                                @endif

                                @if($tagihan->keterangan)
                                    <div class="col-12 mb-3">
                                        <label class="text-muted small">Keterangan</label>
                                        <p class="mb-0">{{ $tagihan->keterangan }}</p>
                                    </div>
                                @endif
                            </div>

                            @if($tagihan->status == 'menunggu_konfirmasi' && $tagihan->bukti_pembayaran)
                                <hr>
                                <div class="alert alert-warning mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Perhatian!</strong> Pelanggan telah mengunggah bukti pembayaran. Silakan verifikasi dan konfirmasi pembayaran.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bukti Pembayaran -->
                    @if($tagihan->bukti_pembayaran)
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="bi bi-file-earmark-image me-2"></i>Bukti Pembayaran</h5>
                            </div>
                            <div class="card-body text-center">
                                @if($tagihan->metode_pembayaran)
                                    <p class="mb-2">
                                        <strong>Metode:</strong> 
                                        <span class="badge bg-info">{{ ucfirst($tagihan->metode_pembayaran) }}</span>
                                    </p>
                                @endif
                                
                                @if($tagihan->catatan_pembayaran)
                                    <p class="mb-3"><strong>Catatan:</strong> {{ $tagihan->catatan_pembayaran }}</p>
                                @endif

                                <img src="{{ Storage::url($tagihan->bukti_pembayaran) }}" 
                                     alt="Bukti Pembayaran" 
                                     class="img-fluid rounded shadow-sm mb-3" 
                                     style="max-height: 500px; cursor: pointer;"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#buktiBayarModal">
                                
                                <div>
                                    <a href="{{ Storage::url($tagihan->bukti_pembayaran) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i> Unduh Bukti
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Form Konfirmasi Pembayaran -->
                    @if($tagihan->status == 'menunggu_konfirmasi' && $tagihan->bukti_pembayaran)
                        <div class="card shadow-sm border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Konfirmasi Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('petugas.pembayaran.konfirmasi', $tagihan->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                        <select name="metode_bayar" class="form-select @error('metode_bayar') is-invalid @enderror" required>
                                            <option value="">-- Pilih Metode --</option>
                                            <option value="tunai" {{ old('metode_bayar', $tagihan->metode_pembayaran) == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                            <option value="transfer" {{ old('metode_bayar', $tagihan->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                            <option value="qris" {{ old('metode_bayar', $tagihan->metode_pembayaran) == 'qris' ? 'selected' : '' }}>QRIS</option>
                                        </select>
                                        @error('metode_bayar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               name="jumlah_bayar" 
                                               class="form-control @error('jumlah_bayar') is-invalid @enderror" 
                                               value="{{ old('jumlah_bayar', $tagihan->jumlah) }}" 
                                               min="0" 
                                               required>
                                        @error('jumlah_bayar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Bayar</label>
                                        <input type="datetime-local" 
                                               name="tanggal_bayar" 
                                               class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                                               value="{{ old('tanggal_bayar', now()->format('Y-m-d\TH:i')) }}">
                                        @error('tanggal_bayar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="keterangan" 
                                                  class="form-control @error('keterangan') is-invalid @enderror" 
                                                  rows="3" 
                                                  placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-circle"></i> Konfirmasi Pembayaran
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#tolakModal">
                                            <i class="bi bi-x-circle"></i> Tolak Pembayaran
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @elseif($tagihan->status != 'lunas' && !$tagihan->bukti_pembayaran)
                        <!-- Form Manual Input Pembayaran -->
                        <div class="card shadow-sm border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Input Pembayaran Manual</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Pelanggan belum mengunggah bukti pembayaran. Anda dapat input pembayaran secara manual jika pelanggan membayar langsung.</p>
                                
                                <form action="{{ route('petugas.pembayaran.konfirmasi', $tagihan->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                        <select name="metode_bayar" class="form-select @error('metode_bayar') is-invalid @enderror" required>
                                            <option value="">-- Pilih Metode --</option>
                                            <option value="tunai">Tunai</option>
                                            <option value="transfer">Transfer Bank</option>
                                            <option value="qris">QRIS</option>
                                        </select>
                                        @error('metode_bayar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                                        <input type="number" 
                                               name="jumlah_bayar" 
                                               class="form-control @error('jumlah_bayar') is-invalid @enderror" 
                                               value="{{ old('jumlah_bayar', $tagihan->jumlah) }}" 
                                               min="0" 
                                               required>
                                        @error('jumlah_bayar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Bayar</label>
                                        <input type="datetime-local" 
                                               name="tanggal_bayar" 
                                               class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                                               value="{{ old('tanggal_bayar', now()->format('Y-m-d\TH:i')) }}">
                                        @error('tanggal_bayar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="keterangan" 
                                                  class="form-control @error('keterangan') is-invalid @enderror" 
                                                  rows="3" 
                                                  placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Simpan Pembayaran
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($tagihan->status == 'lunas')
                        <!-- Action untuk Tagihan Lunas -->
                        <div class="card shadow-sm border-success">
                            <div class="card-body text-center">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 mb-3">Pembayaran Telah Dikonfirmasi</h5>
                                <a href="{{ route('petugas.pembayaran.kwitansi', $tagihan->id) }}" 
                                   class="btn btn-primary" 
                                   target="_blank">
                                    <i class="bi bi-printer"></i> Cetak Kwitansi
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Riwayat Pembayaran -->
            @if($riwayatPembayaran->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Pembayaran Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatPembayaran as $riwayat)
                                        <tr>
                                            <td>
                                                {{ \Carbon\Carbon::create()->month($riwayat->bulan)->translatedFormat('F') }} 
                                                {{ $riwayat->tahun }}
                                            </td>
                                            <td>Rp {{ number_format($riwayat->jumlah, 0, ',', '.') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($riwayat->tanggal_bayar)->format('d/m/Y') }}</td>
                                            <td class="text-capitalize">{{ $riwayat->metode_bayar ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-success">Lunas</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Bukti Bayar -->
@if($tagihan->bukti_pembayaran)
<div class="modal fade" id="buktiBayarModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ Storage::url($tagihan->bukti_pembayaran) }}" 
                     alt="Bukti Pembayaran" 
                     class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal Tolak Pembayaran -->
<div class="modal fade" id="tolakModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('petugas.pembayaran.tolak', $tagihan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Tolak Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Apakah Anda yakin ingin menolak pembayaran ini?
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" 
                                  class="form-control" 
                                  rows="4" 
                                  required 
                                  placeholder="Jelaskan alasan penolakan pembayaran..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Tolak Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    .badge {
        padding: 0.5em 0.8em;
    }
    img[data-bs-toggle="modal"] {
        transition: transform 0.3s ease;
    }
    img[data-bs-toggle="modal"]:hover {
        transform: scale(1.02);
    }
</style>
@endpush