<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $pelanggan = auth()->user()->pelanggan;
        
        // Get latest tagihan
        $tagihanTerbaru = Tagihan::where('pelanggan_id', $pelanggan->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->take(5)
            ->get();

        // Stats
        $totalNunggak = Tagihan::where('pelanggan_id', $pelanggan->id)
            ->where('status', 'nunggak')
            ->sum('jumlah');
            
        $totalMenunggu = Tagihan::where('pelanggan_id', $pelanggan->id)
            ->where('status', 'menunggu_konfirmasi')
            ->sum('jumlah');

        return view('pelanggan.dashboard', compact(
            'pelanggan',
            'tagihanTerbaru',
            'totalNunggak',
            'totalMenunggu'
        ));
    }

    public function tagihan(Request $request)
    {
        $pelanggan = auth()->user()->pelanggan;
        
        $query = Tagihan::where('pelanggan_id', $pelanggan->id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by month
        if ($request->filled('bulan')) {
            $bulanTahun = explode('-', $request->bulan);
            if (count($bulanTahun) == 2) {
                $query->where('tahun', $bulanTahun[0])
                      ->where('bulan', $bulanTahun[1]);
            }
        }

        // Filter by year
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $tagihans = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        // Stats
        $stats = [
            'nunggak' => Tagihan::where('pelanggan_id', $pelanggan->id)
                ->where('status', 'nunggak')
                ->count(),
            'total_nunggak' => Tagihan::where('pelanggan_id', $pelanggan->id)
                ->where('status', 'nunggak')
                ->sum('jumlah'),
            'menunggu_konfirmasi' => Tagihan::where('pelanggan_id', $pelanggan->id)
                ->where('status', 'menunggu_konfirmasi')
                ->count(),
            'total_menunggu' => Tagihan::where('pelanggan_id', $pelanggan->id)
                ->where('status', 'menunggu_konfirmasi')
                ->sum('jumlah'),
            'lunas' => Tagihan::where('pelanggan_id', $pelanggan->id)
                ->where('status', 'lunas')
                ->count(),
            'total_lunas' => Tagihan::where('pelanggan_id', $pelanggan->id)
                ->where('status', 'lunas')
                ->sum('jumlah'),
        ];

        return view('pelanggan.tagihan.index', compact('tagihans', 'stats'));
    }

    public function tagihanShow(Tagihan $tagihan)
    {
        // Pastikan tagihan milik pelanggan yang login
        if ($tagihan->pelanggan_id != auth()->user()->pelanggan->id) {
            abort(403, 'Unauthorized');
        }

        $tagihan->load(['pelanggan.user', 'pelanggan.paket']);
        
        return view('pelanggan.tagihan.show', compact('tagihan'));
    }

    public function uploadBukti(Request $request, Tagihan $tagihan)
    {
        // Pastikan tagihan milik pelanggan yang login
        if ($tagihan->pelanggan_id != auth()->user()->pelanggan->id) {
            abort(403, 'Unauthorized');
        }

        // Validasi hanya bisa upload jika status nunggak
        if ($tagihan->status != 'nunggak') {
            return redirect()->back()
                ->with('error', 'Tagihan ini tidak dapat diproses pembayaran.');
        }

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'metode_pembayaran' => 'required|in:transfer_bank,e_wallet,tunai',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            // Hapus bukti lama jika ada
            if ($tagihan->bukti_pembayaran) {
                Storage::disk('public')->delete($tagihan->bukti_pembayaran);
            }

            // Upload bukti baru
            $file = $request->file('bukti_pembayaran');
            $filename = 'bukti_' . $tagihan->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('bukti_pembayaran', $filename, 'public');

            // Update tagihan
            $tagihan->update([
                'bukti_pembayaran' => $path,
                'metode_pembayaran' => $request->metode_pembayaran,
                'catatan_pembayaran' => $request->catatan,
                'status' => 'menunggu_konfirmasi',
            ]);

            return redirect()->back()
                ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupload bukti pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * ✅ METHOD BARU - Cetak Invoice
     */
    public function cetakInvoice(Tagihan $tagihan)
    {
        // Pastikan tagihan milik pelanggan yang login
        if ($tagihan->pelanggan_id != auth()->user()->pelanggan->id) {
            abort(403, 'Unauthorized');
        }

        // Hanya bisa cetak invoice yang sudah lunas
        if ($tagihan->status !== 'lunas') {
            return redirect()->back()
                ->with('error', 'Invoice hanya bisa dicetak untuk tagihan yang sudah lunas.');
        }

        $tagihan->load(['pelanggan.user', 'pelanggan.paket']);

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('pelanggan.tagihan.cetak-invoice', compact('tagihan', 'namaBulan'));
    }

    public function profile()
    {
        $pelanggan = auth()->user()->pelanggan;
        return view('pelanggan.profile', compact('pelanggan'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        try {
            $user = auth()->user();
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($user->pelanggan) {
                $user->pelanggan->update([
                    'no_telepon' => $request->no_telepon,
                    'alamat' => $request->alamat,
                ]);
            }

            return redirect()->route('pelanggan.profile')
                ->with('success', 'Profile berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profile: ' . $e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('pelanggan.profile')
            ->with('success', 'Password berhasil diubah');
    }

    public function paket()
    {
        $pelanggan = auth()->user()->pelanggan;
        
        $pelanggan->load('paket');
        
        $riwayatTagihan = Tagihan::where('pelanggan_id', $pelanggan->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->take(12)
            ->get();
        
        $totalLunas = Tagihan::where('pelanggan_id', $pelanggan->id)
            ->where('status', 'lunas')
            ->count();
            
        $totalNunggak = Tagihan::where('pelanggan_id', $pelanggan->id)
            ->where('status', 'nunggak')
            ->count();
        
        return view('pelanggan.paket.index', compact(
            'pelanggan',
            'riwayatTagihan',
            'totalLunas',
            'totalNunggak'
        ));
    }
}