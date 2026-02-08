<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketWifi;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = PaketWifi::withCount('pelanggans')->get();
        
        return view('admin.paket.index', compact('pakets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kecepatan' => 'required|string|max:255',
        ]);

        PaketWifi::create($request->all());

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kecepatan' => 'required|string|max:255',
        ]);

        $paket = PaketWifi::findOrFail($id);
        $paket->update($request->all());

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil diupdate!');
    }

    public function destroy($id)
    {
        $paket = PaketWifi::findOrFail($id);
        
        // Cek apakah paket masih digunakan
        if ($paket->pelanggans()->count() > 0) {
            return redirect()->route('admin.paket.index')
                ->with('error', 'Paket tidak dapat dihapus karena masih digunakan oleh pelanggan!');
        }

        $paket->delete();

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil dihapus!');
    }
}