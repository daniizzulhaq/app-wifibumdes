<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class QrController extends Controller
{
    /**
     * Endpoint yang di-embed dalam QR code.
     * Ketika petugas scan QR pelanggan, diarahkan ke sini.
     *
     * Alur:
     * - Jika sudah login sebagai admin/petugas → langsung ke halaman tagihan pelanggan
     * - Jika belum login → simpan token di session, redirect ke login, lalu lanjut
     * - Jika login sebagai pelanggan lain → tolak
     */
    public function scan(Request $request, string $token)
    {
        // Cari pelanggan berdasarkan token
        $pelanggan = Pelanggan::with(['user', 'paket'])
            ->where('qr_token', $token)
            ->first();

        if (!$pelanggan) {
            return view('qr.invalid', [
                'message' => 'QR Code tidak valid atau sudah kadaluarsa.'
            ]);
        }

        // Jika sudah login
        if (auth()->check()) {
            $user = auth()->user();

            // Admin → ke halaman tagihan admin
            if ($user->role === 'admin') {
                return redirect()->route('admin.tagihan.pelanggan', $pelanggan->id)
                    ->with('info', "📱 QR Scan: Tagihan untuk {$pelanggan->user->name}");
            }

            // Petugas → ke halaman pembayaran petugas
            if ($user->role === 'petugas') {
                return redirect()->route('petugas.pembayaran.pelanggan', $pelanggan->id)
                    ->with('info', "📱 QR Scan: Tagihan untuk {$pelanggan->user->name}");
            }

            // Pelanggan yang sama → ke dashboard-nya sendiri
            if ($user->role === 'pelanggan' && $user->pelanggan?->id === $pelanggan->id) {
                return redirect()->route('pelanggan.dashboard')
                    ->with('info', 'Ini adalah QR Code Anda.');
            }

            // Pelanggan lain yang scan QR orang lain → tolak
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        // Belum login → simpan token di session dan redirect ke login
        session(['qr_redirect_token' => $token]);

        return redirect()->route('login')
            ->with('info', '🔐 Silakan login terlebih dahulu untuk melanjutkan pembayaran.');
    }

    /**
     * Redirect setelah login jika ada QR token di session.
     * Dipanggil dari LoginController setelah authenticated.
     */
    public static function handleAfterLogin(): ?string
    {
        if (session()->has('qr_redirect_token')) {
            $token = session()->pull('qr_redirect_token');
            return route('qr.scan', ['token' => $token]);
        }

        return null;
    }

    /**
     * Halaman untuk melihat QR pelanggan (akses admin/petugas).
     */
    public function show(Pelanggan $pelanggan)
    {
        // Pastikan hanya admin/petugas
        if (!in_array(auth()->user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        $pelanggan->load(['user', 'paket']);
        $pelanggan->ensureHasQrToken();

        return view('qr.show', compact('pelanggan'));
    }

    /**
     * Regenerate QR token pelanggan (keamanan).
     */
    public function regenerate(Pelanggan $pelanggan)
    {
        if (!in_array(auth()->user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        $pelanggan->generateQrToken();

        return redirect()->back()
            ->with('success', '✓ QR Code berhasil diperbarui!');
    }
}