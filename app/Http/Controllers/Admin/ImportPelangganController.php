<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use App\Models\PaketWifi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportPelangganController extends Controller
{
    public function index()
    {
        $pakets = PaketWifi::all();
        return view('admin.pelanggan.import', compact('pakets'));
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/template_import_pelanggan.xlsx');
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File template tidak ditemukan.');
        }
        return response()->download($filePath, 'template_import_pelanggan.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:5120',
        ], [
            'file_excel.required' => 'File Excel wajib diunggah',
            'file_excel.mimes'   => 'File harus berformat .xlsx atau .xls',
            'file_excel.max'     => 'Ukuran file maksimal 5MB',
        ]);

        $defaultPaketId = PaketWifi::first()?->id ?? null;

        try {
            $spreadsheet = IOFactory::load($request->file('file_excel')->getPathname());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, false);

            [$headerRowIndex, $colNama, $colAlamat, $colNoWa, $colEmail, $colPaket]
                = $this->findHeader($rows);

            if ($headerRowIndex === null) {
                return redirect()->back()
                    ->with('error', 'Format file tidak valid. Kolom NAMA tidak ditemukan.');
            }

            $imported = 0;
            $skipped  = 0;

            DB::beginTransaction();

            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex <= $headerRowIndex) continue;

                $nama    = trim((string)($row[$colNama]   ?? ''));
                $alamat  = trim((string)($row[$colAlamat] ?? ''));
                $noWaRaw = $row[$colNoWa]  ?? '';
                $emailRaw = trim((string)($row[$colEmail] ?? ''));
                $paketRaw = $row[$colPaket] ?? null;

                if (empty($nama)) { $skipped++; continue; }

                $noWa    = $this->normalizePhone($noWaRaw);
                $email   = $this->normalizeEmail($emailRaw, $nama);
                $paketId = $this->resolvePaketId($paketRaw, $defaultPaketId);

                if ($paketId === null) { $skipped++; continue; }

                // Buat email unik jika sudah ada
                $base    = $email;
                $counter = 1;
                while (User::where('email', $email)->exists()) {
                    [$local, $domain] = str_contains($base, '@')
                        ? explode('@', $base, 2)
                        : [$base, 'pelanggan.com'];
                    $email = $local . $counter . '@' . $domain;
                    $counter++;
                }

                $user = User::create([
                    'name'     => $nama,
                    'email'    => $email,
                    'password' => Hash::make('12345678'),
                    'role'     => 'pelanggan',
                ]);

                Pelanggan::create([
                    'user_id'   => $user->id,
                    'no_wa'     => $noWa,
                    'alamat'    => $alamat ?: 'TINGARJAYA',
                    'link_maps' => null,
                    'paket_id'  => $paketId,
                    'status'    => 'aktif',
                ]);

                $imported++;
            }

            DB::commit();

            return redirect()->route('admin.pelanggan.index')
                ->with('success', "Import berhasil! {$imported} pelanggan ditambahkan, {$skipped} dilewati.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function preview(Request $request)
    {
        $request->validate(['file_excel' => 'required|mimes:xlsx,xls|max:5120']);

        try {
            $rows = IOFactory::load($request->file('file_excel')->getPathname())
                             ->getActiveSheet()
                             ->toArray(null, true, true, false);

            [$headerRowIndex, $colNama, $colAlamat, $colNoWa, $colEmail, $colPaket]
                = $this->findHeader($rows);

            if ($headerRowIndex === null) {
                return response()->json(['error' => 'Kolom NAMA tidak ditemukan'], 422);
            }

            $preview = [];
            $total   = 0;

            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex <= $headerRowIndex) continue;

                $nama = trim((string)($row[$colNama] ?? ''));
                if (empty($nama)) continue;

                $total++;

                if (count($preview) < 10) {
                    $paketRaw = $row[$colPaket] ?? null;
                    $harga    = $this->parseHarga($paketRaw);
                    $paket    = $harga > 0
                        ? PaketWifi::whereRaw('ROUND(harga) = ?', [$harga])->first()
                        : null;

                    $preview[] = [
                        'nama'   => $nama,
                        'alamat' => trim((string)($row[$colAlamat] ?? '')) ?: '-',
                        'no_wa'  => $this->normalizePhone($row[$colNoWa] ?? ''),
                        'email'  => $this->normalizeEmail(trim((string)($row[$colEmail] ?? '')), $nama),
                        'paket'  => $paket
                            ? ($paket->nama_paket ?? '-') . ' — Rp ' . number_format((int) round((float) $paket->harga), 0, ',', '.')
                            : ($harga > 0
                                ? 'Rp ' . number_format($harga, 0, ',', '.') . ' <span style="color:#ef4444">(tidak cocok)</span>'
                                : '<span style="color:#9ca3af">-</span>'),
                    ];
                }
            }

            return response()->json(['preview' => $preview, 'total' => $total]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    /**
     * Cari baris header dan kolom-kolomnya.
     * Return: [headerRowIndex, colNama, colAlamat, colNoWa, colEmail, colPaket]
     */
    private function findHeader(array $rows): array
    {
        foreach ($rows as $rowIndex => $row) {
            foreach ($row as $colIndex => $cell) {
                if (strtoupper(trim((string) $cell)) === 'NAMA') {
                    return [
                        $rowIndex,
                        $colIndex,        // NAMA
                        $colIndex + 1,    // ALAMAT
                        $colIndex + 2,    // NO WA
                        $colIndex + 3,    // EMAIL PPOE
                        $colIndex + 5,    // PAKET (skip kolom MAP di +4)
                    ];
                }
            }
        }

        return [null, null, null, null, null, null];
    }

    /**
     * Parse nilai kolom PAKET dari Excel menjadi integer harga dalam Rupiah.
     *
     * Kasus yang ditangani:
     *   150        → 150000   (Excel simpan dalam satuan ribu)
     *   125        → 125000
     *   100        → 100000
     *   150000     → 150000   (sudah lengkap)
     *   150000.0   → 150000   (float dari Excel)
     *   "Rp150.000"→ 150000   (format string rupiah)
     *   "150.000"  → 150000
     *
     * Logika konversi ribuan:
     *   Jika nilai ≤ 9999 → dianggap dalam satuan ribu, kali 1000
     *   Jika nilai > 9999 → dianggap sudah dalam rupiah penuh
     */
    private function parseHarga($raw): int
    {
        if ($raw === null || $raw === '') return 0;

        // Nilai numerik langsung dari Excel (int atau float)
        if (is_int($raw) || is_float($raw)) {
            $nilai = (int) round((float) $raw);
            // Nilai kecil = satuan ribu (misal: 150 → 150.000)
            return $nilai <= 9999 && $nilai > 0 ? $nilai * 1000 : $nilai;
        }

        $str = trim((string) $raw);
        if ($str === '') return 0;

        // String numerik murni: "150" atau "150000" atau "150000.0"
        if (is_numeric($str)) {
            $nilai = (int) round((float) $str);
            return $nilai <= 9999 && $nilai > 0 ? $nilai * 1000 : $nilai;
        }

        // Format dengan titik ribuan: "150.000" atau "Rp 150.000" atau "Rp150.000,00"
        // 1. Hapus semua non-digit kecuali titik dan koma
        $clean = preg_replace('/[^0-9.,]/', '', $str);

        // 2. Deteksi apakah titik adalah pemisah ribuan atau desimal
        //    "150.000"  → titik = ribuan → hapus titik → 150000
        //    "150,00"   → koma = desimal → ambil sebelum koma → 150
        //    "150.000,00" → titik=ribuan, koma=desimal → 150000

        if (str_contains($clean, ',')) {
            // Ada koma → koma adalah desimal, titik adalah ribuan
            $clean = str_replace('.', '', $clean);   // hapus titik ribuan
            $clean = explode(',', $clean)[0];          // ambil sebelum koma
        } elseif (substr_count($clean, '.') === 1) {
            // Hanya satu titik → bisa desimal atau ribuan
            $parts = explode('.', $clean);
            if (strlen($parts[1]) === 3) {
                // "150.000" → titik adalah ribuan
                $clean = str_replace('.', '', $clean);
            } else {
                // "150.5" → titik adalah desimal
                $clean = $parts[0];
            }
        } else {
            // Beberapa titik → semua adalah pemisah ribuan: "1.500.000"
            $clean = str_replace('.', '', $clean);
        }

        $nilai = (int) $clean;
        return $nilai <= 9999 && $nilai > 0 ? $nilai * 1000 : $nilai;
    }

    private function normalizePhone($phone): string
    {
        if ($phone === null || $phone === '') return '';

        if (is_int($phone) || is_float($phone)) {
            $phone = (string)(int) round((float) $phone);
        } else {
            $phone = preg_replace('/[^0-9]/', '', (string) $phone);
        }

        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        } elseif (!str_starts_with($phone, '0') && strlen($phone) > 0) {
            $phone = '0' . $phone;
        }

        return $phone;
    }

    private function normalizeEmail(string $emailPpoe, string $nama): string
    {
        if (str_contains($emailPpoe, '@') && str_contains($emailPpoe, '.')) {
            return strtolower($emailPpoe);
        }
        if (str_contains($emailPpoe, '@')) {
            return strtolower($emailPpoe) . '.com';
        }
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '.', $nama));
        return trim($slug, '.') . '@pelanggan.com';
    }

    private function resolvePaketId($paketRaw, $defaultPaketId): ?int
    {
        $harga = $this->parseHarga($paketRaw);

        if ($harga > 0) {
            $paket = PaketWifi::whereRaw('ROUND(harga) = ?', [$harga])->first();
            if ($paket) return $paket->id;
        }

        return $defaultPaketId;
    }
}