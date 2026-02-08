@extends('layouts.petugas')

@section('title', 'Detail Pelanggan')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
        font-size: 14px;
        color: #6b7280;
    }

    .breadcrumb a {
        color: #667eea;
        text-decoration: none;
        transition: color 0.3s;
    }

    .breadcrumb a:hover {
        color: #764ba2;
    }

    .breadcrumb-separator {
        color: #d1d5db;
    }

    .detail-header {
        background: white;
        padding: 32px;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .detail-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: var(--primary-gradient);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
    }

    .pelanggan-info {
        flex: 1;
    }

    .pelanggan-name {
        font-size: 32px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pelanggan-id {
        font-size: 14px;
        color: #6b7280;
        font-weight: 600;
    }

    .status-badge-large {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 12px;
    }

    .status-badge-large.aktif {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .status-badge-large.pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .status-badge-large.nonaktif {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .action-buttons-header {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    .info-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .card-header {
        background: var(--primary-gradient);
        color: white;
        padding: 20px 24px;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-body {
        padding: 24px;
    }

    .info-row {
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6b7280;
        font-size: 14px;
    }

    .info-value {
        color: #111827;
        font-size: 14px;
        font-weight: 500;
    }

    .info-value strong {
        color: #667eea;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .stat-item {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid;
        transition: all 0.3s;
    }

    .stat-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .stat-item.success { border-left-color: var(--success-color); }
    .stat-item.danger { border-left-color: var(--danger-color); }
    .stat-item.info { border-left-color: var(--info-color); }

    .stat-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: #111827;
    }

    .tagihan-table {
        width: 100%;
        border-collapse: collapse;
    }

    .tagihan-table thead {
        background: linear-gradient(to right, #f9fafb, #f3f4f6);
    }

    .tagihan-table th {
        padding: 12px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        border-bottom: 2px solid #e5e7eb;
    }

    .tagihan-table td {
        padding: 12px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
    }

    .tagihan-table tbody tr:hover {
        background: #f9fafb;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-warning { background: #fef3c7; color: #92400e; }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-success { background: var(--success-color); color: white; }
    .btn-danger { background: var(--danger-color); color: white; }
    .btn-secondary { background: #6b7280; color: white; }

    .btn-outline {
        background: white;
        border: 2px solid #667eea;
        color: #667eea;
    }

    .btn-outline:hover {
        background: #667eea;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #e5e7eb;
        margin-bottom: 16px;
    }

    .empty-state p {
        color: #9ca3af;
        font-size: 14px;
    }

    code {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        padding: 4px 10px;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 13px;
        font-weight: 600;
        color: #667eea;
        border: 1px solid #e5e7eb;
    }

    .photo-container {
        margin-top: 16px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .photo-container img {
        width: 100%;
        height: auto;
        display: block;
    }

    .alert {
        padding: 16px 20px;
        margin-bottom: 24px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border-left: 4px solid var(--success-color);
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border-left: 4px solid var(--danger-color);
    }

    @media (max-width: 968px) {
        .content-grid { grid-template-columns: 1fr; }
        .header-content { flex-direction: column; }
        .action-buttons-header { width: 100%; }
        .info-row { grid-template-columns: 1fr; gap: 8px; }
    }
</style>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('petugas.dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('petugas.pelanggan.index') }}">Data Pelanggan</a>
    <span class="breadcrumb-separator">/</span>
    <span>Detail</span>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle" style="font-size: 20px;"></i>
        <strong>{{ session('success') }}</strong>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
        <strong>{{ session('error') }}</strong>
    </div>
@endif

<!-- Header Section -->
<div class="detail-header">
    <div class="header-content">
        <div class="pelanggan-info">
            <h1 class="pelanggan-name">
                <i class="fas fa-user-circle" style="color: #667eea;"></i>
                {{ $pelanggan->user->name }}
            </h1>
            <p class="pelanggan-id">ID Pelanggan: #{{ str_pad($pelanggan->id, 5, '0', STR_PAD_LEFT) }}</p>
            <div>
                @if($pelanggan->status == 'aktif')
                    <span class="status-badge-large aktif">
                        <i class="fas fa-check-circle"></i> Aktif
                    </span>
                @elseif($pelanggan->status == 'pending')
                    <span class="status-badge-large pending">
                        <i class="fas fa-clock"></i> Pending
                    </span>
                @else
                    <span class="status-badge-large nonaktif">
                        <i class="fas fa-times-circle"></i> Nonaktif
                    </span>
                @endif
            </div>
        </div>

        <div class="action-buttons-header">
            <a href="{{ route('petugas.pelanggan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('petugas.pelanggan.edit', $pelanggan->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('petugas.pelanggan.destroy', $pelanggan->id) }}"
                  method="POST"
                  style="display: inline;"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="content-grid">
    <!-- Left Column -->
    <div>
        <!-- Informasi Pribadi -->
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-id-card"></i> Informasi Pribadi
            </div>
            <div class="card-body">
                <div class="info-row">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value"><strong>{{ $pelanggan->user->name }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <i class="fas fa-envelope" style="color: #667eea;"></i>
                        {{ $pelanggan->user->email }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">WhatsApp</div>
                    <div class="info-value">
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pelanggan->no_wa) }}"
                           target="_blank"
                           class="btn btn-success"
                           style="padding: 6px 14px; font-size: 13px;">
                            <i class="fab fa-whatsapp"></i>
                            {{ $pelanggan->no_wa }}
                        </a>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat</div>
                    <div class="info-value">
                        {{ $pelanggan->alamat }}
                        @if($pelanggan->link_maps)
                            <br>
                            <a href="{{ $pelanggan->link_maps }}"
                               target="_blank"
                               class="btn btn-outline"
                               style="margin-top: 8px; padding: 6px 14px; font-size: 13px;">
                                <i class="fas fa-map-marker-alt"></i> Lihat di Google Maps
                            </a>
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Daftar</div>
                    <div class="info-value">
                        <i class="fas fa-calendar" style="color: #667eea;"></i>
                        {{ $pelanggan->created_at->format('d M Y, H:i') }}
                        <small style="color: #9ca3af;">({{ $pelanggan->created_at->diffForHumans() }})</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Foto Rumah -->
        @if($pelanggan->foto_rumah)
        <div class="info-card" style="margin-top: 24px;">
            <div class="card-header">
                <i class="fas fa-image"></i> Foto Rumah
            </div>
            <div class="card-body">
                <div class="photo-container">
                    <img src="{{ asset('storage/' . $pelanggan->foto_rumah) }}"
                         alt="Foto Rumah {{ $pelanggan->user->name }}">
                </div>
            </div>
        </div>
        @endif

        <!-- Paket & PPPoE -->
        <div class="info-card" style="margin-top: 24px;">
            <div class="card-header">
                <i class="fas fa-wifi"></i> Informasi Paket & Koneksi
            </div>
            <div class="card-body">
                <div class="info-row">
                    <div class="info-label">Paket WiFi</div>
                    <div class="info-value">
                        @if($pelanggan->paketWifi)
                            <strong style="color: #111827; font-size: 16px;">{{ $pelanggan->paketWifi->nama_paket }}</strong>
                            <br>
                            <span style="color: #10b981; font-weight: 700; font-size: 15px;">
                                Rp {{ number_format($pelanggan->paketWifi->harga, 0, ',', '.') }}/bulan
                            </span>
                            <br>
                            <small style="color: #6b7280;">
                                Kecepatan: {{ $pelanggan->paketWifi->kecepatan }}
                            </small>
                        @else
                            <span style="color: #9ca3af;">Belum ada paket</span>
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Username PPPoE</div>
                    <div class="info-value">
                        @if($pelanggan->pppoeAccount)
                            <code>{{ $pelanggan->pppoeAccount->username_pppoe }}</code>
                        @else
                            <span style="color: #9ca3af;">Belum dikonfigurasi</span>
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Password PPPoE</div>
                    <div class="info-value">
                        @if($pelanggan->pppoeAccount)
                            <code>{{ $pelanggan->pppoeAccount->password_pppoe }}</code>
                        @else
                            <span style="color: #9ca3af;">Belum dikonfigurasi</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Tagihan -->
        <div class="info-card" style="margin-top: 24px;">
            <div class="card-header">
                <i class="fas fa-file-invoice-dollar"></i> Riwayat Tagihan
            </div>
            <div class="card-body">
                @if($pelanggan->tagihans && $pelanggan->tagihans->count() > 0)
                    <div style="overflow-x: auto;">
                        <table class="tagihan-table">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Tanggal Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pelanggan->tagihans as $tagihan)
                                <tr>
                                    <td>
                                        <strong>{{ $tagihan->bulan_format ?? $tagihan->bulan . '/' . $tagihan->tahun }}</strong>
                                    </td>
                                    <td>
                                        <strong style="color: #111827;">
                                            Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                    <td>
                                        @if($tagihan->status == 'lunas')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Lunas
                                            </span>
                                        @elseif($tagihan->status == 'pending')
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times"></i> Nunggak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tagihan->tanggal_bayar)
                                            {{ \Carbon\Carbon::parse($tagihan->tanggal_bayar)->format('d M Y') }}
                                        @else
                                            <span style="color: #9ca3af;">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Belum ada riwayat tagihan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div>
        <!-- Quick Stats -->
        <div class="info-card">
            <div class="card-header">
                <i class="fas fa-chart-pie"></i> Statistik
            </div>
            <div class="card-body">
                <div class="quick-stats">
                    <div class="stat-item success">
                        <div class="stat-label">Total Tagihan Lunas</div>
                        <div class="stat-value">
                            {{ $pelanggan->tagihans->where('status', 'lunas')->count() }}
                        </div>
                    </div>
                    <div class="stat-item danger">
                        <div class="stat-label">Tagihan Nunggak</div>
                        <div class="stat-value">
                            {{ $pelanggan->tagihans->where('status', 'nunggak')->count() }}
                        </div>
                    </div>
                    <div class="stat-item info">
                        <div class="stat-label">Total Pembayaran</div>
                        <div class="stat-value" style="font-size: 18px;">
                            Rp {{ number_format($pelanggan->tagihans->where('status', 'lunas')->sum('jumlah'), 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="info-card" style="margin-top: 24px;">
            <div class="card-header">
                <i class="fas fa-bolt"></i> Quick Actions
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <form action="{{ route('petugas.pelanggan.updateStatus', $pelanggan->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="{{ $pelanggan->status == 'aktif' ? 'nonaktif' : 'aktif' }}">
                        <button type="submit" class="btn {{ $pelanggan->status == 'aktif' ? 'btn-danger' : 'btn-success' }}" style="width: 100%;">
                            <i class="fas fa-{{ $pelanggan->status == 'aktif' ? 'ban' : 'check-circle' }}"></i>
                            {{ $pelanggan->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }} Pelanggan
                        </button>
                    </form>

                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pelanggan->no_wa) }}"
                       target="_blank"
                       class="btn btn-success"
                       style="width: 100%;">
                        <i class="fab fa-whatsapp"></i> Hubungi via WhatsApp
                    </a>

                    @if($pelanggan->link_maps)
                    <a href="{{ $pelanggan->link_maps }}"
                       target="_blank"
                       class="btn btn-primary"
                       style="width: 100%;">
                        <i class="fas fa-map-marker-alt"></i> Lokasi di Google Maps
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="info-card" style="margin-top: 24px;">
            <div class="card-header">
                <i class="fas fa-clock"></i> Timeline
            </div>
            <div class="card-body">
                <div class="info-row">
                    <div class="info-label">Terdaftar</div>
                    <div class="info-value">
                        {{ $pelanggan->created_at->format('d M Y') }}
                        <br>
                        <small style="color: #9ca3af;">{{ $pelanggan->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Update Terakhir</div>
                    <div class="info-value">
                        {{ $pelanggan->updated_at->format('d M Y') }}
                        <br>
                        <small style="color: #9ca3af;">{{ $pelanggan->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection