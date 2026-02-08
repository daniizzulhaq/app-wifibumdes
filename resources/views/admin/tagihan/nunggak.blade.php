@extends('layouts.admin')

@section('title', 'Tagihan Nunggak')
@section('page-title', 'Tagihan Nunggak')

@section('content')
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

    .header-actions {
        display: flex;
        gap: 10px;
    }
    
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid;
    }
    
    .stat-card.red { border-color: #ef4444; }
    .stat-card.orange { border-color: #f59e0b; }
    .stat-card.purple { border-color: #8b5cf6; }
    
    .stat-card h6 {
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        margin: 0 0 8px 0;
        text-transform: uppercase;
    }
    
    .stat-card h3 {
        font-size: 28px;
        font-weight: 800;
        margin: 0;
        color: #111827;
    }

    .stat-card p {
        margin: 5px 0 0 0;
        font-size: 13px;
        color: #6b7280;
    }
    
    /* Main Card */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .card-header {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 20px;
    }
    
    .card-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }
    
    .card-body {
        padding: 20px;
    }
    
    /* Buttons */
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
    
    .btn-primary {
        background: #667eea;
        color: white;
    }
    
    .btn-primary:hover {
        background: #5568d3;
        color: white;
    }
    
    .btn-info {
        background: #3b82f6;
        color: white;
    }

    .btn-info:hover {
        background: #2563eb;
        color: white;
    }
    
    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        color: white;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
        color: white;
    }
    
    .btn-sm {
        padding: 6px 12px;
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
        background: #fef2f2;
    }
    
    th {
        padding: 12px;
        text-align: left;
        font-weight: 700;
        font-size: 12px;
        color: #991b1b;
        text-transform: uppercase;
        border-bottom: 2px solid #fecaca;
    }
    
    td {
        padding: 12px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
    }
    
    tbody tr {
        background: #fffbeb;
    }

    tbody tr:hover {
        background-color: #fef3c7;
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
    
    /* Alert */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .alert-warning {
        background: #fef3c7;
        color: #92400e;
        border-left: 4px solid #f59e0b;
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
        opacity: 0.7;
    }
    
    .alert button:hover {
        opacity: 1;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal.show {
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
        color: #111827;
    }

    .modal-header .close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6b7280;
        padding: 0;
        width: 30px;
        height: 30px;
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
    }

    .form-control:focus {
        border-color: #667eea;
    }

    .text-danger {
        color: #ef4444;
    }

    .text-muted {
        color: #6b7280;
        font-size: 13px;
    }

    .overdue-badge {
        background: #7f1d1d;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        margin-left: 5px;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@php
$namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$totalNunggak = $tagihans->sum('jumlah');
$jumlahPelanggan = $tagihans->unique('pelanggan_id')->count();
@endphp

<div class="container-custom">
    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h2>⚠️ Tagihan Nunggak</h2>
            <p>Daftar tagihan yang belum dibayar oleh pelanggan</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.tagihan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Semua Tagihan
            </a>
        </div>
    </div>

    <!-- Alert Warning -->
    @if($tagihans->total() > 0)
        <div class="alert alert-warning">
            <span>
                <i class="fas fa-exclamation-triangle"></i> 
                Terdapat <strong>{{ $tagihans->total() }}</strong> tagihan nunggak dari <strong>{{ $jumlahPelanggan }}</strong> pelanggan dengan total tunggakan <strong>Rp {{ number_format($totalNunggak, 0, ',', '.') }}</strong>
            </span>
            <button onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card red">
            <h6>Total Tagihan Nunggak</h6>
            <h3>{{ $tagihans->total() ?? 0 }}</h3>
            <p>Tagihan yang belum dibayar</p>
        </div>
        <div class="stat-card orange">
            <h6>Total Tunggakan</h6>
            <h3>Rp {{ number_format($totalNunggak / 1000, 0) }}rb</h3>
            <p>Jumlah yang belum dibayar</p>
        </div>
        <div class="stat-card purple">
            <h6>Pelanggan Nunggak</h6>
            <h3>{{ $jumlahPelanggan }}</h3>
            <p>Pelanggan dengan tunggakan</p>
        </div>
    </div>

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

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <h3>Daftar Tagihan Nunggak</h3>
        </div>
        
        <div class="card-body">
            <!-- Table -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Periode</th>
                            <th>Jumlah</th>
                            <th>Jatuh Tempo</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 150px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tagihans as $index => $tagihan)
                            @php
                                $bulanInt = (int)$tagihan->bulan;
                                $tahunInt = (int)$tagihan->tahun;
                                $namaBulanText = isset($namaBulan[$bulanInt]) ? $namaBulan[$bulanInt] : 'N/A';
                                
                                // Cek apakah lewat jatuh tempo
                                $isOverdue = false;
                                if($tagihan->tanggal_jatuh_tempo) {
                                    $isOverdue = \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->isPast();
                                }
                            @endphp
                            <tr>
                                <td>{{ $tagihans->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $tagihan->pelanggan->user->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($tagihan->pelanggan->alamat ?? '-', 30) }}</small>
                                </td>
                                <td>
                                    @if($tagihan->pelanggan && $tagihan->pelanggan->paket)
                                        {{ $tagihan->pelanggan->paket->nama }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $namaBulanText }} {{ $tahunInt }}</td>
                                <td><strong style="color: #dc2626;">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($tagihan->tanggal_jatuh_tempo)
                                        {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                        @if($isOverdue)
                                            <span class="overdue-badge">LEWAT</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i> Nunggak
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.tagihan.show', $tagihan->id) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-success" 
                                            onclick="openKonfirmasiModal({{ $tagihan->id }}, '{{ addslashes($tagihan->pelanggan->user->name ?? 'N/A') }}', '{{ $namaBulanText }} {{ $tahunInt }}', {{ $tagihan->jumlah }})" 
                                            title="Konfirmasi Bayar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px; background: white;">
                                    <i class="fas fa-check-circle" style="font-size: 48px; color: #10b981;"></i>
                                    <p style="margin: 10px 0 0 0; color: #059669; font-weight: 600;">Tidak ada tagihan nunggak! 🎉</p>
                                    <small style="color: #6b7280;">Semua pelanggan sudah membayar tepat waktu</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tagihans->total() > 0)
                <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <p style="color: #6b7280; margin: 0;">
                        Menampilkan {{ $tagihans->firstItem() }} - {{ $tagihans->lastItem() }} dari {{ $tagihans->total() }}
                    </p>
                    {{ $tagihans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pembayaran -->
<div class="modal" id="konfirmasiModal">
    <div class="modal-dialog">
        <form method="POST" id="konfirmasiForm">
            @csrf
            <div class="modal-header">
                <h5>Konfirmasi Pembayaran</h5>
                <button type="button" class="close" onclick="closeModal('konfirmasiModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Pelanggan:</strong> <span id="konfNama"></span></p>
                <p><strong>Periode:</strong> <span id="konfPeriode"></span></p>
                <p><strong>Jumlah:</strong> Rp <span id="konfJumlah"></span></p>
                
                <hr style="margin: 15px 0; border: 0; border-top: 1px solid #e5e7eb;">
                
                <div class="form-group">
                    <label>Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="metode_bayar" class="form-control" required>
                        <option value="">Pilih Metode</option>
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('konfirmasiModal')">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Konfirmasi Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal Functions
function openModal(modalId) {
    document.getElementById(modalId).classList.add('show');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
}

function openKonfirmasiModal(id, nama, periode, jumlah) {
    document.getElementById('konfNama').textContent = nama;
    document.getElementById('konfPeriode').textContent = periode;
    document.getElementById('konfJumlah').textContent = new Intl.NumberFormat('id-ID').format(jumlah);
    document.getElementById('konfirmasiForm').action = `/admin/tagihan/${id}/konfirmasi`;
    openModal('konfirmasiModal');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('show');
    }
}

// Auto hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.display = 'none';
        });
    }, 5000);
});
</script>
@endsection