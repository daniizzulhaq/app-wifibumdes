{{-- resources/views/pelanggan/dashboard.blade.php --}}
@extends('layouts.pelanggan-app')

@section('title', 'Dashboard - ' . auth()->user()->name)

@section('content')
<div class="dashboard-wrapper">

    {{-- ===== HEADER GREETING ===== --}}
    <div class="greeting-section">
        <div class="greeting-text">
            <span class="greeting-hi">Halo,</span>
            <h1 class="greeting-name">{{ auth()->user()->name }} 👋</h1>
            <p class="greeting-sub">{{ $pelanggan->paket->nama_paket ?? 'Paket WiFi' }} &nbsp;·&nbsp; Status: 
                <span class="status-badge status-{{ $pelanggan->status }}">{{ ucfirst($pelanggan->status) }}</span>
            </p>
        </div>
        <div class="greeting-date">
            <span class="date-day">{{ now()->isoFormat('dddd') }}</span>
            <span class="date-full">{{ now()->isoFormat('D MMMM YYYY') }}</span>
        </div>
    </div>

    {{-- ===== STATS CARDS ===== --}}
    <div class="stats-grid">
        <div class="stat-card stat-red">
            <div class="stat-icon">⚠️</div>
            <div class="stat-info">
                <span class="stat-label">Belum Dibayar</span>
                <span class="stat-value">Rp {{ number_format($totalNunggak, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="stat-card stat-yellow">
            <div class="stat-icon">⏳</div>
            <div class="stat-info">
                <span class="stat-label">Menunggu Konfirmasi</span>
                <span class="stat-value">Rp {{ number_format($totalMenunggu, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="stat-card stat-blue">
            <div class="stat-icon">📶</div>
            <div class="stat-info">
                <span class="stat-label">Kecepatan Paket</span>
                <span class="stat-value">{{ $pelanggan->paket->kecepatan ?? '-' }} Mbps</span>
            </div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-icon">📅</div>
            <div class="stat-info">
                <span class="stat-label">Bergabung Sejak</span>
                <span class="stat-value">{{ $pelanggan->tgl_registrasi ? $pelanggan->tgl_registrasi->format('M Y') : '-' }}</span>
            </div>
        </div>
    </div>

    <div class="main-content-grid">

        {{-- ===== QR CODE CARD ===== --}}
        <div class="qr-section">
            <div class="qr-card">
                {{-- Decorative top bar --}}
                <div class="qr-card-header">
                    <div class="qr-header-left">
                        <div class="wifi-icon-wrap">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
                                <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
                                <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
                                <line x1="12" y1="20" x2="12.01" y2="20"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="qr-title">QR Pembayaran</h3>
                            <p class="qr-subtitle">Tunjukkan ke petugas saat tagihan</p>
                        </div>
                    </div>
                    <div class="qr-badge">AKTIF</div>
                </div>

                {{-- QR Code Image --}}
                <div class="qr-code-wrap">
                    <div class="qr-frame">
                        <div class="qr-corner qr-corner-tl"></div>
                        <div class="qr-corner qr-corner-tr"></div>
                        <div class="qr-corner qr-corner-bl"></div>
                        <div class="qr-corner qr-corner-br"></div>
                        <img 
                            src="{{ $pelanggan->getQrImageUrl(220) }}" 
                            alt="QR Code {{ auth()->user()->name }}"
                            class="qr-img"
                            id="qrImage"
                            loading="lazy"
                        />
                        <div class="qr-logo-overlay">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                                <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
                                <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
                                <line x1="12" y1="20" x2="12.01" y2="20"/>
                            </svg>
                        </div>
                    </div>
                    <div class="qr-pulse-ring"></div>
                </div>

                {{-- Customer Info under QR --}}
                <div class="qr-customer-info">
                    <p class="qr-customer-name">{{ auth()->user()->name }}</p>
                    <p class="qr-customer-id">ID: {{ str_pad($pelanggan->id, 6, '0', STR_PAD_LEFT) }}</p>
                    <p class="qr-customer-paket">{{ $pelanggan->paket->nama_paket ?? '-' }}</p>
                </div>

                {{-- Instructions --}}
                <div class="qr-instructions">
                    <div class="instruction-item">
                        <span class="instruction-num">1</span>
                        <span class="instruction-text">Petugas datang ke rumah Anda</span>
                    </div>
                    <div class="instruction-item">
                        <span class="instruction-num">2</span>
                        <span class="instruction-text">Tunjukkan QR Code ini kepada petugas</span>
                    </div>
                    <div class="instruction-item">
                        <span class="instruction-num">3</span>
                        <span class="instruction-text">Petugas scan → tagihan muncul otomatis</span>
                    </div>
                    <div class="instruction-item">
                        <span class="instruction-num">4</span>
                        <span class="instruction-text">Lakukan pembayaran sesuai tagihan</span>
                    </div>
                </div>

                {{-- Download / Print Button --}}
                <div class="qr-actions">
                    <button onclick="downloadQR()" class="btn-qr-action btn-download">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7,10 12,15 17,10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Download QR
                    </button>
                    <button onclick="printQR()" class="btn-qr-action btn-print">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="6 9 6 2 18 2 18 9"/>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                            <rect x="6" y="14" width="12" height="8"/>
                        </svg>
                        Cetak QR
                    </button>
                </div>

                <p class="qr-security-note">
                    🔒 QR bersifat unik dan hanya berlaku untuk akun Anda
                </p>
            </div>
        </div>

        {{-- ===== TAGIHAN TERBARU ===== --}}
        <div class="tagihan-section">
            <div class="section-header">
                <h2 class="section-title">Tagihan Terbaru</h2>
                <a href="{{ route('pelanggan.tagihan.index') }}" class="section-link">Lihat Semua →</a>
            </div>

            @if($tagihanTerbaru->count() > 0)
                <div class="tagihan-list">
                    @foreach($tagihanTerbaru as $tagihan)
                    <div class="tagihan-item tagihan-{{ $tagihan->status }}">
                        <div class="tagihan-left">
                            <div class="tagihan-icon">
                                @if($tagihan->status === 'lunas') ✅
                                @elseif($tagihan->status === 'menunggu_konfirmasi') ⏳
                                @else ⚠️
                                @endif
                            </div>
                            <div class="tagihan-meta">
                                <span class="tagihan-period">
                                    {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$tagihan->bulan] }} {{ $tagihan->tahun }}
                                </span>
                                <span class="tagihan-status-text">
                                    @if($tagihan->status === 'lunas') Lunas
                                    @elseif($tagihan->status === 'menunggu_konfirmasi') Menunggu Konfirmasi
                                    @elseif($tagihan->status === 'nunggak') Belum Dibayar
                                    @else {{ ucfirst($tagihan->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="tagihan-right">
                            <span class="tagihan-amount">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</span>
                            <a href="{{ route('pelanggan.tagihan.show', $tagihan) }}" class="tagihan-detail-btn">Detail</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <span class="empty-icon">📄</span>
                    <p>Belum ada tagihan</p>
                </div>
            @endif

            {{-- Info paket --}}
            <div class="paket-info-card">
                <div class="paket-header">
                    <span class="paket-label">Paket Aktif</span>
                    <span class="paket-name">{{ $pelanggan->paket->nama_paket ?? '-' }}</span>
                </div>
                <div class="paket-details">
                    <div class="paket-detail-item">
                        <span>Kecepatan</span>
                        <strong>{{ $pelanggan->paket->kecepatan ?? '-' }} Mbps</strong>
                    </div>
                    <div class="paket-detail-item">
                        <span>Tagihan/Bulan</span>
                        <strong>Rp {{ number_format($pelanggan->paket->harga ?? 0, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- PRINT AREA (hidden) --}}
<div id="printArea" style="display:none;">
    <div style="text-align:center; padding:30px; font-family:sans-serif;">
        <h2 style="margin:0 0 4px 0;">QR Pembayaran WiFi</h2>
        <p style="margin:0 0 16px 0; color:#666;">{{ auth()->user()->name }} — ID: {{ str_pad($pelanggan->id, 6, '0', STR_PAD_LEFT) }}</p>
        <img src="{{ $pelanggan->getQrImageUrl(280) }}" style="width:280px;height:280px;" />
        <p style="margin:16px 0 4px 0; color:#666;">{{ $pelanggan->paket->nama_paket ?? '' }}</p>
        <p style="margin:0; font-size:11px; color:#999;">Scan QR ini untuk melihat tagihan pembayaran</p>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ===== LAYOUT ===== */
.dashboard-wrapper {
    max-width: 1100px;
    margin: 0 auto;
    padding: 24px 20px 60px;
}

/* ===== GREETING ===== */
.greeting-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 12px;
}
.greeting-hi { font-size: 15px; color: #6b7280; font-weight: 500; display: block; }
.greeting-name { font-size: 28px; font-weight: 800; color: #111827; margin: 2px 0 4px; }
.greeting-sub { font-size: 14px; color: #6b7280; }
.status-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.status-aktif { background: #dcfce7; color: #15803d; }
.status-pending { background: #fef3c7; color: #b45309; }
.status-nonaktif { background: #fee2e2; color: #dc2626; }
.greeting-date { text-align: right; }
.date-day { display: block; font-size: 13px; color: #9ca3af; }
.date-full { display: block; font-size: 15px; font-weight: 600; color: #374151; }

/* ===== STATS GRID ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 14px;
    margin-bottom: 28px;
}
.stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    border: 1px solid #f3f4f6;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
}
.stat-red { border-left: 4px solid #ef4444; }
.stat-yellow { border-left: 4px solid #f59e0b; }
.stat-blue { border-left: 4px solid #3b82f6; }
.stat-green { border-left: 4px solid #10b981; }
.stat-icon { font-size: 26px; }
.stat-label { display: block; font-size: 12px; color: #6b7280; font-weight: 500; }
.stat-value { display: block; font-size: 16px; font-weight: 800; color: #111827; margin-top: 2px; }

/* ===== MAIN GRID ===== */
.main-content-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 24px;
    align-items: start;
}
@media (max-width: 768px) {
    .main-content-grid { grid-template-columns: 1fr; }
}

/* ===== QR CARD ===== */
.qr-section {}
.qr-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 24px rgba(0,0,0,.08);
}
.qr-card-header {
    background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
    padding: 18px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.qr-header-left { display: flex; align-items: center; gap: 12px; }
.wifi-icon-wrap {
    background: rgba(255,255,255,.2);
    border-radius: 10px;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
.qr-title { font-size: 16px; font-weight: 700; color: #fff; margin: 0; }
.qr-subtitle { font-size: 12px; color: rgba(255,255,255,.75); margin: 2px 0 0; }
.qr-badge {
    background: #10b981;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    letter-spacing: 0.5px;
}

.qr-code-wrap {
    display: flex;
    justify-content: center;
    padding: 28px 20px 16px;
    position: relative;
}
.qr-frame {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 20px rgba(37,99,235,.15);
    padding: 10px;
}
/* Corner decorations */
.qr-corner {
    position: absolute;
    width: 20px;
    height: 20px;
    z-index: 2;
}
.qr-corner-tl { top: 4px; left: 4px; border-top: 3px solid #2563eb; border-left: 3px solid #2563eb; border-radius: 4px 0 0 0; }
.qr-corner-tr { top: 4px; right: 4px; border-top: 3px solid #2563eb; border-right: 3px solid #2563eb; border-radius: 0 4px 0 0; }
.qr-corner-bl { bottom: 4px; left: 4px; border-bottom: 3px solid #2563eb; border-left: 3px solid #2563eb; border-radius: 0 0 0 4px; }
.qr-corner-br { bottom: 4px; right: 4px; border-bottom: 3px solid #2563eb; border-right: 3px solid #2563eb; border-radius: 0 0 4px 0; }

.qr-img { display: block; width: 220px; height: 220px; border-radius: 8px; }

.qr-logo-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #2563eb;
    border-radius: 8px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 0 4px white;
}

.qr-pulse-ring {
    display: none; /* tampilkan jika ingin animasi */
}

/* Customer info */
.qr-customer-info {
    text-align: center;
    padding: 0 20px 16px;
}
.qr-customer-name { font-size: 17px; font-weight: 700; color: #111827; margin: 0 0 2px; }
.qr-customer-id { font-size: 12px; color: #9ca3af; margin: 0 0 2px; font-family: monospace; }
.qr-customer-paket { font-size: 12px; color: #6b7280; margin: 0; }

/* Instructions */
.qr-instructions {
    margin: 0 20px 16px;
    background: #f8faff;
    border-radius: 12px;
    padding: 14px;
}
.instruction-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 8px;
}
.instruction-item:last-child { margin-bottom: 0; }
.instruction-num {
    background: #2563eb;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
}
.instruction-text { font-size: 13px; color: #374151; line-height: 1.4; }

/* QR Actions */
.qr-actions {
    display: flex;
    gap: 10px;
    padding: 0 20px 16px;
}
.btn-qr-action {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
}
.btn-download {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}
.btn-download:hover { background: #dbeafe; }
.btn-print {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
}
.btn-print:hover { background: #dcfce7; }

.qr-security-note {
    text-align: center;
    font-size: 11px;
    color: #9ca3af;
    padding: 0 20px 18px;
    margin: 0;
}

/* ===== TAGIHAN SECTION ===== */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}
.section-title { font-size: 18px; font-weight: 700; color: #111827; margin: 0; }
.section-link { font-size: 14px; color: #2563eb; text-decoration: none; font-weight: 500; }
.section-link:hover { text-decoration: underline; }

.tagihan-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
.tagihan-item {
    background: #fff;
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #f3f4f6;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    border-left: 4px solid #e5e7eb;
}
.tagihan-lunas { border-left-color: #10b981; }
.tagihan-nunggak { border-left-color: #ef4444; }
.tagihan-menunggu_konfirmasi { border-left-color: #f59e0b; }

.tagihan-left { display: flex; align-items: center; gap: 12px; }
.tagihan-icon { font-size: 22px; }
.tagihan-period { display: block; font-size: 14px; font-weight: 600; color: #111827; }
.tagihan-status-text { display: block; font-size: 12px; color: #6b7280; margin-top: 2px; }

.tagihan-right { display: flex; align-items: center; gap: 12px; }
.tagihan-amount { font-size: 15px; font-weight: 700; color: #111827; }
.tagihan-detail-btn {
    font-size: 12px;
    background: #f3f4f6;
    color: #374151;
    padding: 4px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
}
.tagihan-detail-btn:hover { background: #e5e7eb; }

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #9ca3af;
}
.empty-icon { font-size: 36px; display: block; margin-bottom: 8px; }

/* ===== PAKET INFO ===== */
.paket-info-card {
    background: linear-gradient(135deg, #1e3a5f, #2563eb);
    border-radius: 14px;
    padding: 18px 20px;
    color: white;
    margin-top: 8px;
}
.paket-header { margin-bottom: 12px; }
.paket-label { font-size: 12px; opacity: 0.8; display: block; }
.paket-name { font-size: 20px; font-weight: 800; display: block; margin-top: 2px; }
.paket-details { display: flex; gap: 24px; }
.paket-detail-item span { font-size: 12px; opacity: 0.8; display: block; }
.paket-detail-item strong { font-size: 15px; font-weight: 700; }
</style>
@endpush

@push('scripts')
<script>
function downloadQR() {
    const img = document.getElementById('qrImage');
    const canvas = document.createElement('canvas');
    canvas.width = 320;
    canvas.height = 380;
    const ctx = canvas.getContext('2d');

    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, 320, 380);

    const qrImg = new Image();
    qrImg.crossOrigin = 'anonymous';
    qrImg.onload = function() {
        ctx.drawImage(qrImg, 50, 20, 220, 220);

        ctx.fillStyle = '#111827';
        ctx.font = 'bold 16px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('{{ auth()->user()->name }}', 160, 268);

        ctx.fillStyle = '#6b7280';
        ctx.font = '13px sans-serif';
        ctx.fillText('{{ $pelanggan->paket->nama_paket ?? "" }}', 160, 290);

        ctx.fillStyle = '#9ca3af';
        ctx.font = '11px sans-serif';
        ctx.fillText('ID: {{ str_pad($pelanggan->id, 6, "0", STR_PAD_LEFT) }}', 160, 312);

        ctx.fillStyle = '#d1d5db';
        ctx.fillRect(40, 328, 240, 1);

        ctx.fillStyle = '#9ca3af';
        ctx.font = '11px sans-serif';
        ctx.fillText('Scan untuk pembayaran WiFi', 160, 350);

        const link = document.createElement('a');
        link.download = 'QR-Pembayaran-{{ auth()->user()->name }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    };
    qrImg.src = img.src;
}

function printQR() {
    const printContent = document.getElementById('printArea').innerHTML;
    const win = window.open('', '_blank', 'width=400,height=500');
    win.document.write(`
        <!DOCTYPE html>
        <html>
        <head><title>QR Pembayaran WiFi</title>
        <style>
            body { margin: 0; padding: 20px; font-family: sans-serif; }
            @media print { body { margin: 0; } }
        </style>
        </head>
        <body>${printContent}</body>
        </html>
    `);
    win.document.close();
    win.focus();
    setTimeout(() => { win.print(); win.close(); }, 300);
}
</script>
@endpush