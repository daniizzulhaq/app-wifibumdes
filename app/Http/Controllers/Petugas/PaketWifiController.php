<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\PaketWifi;
use Illuminate\Http\Request;

class PaketWifiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pakets = PaketWifi::withCount('pelanggans')
            ->orderBy('harga', 'asc')
            ->get();
        
        return view('petugas.paket.index', compact('pakets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kecepatan' => 'required|string|max:255',
        ]);

        try {
            PaketWifi::create([
                'nama_paket' => $request->nama_paket,
                'harga' => $request->harga,
                'kecepatan' => $request->kecepatan,
            ]);

            return redirect()->route('petugas.paket.index')
                ->with('success', 'Paket berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan paket: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaketWifi $paket)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kecepatan' => 'required|string|max:255',
        ]);

        try {
            $paket->update([
                'nama_paket' => $request->nama_paket,
                'harga' => $request->harga,
                'kecepatan' => $request->kecepatan,
            ]);

            return redirect()->route('petugas.paket.index')
                ->with('success', 'Paket berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui paket: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaketWifi $paket)
    {
        try {
            // Check if paket is being used
            if ($paket->pelanggans()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Paket tidak dapat dihapus karena masih digunakan oleh pelanggan!');
            }

            $paket->delete();

            return redirect()->route('petugas.paket.index')
                ->with('success', 'Paket berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus paket: ' . $e->getMessage());
        }
    }
}