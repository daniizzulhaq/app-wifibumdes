<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($tagihan->id, 6, '0', STR_PAD_LEFT) }} - {{ $namaBulan[(int)$tagihan->bulan] }} {{ $tagihan->tahun }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background: #f8f9fa;
            color: #333;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        /* Header */
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .company-info {
            position: relative;
            z-index: 1;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .company-tagline {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .company-details {
            font-size: 13px;
            line-height: 1.8;
            opacity: 0.95;
        }

        .invoice-title {
            position: absolute;
            top: 40px;
            right: 40px;
            text-align: right;
        }

        .invoice-title h1 {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 14px;
            opacity: 0.9;
            font-family: 'Courier New', monospace;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        /* Body */
        .invoice-body {
            padding: 40px;
        }

        /* Info Section */
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .info-box h3 {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .info-box p {
            font-size: 14px;
            line-height: 1.8;
            color: #1f2937;
        }

        .info-box .highlight {
            font-weight: 600;
            color: #667eea;
        }

        /* Table */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .invoice-table thead {
            background: #f3f4f6;
        }

        .invoice-table th {
            padding: 15px;
            text-align: left;
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }

        .invoice-table td {
            padding: 20px 15px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #1f2937;
        }

        .item-description {
            font-weight: 500;
        }

        .item-details {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .text-right {
            text-align: right;
        }

        /* Summary */
        .invoice-summary {
            margin-left: auto;
            width: 350px;
            background: #f9fafb;
            padding: 25px;
            border-radius: 12px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }

        .summary-row.total {
            border-top: 2px solid #667eea;
            margin-top: 15px;
            padding-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
        }

        /* Payment Info */
        .payment-info {
            background: #eff6ff;
            border: 2px dashed #3b82f6;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
        }

        .payment-info h3 {
            color: #1e40af;
            font-size: 14px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .payment-item {
            font-size: 13px;
        }

        .payment-label {
            color: #6b7280;
            margin-bottom: 4px;
        }

        .payment-value {
            font-weight: 600;
            color: #1f2937;
        }

        /* Footer */
        .invoice-footer {
            background: #f9fafb;
            padding: 30px 40px;
            border-top: 1px solid #e5e7eb;
        }

        .thank-you {
            text-align: center;
            margin-bottom: 20px;
        }

        .thank-you h3 {
            font-size: 18px;
            color: #667eea;
            margin-bottom: 8px;
        }

        .thank-you p {
            font-size: 13px;
            color: #6b7280;
        }

        .footer-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }

        .footer-info i {
            color: #667eea;
            margin-right: 5px;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(16, 185, 129, 0.05);
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
            }

            .no-print {
                display: none;
            }

            .watermark {
                display: none;
            }

            @page {
                margin: 0;
                size: A4;
            }
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .print-button i {
            margin-right: 8px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- Print Button -->
    <button onclick="window.print()" class="print-button no-print">
        <i class="fas fa-print"></i>
        Cetak Invoice
    </button>

    <!-- Watermark -->
    <div class="watermark">LUNAS</div>

    <div class="invoice-container">
        
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-name">BUMDes.Net TINGGARJAYA</div>
                <div class="company-tagline">Layanan Internet Cepat & Terpercaya</div>
                <div class="company-details">
                    <i class="fas fa-map-marker-alt"></i> Jl. Ahmad Yani No.278 Sidareja - Cilacap, Jawa Tengah<br>
                    <i class="fas fa-phone"></i> WhatsApp: 08132632700<br>
                    <i class="fas fa-envelope"></i> bumdesnet.tinggarjaya@gmail.com
                </div>
            </div>
            
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <div class="invoice-number">#{{ str_pad($tagihan->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="status-badge">
                    <i class="fas fa-check-circle"></i> LUNAS
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            
            <!-- Info Section -->
            <div class="info-section">
                <div class="info-box">
                    <h3>Tagihan Kepada</h3>
                    <p>
                        <strong class="highlight">{{ $tagihan->pelanggan->user->name ?? '-' }}</strong><br>
                        ID Pelanggan: <strong>{{ $tagihan->pelanggan->kode_pelanggan ?? '-' }}</strong><br>
                        {{ $tagihan->pelanggan->alamat ?? '-' }}<br>
                        <i class="fas fa-phone"></i> {{ $tagihan->pelanggan->user->phone ?? $tagihan->pelanggan->no_telepon ?? '-' }}
                    </p>
                </div>
                
                <div class="info-box" style="text-align: right;">
                    <h3>Detail Invoice</h3>
                    <p>
                        Tanggal Invoice: <strong>{{ $tagihan->created_at->format('d M Y') }}</strong><br>
                        Periode: <strong class="highlight">{{ $namaBulan[(int)$tagihan->bulan] }} {{ $tagihan->tahun }}</strong><br>
                        Jatuh Tempo: <strong>{{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d M Y') }}</strong><br>
                        Tanggal Bayar: <strong style="color: #10b981;">{{ \Carbon\Carbon::parse($tagihan->tanggal_bayar)->format('d M Y') }}</strong>
                    </p>
                </div>
            </div>

            <!-- Table -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Deskripsi</th>
                        <th style="width: 20%;">Periode</th>
                        <th style="width: 15%;">Jumlah</th>
                        <th style="width: 15%;" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="item-description">Paket Internet {{ $tagihan->pelanggan->paket->nama_paket ?? '-' }}</div>
                            <div class="item-details">
                                <i class="fas fa-wifi"></i> Kecepatan: {{ $tagihan->pelanggan->paket->kecepatan ?? '-' }} Mbps
                                @if($tagihan->pelanggan->paket->deskripsi ?? null)
                                    | {{ $tagihan->pelanggan->paket->deskripsi }}
                                @endif
                            </div>
                        </td>
                        <td>{{ $namaBulan[(int)$tagihan->bulan] }} {{ $tagihan->tahun }}</td>
                        <td>1</td>
                        <td class="text-right"><strong>Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <!-- Summary -->
            <div class="invoice-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Pajak (0%):</span>
                    <span>Rp 0</span>
                </div>
                <div class="summary-row">
                    <span>Diskon:</span>
                    <span>Rp 0</span>
                </div>
                <div class="summary-row total">
                    <span>TOTAL:</span>
                    <span>Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="payment-info">
                <h3><i class="fas fa-check-circle"></i> Informasi Pembayaran</h3>
                <div class="payment-grid">
                    <div class="payment-item">
                        <div class="payment-label">Metode Pembayaran:</div>
                        <div class="payment-value">{{ ucfirst(str_replace('_', ' ', $tagihan->metode_pembayaran ?? 'Transfer Bank')) }}</div>
                    </div>
                    <div class="payment-item">
                        <div class="payment-label">Tanggal Pembayaran:</div>
                        <div class="payment-value">{{ \Carbon\Carbon::parse($tagihan->tanggal_bayar)->format('d M Y, H:i') }}</div>
                    </div>
                    <div class="payment-item">
                        <div class="payment-label">Status:</div>
                        <div class="payment-value" style="color: #10b981;">
                            <i class="fas fa-check-circle"></i> LUNAS
                        </div>
                    </div>
                    <div class="payment-item">
                        <div class="payment-label">Nomor Referensi:</div>
                        <div class="payment-value" style="font-family: 'Courier New', monospace;">
                            PAY-{{ str_pad($tagihan->id, 8, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="thank-you">
                <h3><i class="fas fa-heart"></i> Terima Kasih!</h3>
                <p>Terima kasih atas kepercayaan Anda menggunakan layanan BUMDes.Net Tinggarjaya</p>
            </div>
            
            <div class="footer-info">
                <div>
                    <i class="fas fa-globe"></i> www.bumdesnet-tinggarjaya.id
                </div>
                <div>
                    <i class="fab fa-whatsapp"></i> 08132632700
                </div>
                <div>
                    <i class="fas fa-envelope"></i> support@bumdesnet.id
                </div>
            </div>
        </div>

    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>

</body>
</html>