@extends('layouts.admin')

@section('title', 'Import Pelanggan')
@section('page-title', 'Import Pelanggan')

@section('content')
<style>
    .container-custom { max-width: 900px; margin: 0 auto; padding: 20px; }
    .card { background: white; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; margin-bottom: 24px; }
    .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; }
    .card-header h3 { margin: 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
    .card-body { padding: 30px; }
    .back-link { display: inline-flex; align-items: center; gap: 8px; color: #667eea; text-decoration: none; font-weight: 600; margin-bottom: 20px; font-size: 14px; }
    .back-link:hover { color: #5568d3; }

    .upload-area { border: 2px dashed #667eea; border-radius: 12px; padding: 40px; text-align: center; cursor: pointer; transition: all 0.3s; background: #f8f7ff; margin-bottom: 20px; }
    .upload-area:hover, .upload-area.drag-over { background: #ede9ff; border-color: #5568d3; }
    .upload-area i { font-size: 48px; color: #667eea; margin-bottom: 12px; display: block; }
    .upload-area p { margin: 0; color: #374151; font-size: 15px; }
    .upload-area small { color: #6b7280; font-size: 12px; }
    #file_excel { display: none; }
    .file-selected { background: #d1fae5; border-color: #10b981; }
    .file-selected i { color: #10b981; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px; }
    .form-control { width: 100%; padding: 10px 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; outline: none; transition: border-color 0.3s; box-sizing: border-box; }
    .form-control:focus { border-color: #667eea; }

    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.3s; }
    .btn-primary { background: #667eea; color: white; }
    .btn-primary:hover { background: #5568d3; }
    .btn-success { background: #10b981; color: white; }
    .btn-success:hover { background: #059669; }
    .btn-secondary { background: #6b7280; color: white; }
    .btn-secondary:hover { background: #4b5563; }
    .btn-info { background: #3b82f6; color: white; }
    .btn-info:hover { background: #2563eb; }
    .btn-lg { padding: 12px 28px; font-size: 16px; }
    .btn:disabled { opacity: 0.6; cursor: not-allowed; }

    .alert { padding: 15px; margin-bottom: 20px; border-radius: 8px; display: flex; align-items: flex-start; gap: 12px; }
    .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
    .alert-danger { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
    .alert-info { background: #dbeafe; color: #1e40af; border-left: 4px solid #3b82f6; }
    .alert-warning { background: #fef3c7; color: #92400e; border-left: 4px solid #f59e0b; }

    #previewSection { display: none; }
    .table-responsive { overflow-x: auto; border-radius: 8px; border: 1px solid #e5e7eb; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    thead { background: #f9fafb; }
    th { padding: 10px 12px; text-align: left; font-weight: 700; font-size: 11px; color: #374151; text-transform: uppercase; border-bottom: 2px solid #e5e7eb; }
    td { padding: 10px 12px; border-bottom: 1px solid #f3f4f6; }
    tbody tr:hover { background: #f9fafb; }
    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; }
    .paket-info { background: #f3f4f6; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #374151; margin-top: 8px; }
    .paket-info strong { color: #667eea; }
</style>

<div class="container-custom">
    <a href="{{ route('admin.pelanggan.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pelanggan
    </a>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle fa-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-info-circle"></i> Petunjuk Import</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info" style="margin:0;">
                <i class="fas fa-info-circle fa-lg"></i>
                <div>
                    <strong>Format kolom Excel:</strong> A=NO, B=NAMA*, C=ALAMAT*, D=NO WA, E=email PPOE, F=MAP, G=PAKET (harga angka)<br>
                    <strong>Password default</strong> semua pelanggan: <code>12345678</code><br>
                    <strong>Kolom PAKET</strong> dicocokkan ke harga paket di sistem. Jika tidak cocok → pakai Paket Default yang dipilih.
                </div>
            </div>
            <div style="margin-top: 16px;">
                <a href="{{ route('admin.pelanggan.import.template') }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Download Template Excel
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-file-import"></i> Upload File Excel</h3>
        </div>
        <div class="card-body">
            <form id="importForm" action="{{ route('admin.pelanggan.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Paket Default -->
                <div class="form-group">
                    <label for="default_paket_id">
                        <i class="fas fa-wifi"></i> Paket Default <span style="color:#ef4444;">*</span>
                        <small style="font-weight:400; color:#6b7280; margin-left:6px;">
                            — dipakai jika harga di Excel tidak cocok dengan paket di sistem
                        </small>
                    </label>
                    <select name="default_paket_id" id="default_paket_id"
                            class="form-control @error('default_paket_id') is-invalid @enderror"
                            required onchange="updatePaketInfo()">
                        <option value="">-- Pilih Paket Default --</option>
                        @foreach($pakets as $paket)
                            <option value="{{ $paket->id }}"
                                data-nama="{{ $paket->nama_paket ?? $paket->nama ?? '' }}"
                                data-harga="{{ number_format((int) round((float) $paket->harga), 0, ',', '.') }}"
                                {{ old('default_paket_id') == $paket->id ? 'selected' : '' }}>
                                {{ $paket->nama_paket ?? $paket->nama ?? 'Paket #'.$paket->id }}
                                — Rp {{ number_format((int) round((float) $paket->harga), 0, ',', '.') }}/bulan
                            </option>
                        @endforeach
                    </select>
                    @error('default_paket_id')
                        <span style="color:#ef4444; font-size:12px;">{{ $message }}</span>
                    @enderror
                    <div class="paket-info" id="paketInfo" style="display:none;">
                        Pelanggan tanpa paket cocok → <strong id="paketInfoText"></strong>
                    </div>
                </div>

                <hr style="border:none; border-top:1px solid #e5e7eb; margin:24px 0;">

                <!-- Upload Area -->
                <div class="upload-area" id="uploadArea" onclick="document.getElementById('file_excel').click()">
                    <i class="fas fa-cloud-upload-alt" id="uploadIcon"></i>
                    <p id="uploadText">Klik atau seret file Excel ke sini</p>
                    <small id="uploadSubtext">Format: .xlsx atau .xls | Maksimal 5MB</small>
                    <input type="file" id="file_excel" name="file_excel" accept=".xlsx,.xls">
                </div>

                @error('file_excel')
                    <div class="alert alert-danger" style="margin-top:10px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <!-- Preview Section -->
                <div id="previewSection">
                    <hr style="margin: 24px 0; border: none; border-top: 1px solid #e5e7eb;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                        <h4 style="margin:0; color:#374151; font-size:16px; font-weight:700;">
                            Preview Data
                            <span id="previewCount" style="background:#667eea; color:white; border-radius:12px; padding:2px 10px; font-size:12px; margin-left:8px;"></span>
                        </h4>
                        <small style="color:#6b7280;" id="totalCount"></small>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>No WA</th>
                                    <th>Email</th>
                                    <th>Paket (dari Excel)</th>
                                </tr>
                            </thead>
                            <tbody id="previewBody"></tbody>
                        </table>
                    </div>
                    <div class="alert alert-warning" style="margin-top:16px;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Preview <strong>10 baris pertama</strong>. Semua baris valid akan diimport. Baris dengan paket "(pakai default)" akan menggunakan paket default yang dipilih.</span>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="button" id="previewBtn" class="btn btn-info" style="display:none;" onclick="loadPreview()">
                        <i class="fas fa-eye"></i> Preview
                    </button>
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-lg" style="display:none;">
                        <i class="fas fa-file-import"></i>
                        <span id="submitText">Import Sekarang</span>
                        <span id="loadingSpinner" style="display:none;"><i class="fas fa-spinner fa-spin"></i></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput  = document.getElementById('file_excel');

    uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('drag-over'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelected(e.dataTransfer.files[0]);
        }
    });

    fileInput.addEventListener('change', function () {
        if (this.files.length) handleFileSelected(this.files[0]);
    });

    function handleFileSelected(file) {
        uploadArea.classList.add('file-selected');
        document.getElementById('uploadIcon').className = 'fas fa-file-excel';
        document.getElementById('uploadText').textContent = file.name;
        document.getElementById('uploadSubtext').textContent = (file.size / 1024).toFixed(1) + ' KB — Klik untuk ganti';
        document.getElementById('previewBtn').style.display = 'inline-flex';
        document.getElementById('submitBtn').style.display  = 'inline-flex';
        document.getElementById('previewSection').style.display = 'none';
    }

    document.getElementById('importForm').addEventListener('submit', function () {
        document.getElementById('submitText').textContent = 'Sedang mengimport...';
        document.getElementById('loadingSpinner').style.display = 'inline';
        document.getElementById('submitBtn').disabled = true;
    });
});

function updatePaketInfo() {
    const sel  = document.getElementById('default_paket_id');
    const info = document.getElementById('paketInfo');
    const text = document.getElementById('paketInfoText');
    if (sel.value) {
        const opt = sel.options[sel.selectedIndex];
        text.textContent = opt.dataset.nama + ' — Rp ' + opt.dataset.harga + '/bulan';
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
}

function loadPreview() {
    const file = document.getElementById('file_excel').files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file_excel', file);
    formData.append('_token', '{{ csrf_token() }}');

    document.getElementById('previewBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

    fetch('{{ route("admin.pelanggan.import.preview") }}', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            document.getElementById('previewBtn').innerHTML = '<i class="fas fa-eye"></i> Preview';

            if (data.error) { alert('Error: ' + data.error); return; }

            const tbody = document.getElementById('previewBody');
            tbody.innerHTML = '';
            data.preview.forEach((row, i) => {
                tbody.innerHTML += `
                    <tr>
                        <td>${i + 1}</td>
                        <td><strong>${row.nama}</strong></td>
                        <td>${row.alamat}</td>
                        <td>${row.no_wa || '<span style="color:#9ca3af">-</span>'}</td>
                        <td><small>${row.email}</small></td>
                        <td><small>${row.paket}</small></td>
                    </tr>`;
            });

            document.getElementById('previewCount').textContent = data.preview.length + ' baris';
            document.getElementById('totalCount').textContent   = 'Total: ' + data.total + ' pelanggan';
            document.getElementById('previewSection').style.display = 'block';
        })
        .catch(err => {
            document.getElementById('previewBtn').innerHTML = '<i class="fas fa-eye"></i> Preview';
            alert('Gagal memuat preview: ' + err);
        });
}
</script>
@endsection