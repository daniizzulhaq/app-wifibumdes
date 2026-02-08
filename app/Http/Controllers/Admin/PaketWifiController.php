<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketWifi;
use Illuminate\Http\Request;

class PaketWifiController extends Controller
{
    /**
     * Display a listing of paket wifi
     */
    public function index()
    {
        $pakets = PaketWifi::withCount('pelanggans')->paginate(10);
        return view('admin.paket.index', compact('pakets'));
    }

    /**
     * Show the form for creating a new paket
     */
    public function create()
    {
        return view('admin.paket.create');
    }

    /**
     * Store a newly created paket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        PaketWifi::create($validated);

        return redirect()
            ->route('admin.paket.index')
            ->with('success', 'Paket WiFi berhasil ditambahkan!');
    }

    /**
     * Display the specified paket
     */
    public function show(PaketWifi $paket)
    {
        $paket->loadCount('pelanggans');
        return view('admin.paket.show', compact('paket'));
    }

    /**
     * Show the form for editing the specified paket
     */
    public function edit(PaketWifi $paket)
    {
        return view('admin.paket.edit', compact('paket'));
    }

    /**
     * Update the specified paket
     */
    public function update(Request $request, PaketWifi $paket)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kecepatan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        $paket->update($validated);

        return redirect()
            ->route('admin.paket.index')
            ->with('success', 'Paket WiFi berhasil diupdate!');
    }

    /**
     * Remove the specified paket
     */
    public function destroy(PaketWifi $paket)
    {
        // Cek apakah ada pelanggan yang menggunakan paket ini
        if ($paket->pelanggans()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus paket yang masih digunakan pelanggan!');
        }

        $paket->delete();

        return redirect()
            ->route('admin.paket.index')
            ->with('success', 'Paket WiFi berhasil dihapus!');
    }
}