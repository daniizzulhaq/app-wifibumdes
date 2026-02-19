{{-- resources/views/petugas/pembayaran/pelanggan.blade.php --}}
{{-- Tampil setelah petugas scan QR pelanggan --}}
@extends('layouts.petugas')

@section('title', 'Tagihan - ' . $pelanggan->user->name)

@section('content')
<div class="scan-result-wrapper">

    {{-- ===== BANNER QR SCAN SUCCESS ===== --}}
    @if(session('info'))
    <div class="scan-success-banner">
        <span class="scan-icon">📱</span>
        <span>{{ session('info') }}</span>
    </div>
    @endif

    {{-- ===== CUSTOMER PROFILE HEADER ===== --}}
    <div class="customer-header-card">
        <div class="customer-avatar">
            {{ strtoupper(substr($pelanggan->user->name, 0, 2)) }}
        </div>
        <div class="customer-details">
            <h1 class="customer-name">{{ $pelanggan->user->name }}</h1>
            <p class="customer-meta">
                <span>📞 {{ $pelanggan->no_wa }}</span>
                <span class="separator">·</span>
                <span>📶 {{ $pelanggan->paket->nama_paket ?? '-' }}</span>
                <span class="separator">·</span>
                <span>ID: {{ str_pad($pelanggan->id, 6, '0', STR_PAD_LEFT) }}</span>
            </p>
            <p class="customer-address">📍 {{ $pelanggan->alamat }}</p>
            @if($pelanggan->link_maps)
            <a href="{{ $pelanggan->link_maps }}" target="_blank" class="maps-link">
                🗺️ Buka Google Maps
            </a>
            @endif
        </div>
        <div class="customer-status-wrap">
            <span class="customer-status status-{{ $pelanggan->status }}">
                {{ ucfirst($pelanggan->status) }}
            </span>
        </div>
    </div>

    {{-- ===== TAGIHAN NUNGGAK (PRIORITAS UTAMA) ===== --}}
    @if($tagihanNunggak->count() > 0)
    <div class="nunggak-section">
        <div class="nunggak-header">
            <div>
                <h2 class="nunggak-title">⚠️ Tagihan Belum Dibayar</h2>
                <p class="nunggak-subtitle">{{ $tagihanNunggak->count() }} tagihan · Total: 
                    <strong>Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong>
                </p>
            </div>
        </div>

        <div class="nunggak-list">
            @foreach($tagihanNunggak as $tagihan)
            <div class="nunggak-item {{ $tagihan->status === 'menunggu_konfirmasi' ? 'status-menunggu' : '' }}">
                <div class="nunggak-item-left">
                    <div class="nunggak-period">
                        <span class="period-month">
                            {{ ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'][$tagihan->bulan] }}
                        </span>
                        <span class="period-year">{{ $tagihan->tahun }}</span>
                    </div>
                    <div class="nunggak-info">
                        <span class="nunggak-amount">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</span>
                        <span class="nunggak-jatuh-tempo">
                            Jatuh tempo: {{ $tagihan->tanggal_jatuh_tempo ? $tagihan->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}
                        </span>
                        @if($tagihan->status === 'menunggu_konfirmasi')
                        <span class="menunggu-badge">⏳ Menunggu Konfirmasi</span>
                        @endif
                    </div>
                </div>
                
                <div class="nunggak-item-right">
                    @if($tagihan->status !== 'menunggu_konfirmasi')
                    {{-- Tombol bayar cepat --}}
                    <button onclick="openPayModal({{ $tagihan->id }}, '{{ $tagihan->bulan }}/{{ $tagihan->tahun }}', {{ $tagihan->jumlah }})" 
                            class="btn-bayar">
                        💳 Bayar
                    </button>
                    @else
                    {{-- Tombol konfirmasi --}}
                    <button onclick="openKonfirmasiModal({{ $tagihan->id }}, '{{ $tagihan->bulan }}/{{ $tagihan->tahun }}', {{ $tagihan->jumlah }})"
                            class="btn-konfirmasi">
                        ✅ Konfirmasi
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Bayar Semua Button --}}
        @if($tagihanNunggak->where('status', '!=', 'menunggu_konfirmasi')->count() > 1)
        <button onclick="openBayarSemuaModal()" class="btn-bayar-semua">
            💰 Bayar Semua (Rp {{ number_format($totalTagihan, 0, ',', '.') }})
        </button>
        @endif
    </div>
    @else
    <div class="all-paid-card">
        <span class="all-paid-icon">🎉</span>
        <h3>Semua Tagihan Lunas!</h3>
        <p>Pelanggan ini tidak memiliki tunggakan.</p>
    </div>
    @endif

    {{-- ===== RIWAYAT PEMBAYARAN ===== --}}
    <div class="history-section">
        <h2 class="section-title">Riwayat Pembayaran</h2>
        @if($tagihans->count() > 0)
        <div class="history-table-wrap">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Tgl Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tagihans as $t)
                    <tr>
                        <td>{{ ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'][$t->bulan] }} {{ $t->tahun }}</td>
                        <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td>{{ $t->metode_pembayaran ? ucfirst($t->metode_pembayaran) : '-' }}</td>
                        <td>{{ $t->tanggal_bayar ? $t->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="status-pill status-{{ $t->status }}">
                                {{ $t->status === 'lunas' ? 'Lunas' : ($t->status === 'nunggak' ? 'Nunggak' : ucfirst(str_replace('_', ' ', $t->status))) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $tagihans->links() }}
        @else
        <p style="color:#9ca3af;padding:20px 0;">Belum ada riwayat pembayaran.</p>
        @endif
    </div>
</div>

{{-- ===== MODAL BAYAR SINGLE ===== --}}
<div id="payModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3 class="modal-title">💳 Konfirmasi Pembayaran</h3>
            <button onclick="closePayModal()" class="modal-close">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-info-row">
                <span>Pelanggan</span>
                <strong>{{ $pelanggan->user->name }}</strong>
            </div>
            <div class="modal-info-row">
                <span>Periode</span>
                <strong id="modalPeriode">-</strong>
            </div>
            <div class="modal-info-row highlight">
                <span>Jumlah</span>
                <strong id="modalJumlah" class="modal-amount">-</strong>
            </div>
            
            <form id="payForm" method="POST" action="">
                @csrf
                <div class="form-group">
                    <label class="form-label">Metode Pembayaran</label>
                    <div class="method-options">
                        <label class="method-option">
                            <input type="radio" name="metode_pembayaran" value="tunai" checked>
                            <span class="method-card">💵 Tunai</span>
                        </label>
                        <label class="method-option">
                            <input type="radio" name="metode_pembayaran" value="transfer">
                            <span class="method-card">🏦 Transfer</span>
                        </label>
                        <label class="method-option">
                            <input type="radio" name="metode_pembayaran" value="qris">
                            <span class="method-card">📱 QRIS</span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Bayar</label>
                    <input type="number" name="jumlah_bayar" id="modalJumlahBayar" 
                           class="form-input" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan (opsional)</label>
                    <input type="text" name="keterangan" class="form-input" 
                           placeholder="Catatan pembayaran...">
                </div>
                <input type="hidden" name="tanggal_bayar" value="{{ now()->format('Y-m-d') }}">
                <button type="submit" class="btn-submit">✅ Konfirmasi Pembayaran</button>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL BAYAR SEMUA ===== --}}
<div id="bayarSemuaModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h3 class="modal-title">💰 Bayar Semua Tagihan</h3>
            <button onclick="closeBayarSemuaModal()" class="modal-close">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-info-row">
                <span>Pelanggan</span>
                <strong>{{ $pelanggan->user->name }}</strong>
            </div>
            <div class="modal-info-row highlight">
                <span>Total Semua</span>
                <strong class="modal-amount">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong>
            </div>
            
            <form method="POST" action="{{ route('petugas.pembayaran.batch-konfirmasi') }}">
                @csrf
                {{-- Hidden IDs semua tagihan nunggak --}}
                @foreach($tagihanNunggak->where('status', '!=', 'menunggu_konfirmasi') as $t)
                <input type="hidden" name="tagihan_ids[]" value="{{ $t->id }}">
                @endforeach
                <input type="hidden" name="pelanggan_redirect" value="{{ $pelanggan->id }}">
                
                <div class="form-group">
                    <label class="form-label">Metode Pembayaran</label>
                    <div class="method-options">
                        <label class="method-option">
                            <input type="radio" name="metode_pembayaran" value="tunai" checked>
                            <span class="method-card">💵 Tunai</span>
                        </label>
                        <label class="method-option">
                            <input type="radio" name="metode_pembayaran" value="transfer">
                            <span class="method-card">🏦 Transfer</span>
                        </label>
                        <label class="method-option">
                            <input type="radio" name="metode_pembayaran" value="qris">
                            <span class="method-card">📱 QRIS</span>
                        </label>
                    </div>
                </div>
                <input type="hidden" name="tanggal_bayar" value="{{ now()->format('Y-m-d') }}">
                <button type="submit" class="btn-submit">✅ Konfirmasi Semua Pembayaran</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.scan-result-wrapper {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px 16px 60px;
}

/* Banner */
.scan-success-banner {
    background: linear-gradient(135deg, #059669, #10b981);
    color: white;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
    font-size: 14px;
    box-shadow: 0 4px 14px rgba(16,185,129,.3);
}
.scan-icon { font-size: 20px; }

/* Customer Header */
.customer-header-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.customer-avatar {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    color: white;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 800;
    flex-shrink: 0;
}
.customer-name { font-size: 20px; font-weight: 800; color: #111827; margin: 0 0 4px; }
.customer-meta { font-size: 13px; color: #6b7280; margin: 0 0 4px; }
.customer-meta .separator { margin: 0 6px; }
.customer-address { font-size: 13px; color: #6b7280; margin: 0 0 4px; }
.maps-link { font-size: 12px; color: #2563eb; text-decoration: none; }
.customer-status-wrap { margin-left: auto; flex-shrink: 0; }
.customer-status {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}
.status-aktif { background: #dcfce7; color: #15803d; }
.status-pending { background: #fef3c7; color: #b45309; }
.status-nonaktif { background: #fee2e2; color: #dc2626; }

/* Nunggak Section */
.nunggak-section {
    background: #fff;
    border-radius: 16px;
    border: 2px solid #fca5a5;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 2px 16px rgba(239,68,68,.1);
}
.nunggak-header {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    padding: 16px 20px;
    color: white;
}
.nunggak-title { font-size: 16px; font-weight: 700; margin: 0 0 2px; }
.nunggak-subtitle { font-size: 13px; margin: 0; opacity: 0.9; }

.nunggak-list { padding: 12px; display: flex; flex-direction: column; gap: 8px; }
.nunggak-item {
    background: #fff9f9;
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #fecaca;
}
.nunggak-item.status-menunggu {
    background: #fffbeb;
    border-color: #fde68a;
}
.nunggak-item-left { display: flex; align-items: center; gap: 14px; }
.nunggak-period {
    background: #fee2e2;
    border-radius: 8px;
    padding: 8px 12px;
    text-align: center;
    min-width: 52px;
}
.period-month { display: block; font-size: 13px; font-weight: 700; color: #dc2626; }
.period-year { display: block; font-size: 11px; color: #6b7280; }
.nunggak-amount { font-size: 16px; font-weight: 800; color: #111827; display: block; }
.nunggak-jatuh-tempo { font-size: 12px; color: #6b7280; display: block; margin-top: 2px; }
.menunggu-badge {
    display: inline-block;
    background: #fef3c7;
    color: #b45309;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 4px;
    margin-top: 4px;
}

.btn-bayar {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
}
.btn-bayar:hover { background: #1d4ed8; }
.btn-konfirmasi {
    background: #059669;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}
.btn-konfirmasi:hover { background: #047857; }

.btn-bayar-semua {
    width: calc(100% - 24px);
    margin: 0 12px 16px;
    background: linear-gradient(135deg, #1e3a5f, #2563eb);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    display: block;
}
.btn-bayar-semua:hover { opacity: 0.9; }

/* All paid */
.all-paid-card {
    background: #f0fdf4;
    border: 2px solid #86efac;
    border-radius: 16px;
    text-align: center;
    padding: 40px 20px;
    margin-bottom: 24px;
}
.all-paid-icon { font-size: 40px; display: block; margin-bottom: 12px; }
.all-paid-card h3 { color: #15803d; font-size: 18px; margin: 0 0 4px; }
.all-paid-card p { color: #6b7280; margin: 0; font-size: 14px; }

/* History */
.history-section { background: #fff; border-radius: 16px; padding: 20px; border: 1px solid #e5e7eb; }
.section-title { font-size: 16px; font-weight: 700; color: #111827; margin: 0 0 16px; }
.history-table-wrap { overflow-x: auto; }
.history-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.history-table th {
    background: #f9fafb;
    padding: 10px 14px;
    text-align: left;
    color: #6b7280;
    font-weight: 600;
    font-size: 12px;
    border-bottom: 1px solid #e5e7eb;
}
.history-table td {
    padding: 12px 14px;
    border-bottom: 1px solid #f3f4f6;
    color: #374151;
}
.status-pill {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.status-lunas { background: #dcfce7; color: #15803d; }
.status-nunggak { background: #fee2e2; color: #dc2626; }
.status-menunggu_konfirmasi { background: #fef3c7; color: #b45309; }

/* Modal */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    backdrop-filter: blur(4px);
}
.modal-box {
    background: #fff;
    border-radius: 20px;
    width: 100%;
    max-width: 460px;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    overflow: hidden;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 20px;
    border-bottom: 1px solid #f3f4f6;
}
.modal-title { font-size: 17px; font-weight: 700; color: #111827; margin: 0; }
.modal-close {
    background: #f3f4f6;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    color: #6b7280;
}
.modal-body { padding: 20px; }
.modal-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f9fafb;
    font-size: 14px;
    color: #6b7280;
}
.modal-info-row strong { color: #111827; }
.modal-info-row.highlight { background: #eff6ff; margin: 8px -4px; padding: 10px 4px; border-radius: 8px; border: none; }
.modal-amount { font-size: 18px; color: #2563eb !important; }

.form-group { margin-top: 16px; }
.form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
.form-input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    font-size: 14px;
    box-sizing: border-box;
    outline: none;
}
.form-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.method-options { display: flex; gap: 8px; }
.method-option { flex: 1; cursor: pointer; }
.method-option input { display: none; }
.method-card {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px 8px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    white-space: nowrap;
    transition: all 0.15s;
}
.method-option input:checked + .method-card {
    border-color: #2563eb;
    background: #eff6ff;
    color: #2563eb;
}
.btn-submit {
    width: 100%;
    margin-top: 20px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
}
.btn-submit:hover { opacity: 0.9; }
</style>
@endpush

@push('scripts')
<script>
function openPayModal(id, periode, jumlah) {
    document.getElementById('modalPeriode').textContent = periode;
    document.getElementById('modalJumlah').textContent = 'Rp ' + jumlah.toLocaleString('id-ID');
    document.getElementById('modalJumlahBayar').value = jumlah;
    document.getElementById('payForm').action = '/petugas/pembayaran/' + id + '/konfirmasi';
    document.getElementById('payModal').style.display = 'flex';
}

function closePayModal() {
    document.getElementById('payModal').style.display = 'none';
}

function openKonfirmasiModal(id, periode, jumlah) {
    // Untuk menunggu_konfirmasi, langsung open modal bayar
    openPayModal(id, periode, jumlah);
}

function openBayarSemuaModal() {
    document.getElementById('bayarSemuaModal').style.display = 'flex';
}

function closeBayarSemuaModal() {
    document.getElementById('bayarSemuaModal').style.display = 'none';
}

// Tutup modal klik luar
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
});
</script>
@endpush