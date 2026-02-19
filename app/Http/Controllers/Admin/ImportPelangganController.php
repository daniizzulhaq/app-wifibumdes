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
            'file_excel'       => 'required|mimes:xlsx,xls|max:5120',
            'default_paket_id' => 'required|exists:paket_wifi,id',
        ], [
            'file_excel.required'       => 'File Excel wajib diunggah',
            'file_excel.mimes'          => 'File harus berformat .xlsx atau .xls',
            'file_excel.max'            => 'Ukuran file maksimal 5MB',
            'default_paket_id.required' => 'Pilih paket default terlebih dahulu',
            'default_paket_id.exists'   => 'Paket default tidak valid',
        ]);

        $defaultPaketId = (int) $request->input('default_paket_id');

        try {
            $spreadsheet = IOFactory::load($request->file('file_excel')->getPathname());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, false);

            // Cari baris header secara dinamis
            $headerRowIndex = null;
            $colNama = $colAlamat = $colNoWa = $colEmail = $colPaket = null;

            foreach ($rows as $rowIndex => $row) {
                foreach ($row as $colIndex => $cell) {
                    if (strtoupper(trim((string)$cell)) === 'NAMA') {
                        $headerRowIndex = $rowIndex;
                        $colNama   = $colIndex;
                        $colAlamat = $colIndex + 1;
                        $colNoWa   = $colIndex + 2;
                        $colEmail  = $colIndex + 3;
                        $colPaket  = $colIndex + 5; // skip kolom MAP
                        break 2;
                    }
                }
            }

            if ($headerRowIndex === null) {
                return redirect()->back()
                    ->with('error', 'Format file tidak valid. Kolom NAMA tidak ditemukan.');
            }

            // keyBy harga sebagai int — handle decimal cast model (150000.00 → 150000)
            $paketByHarga = PaketWifi::all()->keyBy(fn($p) => (int) round((float) $p->harga));

            $imported = 0;
            $skipped  = 0;

            DB::beginTransaction();

            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex <= $headerRowIndex) continue;

                $nama      = trim((string)($row[$colNama]  ?? ''));
                $alamat    = trim((string)($row[$colAlamat] ?? ''));
                $noWaRaw   = $row[$colNoWa]  ?? '';
                $emailPpoe = trim((string)($row[$colEmail]  ?? ''));
                $paketRaw  = $row[$colPaket] ?? null;

                if (empty($nama)) { $skipped++; continue; }

                $noWa    = $this->normalizePhone($noWaRaw);
                $email   = $this->normalizeEmail($emailPpoe, $nama);
                $paketId = $this->resolvePaketId($paketRaw, $paketByHarga, $defaultPaketId);

                // Buat email unik
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
                    'user_id'        => $user->id,
                    'no_wa'          => $noWa,
                    'alamat'         => $alamat ?: 'TINGARJAYA',
                    'link_maps'      => null,
                    'paket_id'       => $paketId,
                    'status'         => 'aktif',
                    
                ]);

                $imported++;
            }

            DB::commit();

            return redirect()->route('admin.pelanggan.index')
                ->with('success', "Import berhasil! {$imported} pelanggan ditambahkan.");

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

            $headerRowIndex = null;
            $colNama = $colAlamat = $colNoWa = $colEmail = $colPaket = null;

            foreach ($rows as $rowIndex => $row) {
                foreach ($row as $colIndex => $cell) {
                    if (strtoupper(trim((string)$cell)) === 'NAMA') {
                        $headerRowIndex = $rowIndex;
                        $colNama   = $colIndex;
                        $colAlamat = $colIndex + 1;
                        $colNoWa   = $colIndex + 2;
                        $colEmail  = $colIndex + 3;
                        $colPaket  = $colIndex + 5;
                        break 2;
                    }
                }
            }

            if ($headerRowIndex === null) {
                return response()->json(['error' => 'Kolom NAMA tidak ditemukan'], 422);
            }

            $paketByHarga = PaketWifi::all()->keyBy(fn($p) => (int) round((float) $p->harga));
            $preview = [];
            $total   = 0;

            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex <= $headerRowIndex) continue;

                $nama = trim((string)($row[$colNama] ?? ''));
                if (empty($nama)) continue;

                $total++;

                if (count($preview) < 10) {
                    $paketRaw  = $row[$colPaket] ?? null;
                    $harga     = $this->parseHarga($paketRaw);  // selalu int
                    $paket     = $paketByHarga->get($harga);
                    $namapaket = $paket ? ($paket->nama_paket ?? $paket->nama ?? 'Paket') : null;

                    $preview[] = [
                        'nama'   => $nama,
                        'alamat' => trim((string)($row[$colAlamat] ?? '')) ?: '-',
                        'no_wa'  => $this->normalizePhone($row[$colNoWa] ?? ''),
                        'email'  => $this->normalizeEmail(trim((string)($row[$colEmail] ?? '')), $nama),
                        // tampilkan harga saja dari Excel, bukan dari DB
                        'paket'  => $harga > 0
                            ? 'Rp ' . number_format($harga, 0, ',', '.') . ($paket ? '' : ' (pakai default)')
                            : '(pakai default)',
                    ];
                }
            }

            return response()->json(['preview' => $preview, 'total' => $total]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Parse harga dari berbagai format nilai sel Excel:
     *   float  : 150000.0        → 150000
     *   int    : 150000          → 150000
     *   string : "Rp150.000,00" → 150000
     *   string : "150000"        → 150000
     */
    private function parseHarga($raw): int
    {
        if ($raw === null || $raw === '') return 0;

        // Nilai numerik langsung dari Excel (int atau float)
        if (is_int($raw) || is_float($raw)) {
            return (int) round((float) $raw);
        }

        $str = (string) $raw;

        // Cek apakah string yang isinya murni angka/desimal: "150000" atau "150000.0"
        if (is_numeric($str)) {
            return (int) round((float) $str);
        }

        // Format Rupiah: "Rp150.000,00" atau "Rp 150.000"
        // Hapus semua non-digit kecuali koma, lalu ambil bagian sebelum koma
        $clean = preg_replace('/[^0-9,]/', '', $str); // "150000,00"
        $parts = explode(',', $clean);
        return (int) ($parts[0] ?? 0);
    }

    private function normalizePhone($phone): string
    {
        if ($phone === null || $phone === '') return '';

        // Float/int dari Excel: 8987588334.0 → "08987588334"
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

    private function resolvePaketId($paketRaw, $paketByHarga, int $defaultPaketId): int
    {
        $harga = $this->parseHarga($paketRaw);

        if ($harga > 0 && $paketByHarga->has($harga)) {
            return $paketByHarga->get($harga)->id;
        }

        return $defaultPaketId;
    }
}