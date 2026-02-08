@extends('layouts.admin')

@section('title', 'Edit Transaksi')
@section('page-title', 'Edit Transaksi')

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
        padding: 30px;
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

    /* Alert */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .alert-warning {
        background: #fef3c7;
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    .alert button {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
    }

    .alert button:hover { opacity: 1; }

    /* Form Layout */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .form-grid .form-group-full {
        grid-column: 1 / -1;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group label .req {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        padding: 12px 14px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        color: #111827;
        background: #fafafa;
        transition: all 0.25s;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #667eea;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.15);
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 90px;
    }

    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 40px;
        cursor: pointer;
    }

    .form-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-hint {
        color: #6b7280;
        font-size: 12px;
        margin-top: 6px;
    }

    /* Jenis Radio */
    .radio-group {
        display: flex;
        gap: 12px;
    }

    .radio-card {
        flex: 1;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 14px 16px;
        cursor: pointer;
        transition: all 0.25s;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fafafa;
    }

    .radio-card:hover {
        border-color: #d1d5db;
        background: #fff;
    }

    .radio-card input[type="radio"] {
        display: none;
    }

    .radio-card input[type="radio"]:checked + .radio-label {
        font-weight: 700;
    }

    .radio-card.selected-masuk {
        border-color: #10b981;
        background: #ecfdf5;
    }

    .radio-card.selected-keluar {
        border-color: #ef4444;
        background: #fef2f2;
    }

    .radio-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .radio-icon.masuk  { background: #d1fae5; color: #10b981; }
    .radio-icon.keluar { background: #fee2e2; color: #ef4444; }

    .radio-label {
        font-size: 14px;
        color: #374151;
    }

    .radio-label small {
        display: block;
        font-size: 11px;
        color: #6b7280;
        font-weight: 400;
        margin-top: 1px;
    }

    /* Form Footer */
    .form-footer {
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #f3f4f6;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
    }
</style>

<div class="container-custom">

    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h2>✏️ Edit Transaksi</h2>
            <p>Ubah data transaksi yang sudah ada</p>
        </div>
        <a href="{{ route('admin.buku_kas.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Warning jika transaksi dari tagihan -->
    @if($bukuKas->referensi_tagihan_id)
        <div class="alert alert-warning">
            <span>
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>Perhatian!</strong> Transaksi ini berasal dari pembayaran tagihan dan tidak dapat diedit di sini. 
                Silakan edit melalui menu Tagihan.
            </span>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('admin.buku_kas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    @else
        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <span><i class="fas fa-exclamation-circle"></i> Silakan perbaiki kesalahan di bawah.</span>
                <button onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
        @endif

        <!-- Form Card -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-edit"></i> &nbsp;Form Edit Transaksi</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.buku_kas.update', $bukuKas->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">

                        <!-- Tanggal -->
                        <div class="form-group">
                            <label>Tanggal <span class="req">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') border-red-500 @enderror"
                                   value="{{ old('tanggal', $bukuKas->tanggal) }}">
                            @error('tanggal')
                                <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nominal -->
                        <div class="form-group">
                            <label>Nominal (Rp) <span class="req">*</span></label>
                            <input type="number" name="nominal" class="form-control @error('nominal') border-red-500 @enderror"
                                   value="{{ old('nominal', $bukuKas->nominal) }}" min="0" placeholder="Contoh: 500000">
                            @error('nominal')
                                <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis: Pemasukan / Pengeluaran -->
                        <div class="form-group form-group-full">
                            <label>Jenis Transaksi <span class="req">*</span></label>
                            <div class="radio-group">

                                <label class="radio-card {{ old('jenis', $bukuKas->jenis) == 'pemasukan' ? 'selected-masuk' : '' }}" id="card-masuk">
                                    <input type="radio" name="jenis" value="pemasukan"
                                           {{ old('jenis', $bukuKas->jenis) == 'pemasukan' ? 'checked' : '' }}
                                           onchange="selectJenis('masuk')">
                                    <div class="radio-icon masuk"><i class="fas fa-arrow-down"></i></div>
                                    <div class="radio-label">
                                        Pemasukan
                                        <small>Dana masuk ke kas</small>
                                    </div>
                                </label>

                                <label class="radio-card {{ old('jenis', $bukuKas->jenis) == 'pengeluaran' ? 'selected-keluar' : '' }}" id="card-keluar">
                                    <input type="radio" name="jenis" value="pengeluaran"
                                           {{ old('jenis', $bukuKas->jenis) == 'pengeluaran' ? 'checked' : '' }}
                                           onchange="selectJenis('keluar')">
                                    <div class="radio-icon keluar"><i class="fas fa-arrow-up"></i></div>
                                    <div class="radio-label">
                                        Pengeluaran
                                        <small>Dana keluar dari kas</small>
                                    </div>
                                </label>

                            </div>
                            @error('jenis')
                                <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="form-group">
                            <label>Kategori <span class="req">*</span></label>
                            <select name="kategori" class="form-control @error('kategori') border-red-500 @enderror">
                                <option value="" disabled>-- Pilih Kategori --</option>
                                <option value="operasional"      {{ old('kategori', $bukuKas->kategori) == 'operasional'      ? 'selected' : '' }}>Operasional</option>
                                <option value="perbaikan"        {{ old('kategori', $bukuKas->kategori) == 'perbaikan'        ? 'selected' : '' }}>Perbaikan</option>
                                <option value="perawatan"        {{ old('kategori', $bukuKas->kategori) == 'perawatan'        ? 'selected' : '' }}>Perawatan</option>
                                <option value="pelatihan"        {{ old('kategori', $bukuKas->kategori) == 'pelatihan'        ? 'selected' : '' }}>Pelatihan</option>
                                <option value="stock_barang"     {{ old('kategori', $bukuKas->kategori) == 'stock_barang'     ? 'selected' : '' }}>Stock Barang</option>
                                <option value="tagihan_banwith"  {{ old('kategori', $bukuKas->kategori) == 'tagihan_banwith'  ? 'selected' : '' }}>Tagihan Bandwidth</option>
                                <option value="honor_karyawan"   {{ old('kategori', $bukuKas->kategori) == 'honor_karyawan'   ? 'selected' : '' }}>Honor Karyawan</option>
                                <option value="sosial"           {{ old('kategori', $bukuKas->kategori) == 'sosial'           ? 'selected' : '' }}>Sosial</option>
                                <option value="donatur"          {{ old('kategori', $bukuKas->kategori) == 'donatur'          ? 'selected' : '' }}>Donatur</option>
                                <option value="listrik"          {{ old('kategori', $bukuKas->kategori) == 'listrik'          ? 'selected' : '' }}>Listrik</option>
                                <option value="bpjs"             {{ old('kategori', $bukuKas->kategori) == 'bpjs'             ? 'selected' : '' }}>BPJS</option>
                                <option value="pajak"            {{ old('kategori', $bukuKas->kategori) == 'pajak'            ? 'selected' : '' }}>Pajak</option>
                                <option value="administrasi"     {{ old('kategori', $bukuKas->kategori) == 'administrasi'     ? 'selected' : '' }}>Administrasi</option>
                                <option value="thr"              {{ old('kategori', $bukuKas->kategori) == 'thr'              ? 'selected' : '' }}>THR</option>
                                <option value="lain_lain"        {{ old('kategori', $bukuKas->kategori) == 'lain_lain'        ? 'selected' : '' }}>Lain - Lain</option>
                            </select>
                            @error('kategori')
                                <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control"
                                      placeholder="Keterangan tambahan (optional)">{{ old('keterangan', $bukuKas->keterangan) }}</textarea>
                            <div class="form-hint">Isi jika diperlukan untuk detail lebih lanjut</div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="form-footer">
                        <a href="{{ route('admin.buku_kas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Transaksi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

</div>

<script>
    function selectJenis(type) {
        const cardMasuk  = document.getElementById('card-masuk');
        const cardKeluar = document.getElementById('card-keluar');

        cardMasuk.classList.remove('selected-masuk');
        cardKeluar.classList.remove('selected-keluar');

        if (type === 'masuk') {
            cardMasuk.classList.add('selected-masuk');
        } else {
            cardKeluar.classList.add('selected-keluar');
        }
    }

    // Jalankan saat load kalau ada old value atau data dari database
    document.addEventListener('DOMContentLoaded', function () {
        const checked = document.querySelector('input[name="jenis"]:checked');
        if (checked) {
            selectJenis(checked.value === 'pemasukan' ? 'masuk' : 'keluar');
        }
    });
</script>
@endsection