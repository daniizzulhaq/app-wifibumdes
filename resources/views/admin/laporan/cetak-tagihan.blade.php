<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tagihan - {{ $namaBulan[(int)$bulan] }} {{ $tahun }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            padding: 20px;
            font-size: 12px;
            color: #000;
            background: #fff;
        }

        /* Header/Kop Surat */
        .kop-surat {
            border-bottom: 4px double #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
            position: relative;
        }

        .kop-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Jika tidak ada logo, tampilkan placeholder */
        .logo-placeholder {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h1 {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 2px;
            color: #000;
            letter-spacing: 1px;
        }

        .kop-text h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #1a1a1a;
        }

        .kop-text p {
            font-size: 11px;
            margin: 2px 0;
            color: #333;
        }

        .kop-text .contact {
            margin-top: 5px;
            font-size: 10px;
            color: #555;
        }

        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            margin: 25px 0 20px 0;
        }

        .judul-laporan h3 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            text-decoration: underline;
        }

        .judul-laporan p {
            font-size: 13px;
            color: #333;
            margin-top: 5px;
        }

        /* Info Section */
        .info-section {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
            padding: 10px;
            background: #f9fafb;
            border: 1px solid #ddd;
        }

        .info-box {
            flex: 1;
        }

        .info-box h4 {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            border-bottom: 1px solid #999;
            padding-bottom: 3px;
        }

        .info-box p {
            font-size: 10px;
            margin: 3px 0;
            line-height: 1.5;
        }

        /* Summary Box */
        .summary-container {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .summary-box {
            flex: 1;
            border: 2px solid #000;
            padding: 12px;
            text-align: center;
        }

        .summary-box.total {
            background: #eff6ff;
            border-color: #3b82f6;
        }

        .summary-box.lunas {
            background: #f0fdf4;
            border-color: #10b981;
        }

        .summary-box.belum {
            background: #fef2f2;
            border-color: #ef4444;
        }

        .summary-box .label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .summary-box .amount {
            font-size: 16px;
            font-weight: bold;
        }

        .summary-box.total .amount {
            color: #2563eb;
        }

        .summary-box.lunas .amount {
            color: #059669;
        }

        .summary-box.belum .amount {
            color: #dc2626;
        }

        .summary-box .count {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 11px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        thead {
            background: #f3f4f6;
        }

        thead th {
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        tbody td {
            padding: 8px;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-lunas {
            background: #d1fae5;
            color: #065f46;
        }

        .status-menunggu {
            background: #fef3c7;
            color: #92400e;
        }

        .status-belum {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-nunggak {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Nominal Colors */
        .nominal-lunas {
            color: #059669;
            font-weight: bold;
        }

        .nominal-belum {
            color: #dc2626;
            font-weight: bold;
        }

        /* Footer Total */
        .footer-total {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .total-box {
            width: 450px;
            border: 2px solid #000;
            padding: 15px;
            background: #f9fafb;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 12px;
        }

        .total-row.main {
            border-top: 2px solid #000;
            margin-top: 8px;
            padding-top: 10px;
            font-weight: bold;
            font-size: 13px;
        }

        .total-row .label {
            font-weight: bold;
        }

        /* Keterangan */
        .keterangan-box {
            margin: 20px 0;
            padding: 12px;
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
        }

        .keterangan-box h4 {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .keterangan-box p {
            font-size: 10px;
            margin: 4px 0;
            line-height: 1.6;
        }

        /* Tanda Tangan */
        .ttd-container {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .ttd-box {
            width: 45%;
            text-align: center;
        }

        .ttd-box p {
            margin-bottom: 5px;
            font-size: 11px;
        }

        .ttd-box .jabatan {
            font-weight: bold;
            margin-bottom: 70px;
        }

        .ttd-box .nama {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 5px;
        }

        /* Catatan Kaki */
        .footer-note {
            margin-top: 30px;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .footer-note p {
            margin: 3px 0;
        }

        /* Print Settings */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .footer-note {
                display: none;
            }

            @page {
                margin: 15mm;
                size: A4 landscape;
            }
        }

        /* Empty State */
        .empty-row td {
            padding: 40px !important;
            text-align: center !important;
            color: #999 !important;
            font-style: italic;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <div class="kop-content">
            <!-- Logo (bisa diganti dengan logo asli) -->
            <div class="logo-placeholder">
                BN
            </div>
            
            <!-- Informasi Perusahaan -->
            <div class="kop-text">
                <h1>BUMDes.Net TINGGARJAYA</h1>
                <h2>Badan Usaha Milik Desa</h2>
                <p>Jl. Ahmad Yani No.278 Sidareja - Cilacap, Jawa Tengah</p>
                <p class="contact">WhatsApp: 08132632700 | Email: bumdesnet.tinggarjaya@gmail.com</p>
            </div>
            
            <!-- Spacer untuk simetris -->
            <div class="logo" style="opacity: 0;">
                <!-- Placeholder untuk menjaga simetris -->
            </div>
        </div>
    </div>

    <!-- Judul Laporan -->
    <div class="judul-laporan">
        <h3>Laporan Tagihan Pelanggan WiFi</h3>
        <p>Periode: <strong>{{ $namaBulan[(int)$bulan] }} {{ $tahun }}</strong></p>
    </div>

   <!-- Info Section -->
<div class="info-section">
    <div class="info-box">
        <h4>Informasi Periode</h4>
        <p><strong>Bulan:</strong> {{ $namaBulan[(int)$bulan] }}</p>
        <p><strong>Tahun:</strong> {{ $tahun }}</p>
        <p><strong>Filter Status:</strong> {{ $status ? ucfirst(str_replace('_', ' ', $status)) : 'Semua Status' }}</p>
    </div>
    <div class="info-box">
        <h4>Ringkasan Data</h4>
        <p><strong>Total Pelanggan:</strong> {{ $jumlahPelanggan }} Pelanggan</p>
        <p><strong>Sudah Lunas:</strong> {{ $jumlahLunas }} Pembayaran</p>
        <p><strong>Nunggak:</strong> {{ $jumlahNunggak }} Tagihan</p>
    </div>
</div>

<!-- Summary Boxes -->
<div class="summary-container">
    <div class="summary-box total">
        <div class="label">Total Tagihan</div>
        <div class="amount">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</div>
        <div class="count">{{ $jumlahPelanggan }} Pelanggan</div>
    </div>
    <div class="summary-box lunas">
        <div class="label">Sudah Lunas</div>
        <div class="amount">Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>
        <div class="count">{{ $jumlahLunas }} Pembayaran</div>
    </div>
    <div class="summary-box nunggak">
        <div class="label">Nunggak</div>
        <div class="amount">Rp {{ number_format($totalNunggak, 0, ',', '.') }}</div>
        <div class="count">{{ $jumlahNunggak }} Tagihan</div>
    </div>
</div>

    <!-- Tabel Transaksi -->
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">Kode</th>
                <th>Nama Pelanggan</th>
                <th style="width: 100px;">Paket</th>
                <th style="width: 80px;">Periode</th>
                <th style="width: 80px;">Status</th>
                <th style="width: 110px;">Nominal</th>
                <th style="width: 80px;">Tgl Bayar</th>
                <th style="width: 80px;">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tagihan as $key => $item)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center">{{ $item->pelanggan->kode_pelanggan ?? '-' }}</td>
                <td>{{ $item->pelanggan->user->name ?? '-' }}</td>
                <td>{{ $item->pelanggan->paket->nama_paket ?? '-' }}</td>
                <td class="text-center">{{ $namaBulan[(int)$item->bulan] ?? '-' }} {{ $item->tahun }}</td>
                <td class="text-center">
                    @if($item->status == 'lunas')
                        <span class="status-badge status-lunas">Lunas</span>
                    @elseif($item->status == 'menunggu_konfirmasi')
                        <span class="status-badge status-menunggu">Menunggu</span>
                    @elseif($item->status == 'nunggak')
                        <span class="status-badge status-nunggak">Nunggak</span>
                    @else
                        <span class="status-badge status-belum">Belum Lunas</span>
                    @endif
                </td>
                <td class="text-right">
                    <span class="nominal-{{ $item->status == 'lunas' ? 'lunas' : 'belum' }}">
                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                    </span>
                </td>
                <td class="text-center">
                    @if($item->tanggal_bayar)
                        {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center" style="font-size: 10px; text-transform: capitalize;">
                    {{ $item->metode_pembayaran ? str_replace('_', ' ', $item->metode_pembayaran) : '-' }}
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="9">
                    Tidak ada data tagihan untuk periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Total Footer -->
    <div class="footer-total">
        <div class="total-box">
            <div class="total-row">
                <span class="label">Total Tagihan:</span>
                <span style="color: #2563eb; font-weight: bold;">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="label">Sudah Lunas:</span>
                <span style="color: #059669; font-weight: bold;">Rp {{ number_format($totalLunas, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="label">Nunggak:</span>
                <span style="color: #dc2626; font-weight: bold;">Rp {{ number_format($totalNunggak, 0, ',', '.') }}</span>
            </div>
            <div class="total-row main">
                <span class="label">Persentase Lunas:</span>
                <span>{{ $totalTagihan > 0 ? number_format(($totalLunas / $totalTagihan) * 100, 1) : 0 }}%</span>
            </div>
        </div>
    </div>

    <!-- Keterangan Status -->
    <div class="keterangan-box">
        <h4>Keterangan Status:</h4>
        <p>• <strong>LUNAS</strong> = Pembayaran sudah dikonfirmasi oleh admin dan dana masuk ke kas</p>
        <p>• <strong>MENUNGGU</strong> = Pelanggan sudah upload bukti bayar, menunggu konfirmasi admin</p>
        <p>• <strong>NUNGGAK</strong> = Tagihan sudah lewat jatuh tempo dan belum dibayar</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="ttd-container">
        <div class="ttd-box">
            <p>Sidareja, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p class="jabatan">Mengetahui,<br>Direktur BUMDes</p>
            <p class="nama">( _________________________ )</p>
        </div>
        <div class="ttd-box">
            <p>&nbsp;</p>
            <p class="jabatan">Dibuat Oleh,<br>Admin / Bendahara</p>
            <p class="nama">( _________________________ )</p>
        </div>
    </div>

    <!-- Footer Note (tidak ikut tercetak) -->
    <div class="footer-note">
        <p><strong>Catatan:</strong></p>
        <p>- Laporan ini dicetak secara otomatis pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} WIB</p>
        <p>- Dokumen ini adalah salinan resmi dari sistem BUMDes.Net Tinggarjaya</p>
        <p>- Untuk informasi lebih lanjut, hubungi WhatsApp: 08132632700</p>
    </div>

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>
</html>