<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\User;
use App\Models\PaketWifi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggans = Pelanggan::with(['user', 'paket', 'pppoeAccount'])
            ->paginate(10);
        
        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pakets = PaketWifi::all();
        
        return view('admin.pelanggan.create', compact('pakets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
       $validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|string|min:6',
    'no_wa' => 'required|string|max:20',
    'alamat' => 'required|string',
    'link_maps' => 'nullable|url',
    'paket_id' => 'required|exists:paket_wifi,id',  // ← Ubah ini
    'status' => 'required|in:aktif,pending,nonaktif'
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'paket_id.required' => 'Paket wajib dipilih',
            'paket_id.exists' => 'Paket tidak valid',
            'status.required' => 'Status wajib dipilih'
        ]);

        try {
            DB::beginTransaction();

            // Buat user baru
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'pelanggan'
            ]);

            // Buat data pelanggan
            $pelanggan = Pelanggan::create([
                'user_id' => $user->id,
                'no_wa' => $validated['no_wa'],
                'alamat' => $validated['alamat'],
                'link_maps' => $request->link_maps,
                'paket_id' => $validated['paket_id'],
                'status' => $validated['status'],
                'tgl_registrasi' => now()
            ]);

            DB::commit();

            return redirect()->route('admin.pelanggan.index')
                ->with('success', 'Pelanggan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pelanggan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['user', 'paket', 'pppoeAccount', 'tagihans']);
        
        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $pelanggan)
    {
        $pakets = PaketWifi::all();
        
        return view('admin.pelanggan.edit', compact('pelanggan', 'pakets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        // Validasi input
      $validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email,' . $pelanggan->user_id,
    'password' => 'nullable|string|min:6',
    'no_wa' => 'required|string|max:20',
    'alamat' => 'required|string',
    'link_maps' => 'nullable|url',
    'paket_id' => 'required|exists:paket_wifi,id',  // ← Ubah ini juga
    'status' => 'required|in:aktif,pending,nonaktif'
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'paket_id.required' => 'Paket wajib dipilih',
            'paket_id.exists' => 'Paket tidak valid',
            'status.required' => 'Status wajib dipilih'
        ]);

        try {
            DB::beginTransaction();

            // Update user
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email']
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }
            
            $pelanggan->user->update($userData);

            // Update pelanggan
            $pelanggan->update([
                'no_wa' => $validated['no_wa'],
                'alamat' => $validated['alamat'],
                'link_maps' => $request->link_maps,
                'paket_id' => $validated['paket_id'],
                'status' => $validated['status']
            ]);

            DB::commit();

            return redirect()->route('admin.pelanggan.index')
                ->with('success', 'Data pelanggan berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal mengupdate pelanggan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        try {
            DB::beginTransaction();

            $nama = $pelanggan->user->name ?? 'Pelanggan';
            
            // Hapus user terkait (akan cascade delete pelanggan)
            $pelanggan->user->delete();

            DB::commit();

            return redirect()->route('admin.pelanggan.index')
                ->with('success', "Pelanggan {$nama} berhasil dihapus!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }

    /**
     * Update status pelanggan
     */
    public function updateStatus(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'status' => 'required|in:aktif,pending,nonaktif'
        ]);

        try {
            $pelanggan->update([
                'status' => $validated['status']
            ]);

            return redirect()->back()
                ->with('success', 'Status pelanggan berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action untuk pelanggan
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:aktifkan,nonaktifkan,hapus',
            'pelanggan_ids' => 'required|array',
            'pelanggan_ids.*' => 'exists:pelanggans,id'
        ]);

        try {
            DB::beginTransaction();

            switch ($validated['action']) {
                case 'aktifkan':
                    Pelanggan::whereIn('id', $validated['pelanggan_ids'])
                        ->update(['status' => 'aktif']);
                    $message = 'Pelanggan berhasil diaktifkan!';
                    break;

                case 'nonaktifkan':
                    Pelanggan::whereIn('id', $validated['pelanggan_ids'])
                        ->update(['status' => 'nonaktif']);
                    $message = 'Pelanggan berhasil dinonaktifkan!';
                    break;

                case 'hapus':
                    $pelanggans = Pelanggan::whereIn('id', $validated['pelanggan_ids'])->get();
                    foreach ($pelanggans as $pelanggan) {
                        $pelanggan->user->delete();
                    }
                    $message = 'Pelanggan berhasil dihapus!';
                    break;
            }

            DB::commit();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal melakukan aksi: ' . $e->getMessage());
        }
    }

    /**
     * Export data pelanggan
     */
    public function export()
    {
        // Implementasi export (bisa menggunakan Laravel Excel)
        // Untuk sementara redirect kembali
        return redirect()->back()
            ->with('info', 'Fitur export sedang dalam pengembangan');
    }
}