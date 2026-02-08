<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Buku Kas - {{ $periodeTampil }}</title>
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

        .summary-box.pemasukan {
            background: #f0fdf4;
            border-color: #10b981;
        }

        .summary-box.pengeluaran {
            background: #fef2f2;
            border-color: #ef4444;
        }

        .summary-box.saldo {
            background: #eff6ff;
            border-color: #3b82f6;
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

        .summary-box.pemasukan .amount {
            color: #059669;
        }

        .summary-box.pengeluaran .amount {
            color: #dc2626;
        }

        .summary-box.saldo .amount {
            color: #2563eb;
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

        .nominal-pemasukan {
            color: #059669;
            font-weight: bold;
        }

        .nominal-pengeluaran {
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
            width: 400px;
            border: 2px solid #000;
            padding: 15px;
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
                size: A4;
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
        <h3>Laporan Buku Kas</h3>
        <p>Periode: <strong>{{ $periodeTampil }}</strong></p>
    </div>

    <!-- Summary Boxes -->
    <div class="summary-container">
        <div class="summary-box pemasukan">
            <div class="label">Total Pemasukan</div>
            <div class="amount">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
        </div>
        <div class="summary-box pengeluaran">
            <div class="label">Total Pengeluaran</div>
            <div class="amount">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
        <div class="summary-box saldo">
            <div class="label">Saldo Akhir</div>
            <div class="amount">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">Tanggal</th>
                <th style="width: 80px;">Jenis</th>
                <th style="width: 100px;">Kategori</th>
                <th style="width: 120px;">Nominal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bukuKas as $key => $item)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td class="text-center">
                    @if ($item->jenis == 'pemasukan')
                        <strong>Pemasukan</strong>
                    @else
                        <strong>Pengeluaran</strong>
                    @endif
                </td>
                <td>{{ str_replace('_', ' ', ucwords($item->kategori)) }}</td>
                <td class="text-right">
                    <span class="nominal-{{ $item->jenis }}">
                        @if ($item->jenis == 'pengeluaran')
                            (Rp {{ number_format($item->nominal, 0, ',', '.') }})
                        @else
                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                        @endif
                    </span>
                </td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="6">
                    Tidak ada transaksi pada periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Total Footer -->
    <div class="footer-total">
        <div class="total-box">
            <div class="total-row">
                <span class="label">Total Pemasukan:</span>
                <span class="nominal-pemasukan">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="label">Total Pengeluaran:</span>
                <span class="nominal-pengeluaran">(Rp {{ number_format($totalPengeluaran, 0, ',', '.') }})</span>
            </div>
            <div class="total-row main">
                <span class="label">Saldo Akhir:</span>
                <span>Rp {{ number_format($saldo, 0, ',', '.') }}</span>
            </div>
        </div>
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
            <p class="jabatan">Dibuat Oleh,<br>Bendahara</p>
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