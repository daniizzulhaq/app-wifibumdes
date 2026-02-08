<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\PaketWifi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PelangganController extends Controller
{
    /**
     * Display a listing of pelanggan.
     */
    public function index(Request $request)
    {
        // Statistics
        $totalPelanggan = Pelanggan::count();
        $pelangganAktif = Pelanggan::where('status', 'aktif')->count();
        $pelangganNonaktif = Pelanggan::where('status', 'nonaktif')->count();
        $pelangganBaru = Pelanggan::whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->count();

        // Query builder
        $query = Pelanggan::with(['user', 'paketWifi']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by paket
        if ($request->has('paket') && $request->paket != '') {
            $query->where('paket_id', $request->paket);
        }

        // Get paginated data
        $pelanggans = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get all paket wifi for filter
        $paketWifis = PaketWifi::all();

        return view('petugas.pelanggan.index', compact(
            'pelanggans',
            'paketWifis',
            'totalPelanggan',
            'pelangganAktif',
            'pelangganNonaktif',
            'pelangganBaru'
        ));
    }

    /**
     * Show the form for creating a new pelanggan.
     */
    public function create()
    {
        $paketWifis = PaketWifi::all();
        return view('petugas.pelanggan.create', compact('paketWifis'));
    }

    /**
     * Store a newly created pelanggan.
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string',
            'link_maps' => 'nullable|url',
            'status' => 'required|in:pending,aktif,nonaktif',
            'paket_id' => 'required|exists:paket_wifi,id',
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'link_maps.url' => 'Format link Google Maps tidak valid',
            'status.required' => 'Status wajib dipilih',
            'paket_id.required' => 'Paket WiFi wajib dipilih',
            'paket_id.exists' => 'Paket WiFi tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pelanggan',
            ]);

            // Create pelanggan data
            $pelanggan = Pelanggan::create([
                'user_id' => $user->id,
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat,
                'link_maps' => $request->link_maps,
                'status' => $request->status,
                'paket_id' => $request->paket_id,
            ]);

            DB::commit();

            return redirect()
                ->route('petugas.pelanggan.index')
                ->with('success', 'Pelanggan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan pelanggan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified pelanggan.
     */
    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['user', 'paketWifi', 'tagihans']);
        
        // Get payment history
        $riwayatPembayaran = $pelanggan->tagihans()
            ->where('status', 'lunas')
            ->with('pembayaran')
            ->orderBy('bulan', 'desc')
            ->limit(10)
            ->get();

        // Get unpaid bills
        $tagihanNunggak = $pelanggan->tagihans()
            ->where('status', 'nunggak')
            ->orderBy('bulan', 'asc')
            ->get();

        return view('petugas.pelanggan.show', compact(
            'pelanggan',
            'riwayatPembayaran',
            'tagihanNunggak'
        ));
    }

    /**
     * Show the form for editing pelanggan.
     */
    public function edit(Pelanggan $pelanggan)
    {
        $pelanggan->load('user');
        $paketWifis = PaketWifi::all();
        
        return view('petugas.pelanggan.edit', compact('pelanggan', 'paketWifis'));
    }

    /**
     * Update the specified pelanggan.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pelanggan->user_id,
            'password' => 'nullable|string|min:8|confirmed',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string',
            'link_maps' => 'nullable|url',
            'status' => 'required|in:pending,aktif,nonaktif',
            'paket_id' => 'required|exists:paket_wifi,id',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update user data
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Update password if provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $pelanggan->user->update($userData);

            // Update pelanggan data
            $pelanggan->update([
                'no_wa' => $request->no_wa,
                'alamat' => $request->alamat,
                'link_maps' => $request->link_maps,
                'status' => $request->status,
                'paket_id' => $request->paket_id,
            ]);

            DB::commit();

            return redirect()
                ->route('petugas.pelanggan.index')
                ->with('success', 'Data pelanggan berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified pelanggan.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        try {
            DB::beginTransaction();

            // Check if pelanggan has unpaid bills
            $hasUnpaidBills = $pelanggan->tagihans()
                ->where('status', 'nunggak')
                ->exists();

            if ($hasUnpaidBills) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat menghapus pelanggan yang masih memiliki tagihan nunggak!');
            }

            // Delete user (will cascade delete pelanggan due to FK constraint)
            $pelanggan->user->delete();

            DB::commit();

            return redirect()
                ->route('petugas.pelanggan.index')
                ->with('success', 'Pelanggan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }

    /**
     * Update pelanggan status.
     */
    public function updateStatus(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'status' => 'required|in:pending,aktif,nonaktif'
        ]);

        try {
            $pelanggan->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status pelanggan berhasil diupdate!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update pelanggan paket.
     */
    public function updatePaket(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'paket_id' => 'required|exists:paket_wifi,id'
        ]);

        try {
            $pelanggan->update([
                'paket_id' => $request->paket_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paket WiFi berhasil diupdate!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate paket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk action for multiple pelanggan.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:aktifkan,nonaktifkan,hapus',
            'pelanggan_ids' => 'required|array',
            'pelanggan_ids.*' => 'exists:pelanggans,id'
        ]);

        try {
            DB::beginTransaction();

            switch ($request->action) {
                case 'aktifkan':
                    Pelanggan::whereIn('id', $request->pelanggan_ids)
                        ->update(['status' => 'aktif']);
                    $message = 'Pelanggan berhasil diaktifkan!';
                    break;

                case 'nonaktifkan':
                    Pelanggan::whereIn('id', $request->pelanggan_ids)
                        ->update(['status' => 'nonaktif']);
                    $message = 'Pelanggan berhasil dinonaktifkan!';
                    break;

                case 'hapus':
                    $pelanggans = Pelanggan::whereIn('id', $request->pelanggan_ids)->get();
                    
                    foreach ($pelanggans as $pelanggan) {
                        // Check unpaid bills
                        $hasUnpaidBills = $pelanggan->tagihans()
                            ->where('status', 'nunggak')
                            ->exists();

                        if (!$hasUnpaidBills) {
                            $pelanggan->user->delete();
                        }
                    }
                    $message = 'Pelanggan berhasil dihapus!';
                    break;
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal melakukan aksi: ' . $e->getMessage());
        }
    }

    /**
     * Export pelanggan to Excel.
     */
    public function export()
    {
        // TODO: Implement Excel export using Laravel Excel package
        return redirect()
            ->back()
            ->with('info', 'Fitur export sedang dalam pengembangan');
    }

    /**
     * Generate random password (AJAX).
     */
    public function generatePassword()
    {
        $length = 12;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }

        return response()->json([
            'password' => $password
        ]);
    }
}