@extends('layouts.petugas')

@section('title', 'Proses Pembayaran')
@section('page-title', 'Proses Pembayaran')

@section('content')

<!-- Load FontAwesome -->
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

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid;
        transition: transform 0.2s;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .stat-card.red { border-color: #ef4444; }
    .stat-card.orange { border-color: #f59e0b; }
    .stat-card.blue { border-color: #3b82f6; }
    .stat-card.green { border-color: #10b981; }
    .stat-card.purple { border-color: #8b5cf6; }
    .stat-card.emerald { border-color: #34d399; }
    
    .stat-card h6 {
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        margin: 0 0 8px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-card h3 {
        font-size: 28px;
        font-weight: 800;
        margin: 0;
        color: #111827;
    }
    
    .stat-card p {
        color: #6b7280;
        font-size: 13px;
        margin: 8px 0 0 0;
    }

    .stat-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 48px;
        opacity: 0.15;
    }

    .stat-card.green .stat-icon { color: #10b981; }
    .stat-card.purple .stat-icon { color: #8b5cf6; }
    .stat-card.emerald .stat-icon { color: #34d399; }
    
    /* Alert Info Box */
    .info-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 24px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
    }

    .info-box i {
        font-size: 32px;
        opacity: 0.9;
    }

    .info-box-content h4 {
        margin: 0 0 5px 0;
        font-size: 18px;
        font-weight: 700;
    }

    .info-box-content p {
        margin: 0;
        font-size: 14px;
        opacity: 0.95;
    }
    
    /* Card */
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
    
    /* Filters */
    .filters {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr 0.5fr;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
    }

    .search-box {
        display: flex;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }
    
    .search-box:focus-within {
        border-color: #667eea;
    }
    
    .search-box i {
        padding: 12px;
        color: #9ca3af;
    }
    
    .search-box input {
        flex: 1;
        border: none;
        outline: none;
        padding: 12px 12px 12px 0;
        font-size: 14px;
    }
    
    .filter-select {
        width: 100%;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
    }
    
    .filter-select:focus {
        border-color: #667eea;
    }
    
    /* Buttons */
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
        position: relative;
        z-index: 1;
    }
    
    table .btn {
        z-index: 0 !important;
    }
    
    .btn-primary {
        background: #667eea;
        color: white;
    }
    
    .btn-primary:hover {
        background: #5568d3;
    }
    
    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
    }
    
    .btn-info {
        background: #3b82f6;
        color: white;
    }

    .btn-info:hover {
        background: #2563eb;
    }
    
    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }
    
    .btn i {
        font-size: 14px;
    }
    
    .btn-sm i {
        font-size: 12px;
    }
    
    /* Table */
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
    
    /* Badge */
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
    
    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-warning {
        background: #fed7aa;
        color: #92400e;
    }
    
    /* Alert */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    
    .alert button {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 99999 !important;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        overflow-y: auto;
        pointer-events: none;
    }

    .modal.show {
        display: flex !important;
        align-items: center;
        justify-content: center;
        pointer-events: auto !important;
    }

    .modal-dialog {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        margin: 20px;
        position: relative;
        z-index: 100000 !important;
        pointer-events: auto !important;
    }

    .modal-dialog * {
        pointer-events: auto !important;
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
        cursor: pointer !important;
        color: #6b7280;
        transition: color 0.2s;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-header .close:hover {
        color: #111827;
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
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        box-sizing: border-box;
        cursor: text !important;
    }

    .form-control:focus {
        border-color: #667eea;
    }

    .text-danger {
        color: #ef4444;
    }

    .modal input,
    .modal select,
    .modal textarea,
    .modal button {
        cursor: pointer !important;
        pointer-events: auto !important;
    }

    .modal input[type="text"],
    .modal input[type="number"],
    .modal input[type="date"],
    .modal textarea {
        cursor: text !important;
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
    <div class="header-section">
        <div class="header-title">
            <h2>💳 Proses Pembayaran</h2>
            <p>Kelola dan konfirmasi pembayaran pelanggan</p>
        </div>
        <div>
            <a href="{{ route('petugas.pembayaran.laporan-harian') }}" class="btn btn-warning">
                <i class="fas fa-file-invoice-dollar"></i> Laporan Harian
            </a>
        </div>
    </div>

    <!-- Info Box Penarikan Hari Ini -->
    <div class="info-box">
        <i class="fas fa-hand-holding-usd"></i>
        <div class="info-box-content">
            <h4>💰 Penarikan Tunai Hari Ini: Rp {{ number_format($penarikanTunaiHariIni, 0, ',', '.') }}</h4>
            <p>Total uang tunai yang perlu dibawa ke kantor dari {{ $jumlahTransaksiHariIni }} transaksi hari ini</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card red">
            <i class="fas fa-exclamation-triangle stat-icon"></i>
            <h6>Total Tunggakan</h6>
            <h3>Rp {{ number_format($totalNunggak, 0, ',', '.') }}</h3>
            <p>Perlu ditindaklanjuti</p>
        </div>
        <div class="stat-card orange">
            <i class="fas fa-file-invoice stat-icon"></i>
            <h6>Jumlah Tagihan</h6>
            <h3>{{ $jumlahTagihan }}</h3>
            <p>Tagihan belum lunas</p>
        </div>
        <div class="stat-card blue">
            <i class="fas fa-users stat-icon"></i>
            <h6>Pelanggan Nunggak</h6>
            <h3>{{ $pelangganNunggak }}</h3>
            <p>Pelanggan dengan tunggakan</p>
        </div>
        <div class="stat-card green">
            <i class="fas fa-money-bill-wave stat-icon"></i>
            <h6>Total Penarikan Hari Ini</h6>
            <h3>Rp {{ number_format($penarikanHariIni, 0, ',', '.') }}</h3>
            <p>Semua metode pembayaran</p>
        </div>
        <div class="stat-card emerald">
            <i class="fas fa-receipt stat-icon"></i>
            <h6>Transaksi Hari Ini</h6>
            <h3>{{ $jumlahTransaksiHariIni }}</h3>
            <p>Pembayaran dikonfirmasi</p>
        </div>
        <div class="stat-card purple">
            <i class="fas fa-wallet stat-icon"></i>
            <h6>Tunai Hari Ini</h6>
            <h3>Rp {{ number_format($penarikanTunaiHariIni, 0, ',', '.') }}</h3>
            <p>Uang cash yang ditarik</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card">
        <div class="card-header">
            <h3>Filter & Pencarian</h3>
        </div>
        
        <div class="card-body">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <span><i class="fas fa-check-circle"></i> {{ session('success') }}</span>
                    <button onclick="this.parentElement.style.display='none'">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <span><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</span>
                    <button onclick="this.parentElement.style.display='none'">&times;</button>
                </div>
            @endif

            <!-- Filters -->
            <form method="GET" action="{{ route('petugas.pembayaran.index') }}">
                <div class="filters">
                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="nunggak" {{ request('status') == 'nunggak' ? 'selected' : '' }}>Nunggak</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Bulan</label>
                        <select name="bulan" class="filter-select">
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                    {{ $namaBulan[$i] }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Tahun</label>
                        <select name="tahun" class="filter-select">
                            <option value="">Semua Tahun</option>
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Cari Pelanggan</label>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Nama pelanggan..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <h3>Daftar Tagihan Belum Lunas</h3>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Periode</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Jatuh Tempo</th>
                            <th style="width: 200px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tagihans as $index => $tagihan)
                            @php
                                $bulanInt = (int)$tagihan->bulan;
                                $tahunInt = (int)$tagihan->tahun;
                                $namaBulanText = $namaBulan[$bulanInt] ?? 'N/A';
                            @endphp
                            <tr>
                                <td>{{ $tagihans->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $tagihan->pelanggan->user->name ?? 'N/A' }}</strong><br>
                                    <small style="color: #6b7280;">{{ $tagihan->pelanggan->alamat ?? '-' }}</small>
                                </td>
                                <td>{{ $tagihan->pelanggan->paket->nama ?? '-' }}</td>
                                <td>{{ $namaBulanText }} {{ $tahunInt }}</td>
                                <td><strong>Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($tagihan->status == 'nunggak')
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Nunggak</span>
                                    @else
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Belum Bayar</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tagihan->tanggal_jatuh_tempo)
                                        {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                    @else
                                        <span style="color: #6b7280;">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('petugas.pembayaran.show', $tagihan->id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-success" 
                                            onclick="openBayarModal({{ $tagihan->id }}, '{{ addslashes($tagihan->pelanggan->user->name ?? 'N/A') }}', '{{ $namaBulanText }} {{ $tahunInt }}', {{ $tagihan->jumlah }})">
                                        <i class="fas fa-money-bill"></i> Bayar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-check-circle" style="font-size: 48px; color: #10b981;"></i>
                                    <p style="margin: 10px 0 0 0; color: #6b7280;">Semua tagihan sudah lunas! 🎉</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tagihans->total() > 0)
                <div style="margin-top: 20px;">
                    {{ $tagihans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pembayaran -->
<div class="modal" id="bayarModal">
    <div class="modal-dialog">
        <form method="POST" id="bayarForm">
            @csrf
            <div class="modal-header">
                <h5>Konfirmasi Pembayaran</h5>
                <button type="button" class="close" onclick="closeModal('bayarModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Pelanggan:</strong> <span id="bayarNama"></span></p>
                <p><strong>Periode:</strong> <span id="bayarPeriode"></span></p>
                <p><strong>Jumlah:</strong> Rp <span id="bayarJumlah"></span></p>
                
                <hr style="margin: 15px 0; border: 0; border-top: 1px solid #e5e7eb;">
                
                <div class="form-group">
                    <label>Jumlah Dibayar <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_bayar" id="inputJumlahBayar" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="metode_pembayaran" class="form-control" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="tunai">💵 Tunai (Cash)</option>
                        <option value="transfer">🏦 Transfer Bank</option>
                        <option value="qris">📱 QRIS</option>
                    </select>
                    <small style="color: #6b7280; font-size: 12px; margin-top: 5px; display: block;">
                        <i class="fas fa-info-circle"></i> Pilih "Tunai" jika menerima pembayaran cash langsung
                    </small>
                </div>
                
                <div class="form-group">
                    <label>Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('bayarModal')">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Konfirmasi Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openBayarModal(id, nama, periode, jumlah) {
    document.getElementById('bayarNama').textContent = nama;
    document.getElementById('bayarPeriode').textContent = periode;
    document.getElementById('bayarJumlah').textContent = new Intl.NumberFormat('id-ID').format(jumlah);
    document.getElementById('inputJumlahBayar').value = jumlah;
    document.getElementById('bayarForm').action = '/petugas/pembayaran/' + id + '/konfirmasi';
    document.getElementById('bayarModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
    document.body.style.overflow = 'auto';
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
}

// Auto hide alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.style.display = 'none';
    });
}, 5000);
</script>
@endsection