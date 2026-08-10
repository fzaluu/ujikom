<?php

namespace App\Http\Controllers;

use App\Models\JenisProduk;
use App\Http\Requests\JenisProduk\StoreRequest;

class JenisProdukController extends Controller
{
    public function index()
    {
        // withCount agar tahu jumlah produk per jenis tanpa N+1 (1 query saja)
        $jenisProduk = JenisProduk::withCount('produk')->orderBy('nama')->paginate(10)->withQueryString();

        return view('jenis-produk.index', compact('jenisProduk'));
    }

    public function create()
    {
        return view('jenis-produk.create');
    }

    public function store(StoreRequest $request)
    {
        JenisProduk::create($request->validated());

        return redirect()->route('jenis-produk.index')->with('success', 'Jenis produk berhasil ditambahkan!');
    }

    public function edit(JenisProduk $jenis_produk)
    {
        $jenisProduk = $jenis_produk;
        return view('jenis-produk.edit', compact('jenisProduk'));
    }

    public function update(StoreRequest $request, JenisProduk $jenis_produk)
    {
        $jenis_produk->update($request->validated());

        return redirect()->route('jenis-produk.index')->with('success', 'Jenis produk berhasil diubah!');
    }

    public function destroy(JenisProduk $jenis_produk)
    {
        // Produk yang masih pakai jenis ini otomatis jadi jenis_id = null (lihat migration nullOnDelete),
        // bukan ikut terhapus.
        $jenis_produk->delete();

        return redirect()->route('jenis-produk.index')->with('success', 'Jenis produk berhasil dihapus. Produk terkait tidak ikut terhapus, hanya jadi tanpa jenis.');
    }
}
