@extends('layouts.petugas')

@section('title', 'Laporan Penarikan Harian')
@section('page-title', 'Laporan Penarikan Harian')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />

<style>
    .container-custom {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .header-title h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 5px 0;
    }
    
    .header-title p {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
    }

    .summary-box {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
    }

    .summary-box h3 {
        margin: 0 0 20px 0;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .summary-item {
        background: rgba(255,255,255,0.2);
        padding: 20px;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .summary-item h6 {
        margin: 0 0 8px 0;
        font-size: 12px;
        text-transform: uppercase;
        opacity: 0.9;
    }

    .summary-item h4 {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
    }

    .summary-item p {
        margin: 5px 0 0 0;
        font-size: 13px;
        opacity: 0.9;
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
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }
    
    .card-body {
        padding: 20px;
    }

    .date-picker {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        padding: 10px 15px;
        border-radius: 8px;
    }

    .date-picker input {
        border: none;
        outline: none;
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }

    .table-responsive {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    thead {
        background: #f9fafb;
    }
    
    th {
        padding: 12px;
        text-align: left;
        font-weight: 700;
        font-size: 12px;
        color: #374151;
        text-transform: uppercase;
        border-bottom: 2px solid #e5e7eb;
    }
    
    td {
        padding: 12px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
    }
    
    tbody tr:hover {
        background-color: #f9fafb;
    }

    .text-center {
        text-align: center;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
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

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5568d3;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .empty-state h4 {
        margin: 0 0 10px 0;
        font-size: 18px;
        color: #374151;
    }

    @media print {
        .no-print {
            display: none !important;
        }
        
        .summary-box {
            background: #10b981 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

@php
$namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
@endphp

<div class="container-custom">
    <!-- Header -->
    <div class="header-section no-print">
        <div class="header-title">
            <h2>📊 Laporan Penarikan Harian</h2>
            <p>Detail penarikan uang oleh petugas per hari</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <form method="GET" style="display: inline;">
                <div class="date-picker">
                    <i class="fas fa-calendar"></i>
                    <input type="date" 
                           name="tanggal" 
                           value="{{ $tanggal->format('Y-m-d') }}" 
                           onchange="this.form.submit()">
                </div>
            </form>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak
            </button>
            <a href="{{ route('petugas.pembayaran.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Summary Box -->
    <div class="summary-box">
        <h3>
            <i class="fas fa-wallet"></i>
            Ringkasan Penarikan - {{ $tanggal->format('d F Y') }}
        </h3>
        <div class="summary-grid">
            <div class="summary-item">
                <h6>💰 Total Penarikan</h6>
                <h4>Rp {{ number_format($totalPenarikan, 0, ',', '.') }}</h4>
                <p>{{ $jumlahTransaksi }} transaksi</p>
            </div>
            <div class="summary-item">
                <h6>💵 Tunai (Cash)</h6>
                <h4>Rp {{ number_format($totalTunai, 0, ',', '.') }}</h4>
                <p>{{ $jumlahTunai }} transaksi</p>
            </div>
            <div class="summary-item">
                <h6>🏦 Transfer Bank</h6>
                <h4>Rp {{ number_format($totalTransfer, 0, ',', '.') }}</h4>
                <p>{{ $jumlahTransfer }} transaksi</p>
            </div>
            <div class="summary-item">
                <h6>📱 QRIS</h6>
                <h4>Rp {{ number_format($totalQris, 0, ',', '.') }}</h4>
                <p>{{ $jumlahQris }} transaksi</p>
            </div>
        </div>
    </div>

    <!-- Detail Transaksi -->
    <div class="card">
        <div class="card-header">
            <h3>Detail Transaksi</h3>
            <span>Petugas: {{ auth()->user()->name }}</span>
        </div>
        
        <div class="card-body">
            @if($transaksiHariIni->count() > 0)
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Waktu</th>
                                <th>Pelanggan</th>
                                <th>Periode</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksiHariIni as $index => $transaksi)
                                @php
                                    $bulanInt = (int)$transaksi->bulan;
                                    $tahunInt = (int)$transaksi->tahun;
                                    $namaBulanText = $namaBulan[$bulanInt] ?? 'N/A';
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_bayar)->format('H:i') }}</td>
                                    <td>
                                        <strong>{{ $transaksi->pelanggan->user->name ?? 'N/A' }}</strong><br>
                                        <small style="color: #6b7280;">{{ $transaksi->pelanggan->alamat ?? '-' }}</small>
                                    </td>
                                    <td>{{ $namaBulanText }} {{ $tahunInt }}</td>
                                    <td>
                                        @if($transaksi->metode_pembayaran == 'tunai')
                                            <span class="badge badge-success">
                                                <i class="fas fa-money-bill-wave"></i> Tunai
                                            </span>
                                        @elseif($transaksi->metode_pembayaran == 'transfer')
                                            <span class="badge badge-info">
                                                <i class="fas fa-university"></i> Transfer
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-qrcode"></i> QRIS
                                            </span>
                                        @endif
                                    </td>
                                    <td><strong>Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</strong></td>
                                    <td>{{ $transaksi->keterangan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: #f9fafb; font-weight: 700;">
                                <td colspan="5" style="text-align: right; padding: 15px;">TOTAL:</td>
                                <td colspan="2" style="font-size: 16px; color: #10b981;">
                                    Rp {{ number_format($totalPenarikan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h4>Belum Ada Transaksi</h4>
                    <p>Belum ada pembayaran yang dikonfirmasi pada tanggal ini</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection