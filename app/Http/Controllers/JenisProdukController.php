<?php

namespace App\Http\Controllers;

use App\Models\JenisProduk;
use App\Http\Requests\JenisProduk\StoreRequest;
use Illuminate\Http\Request;

class JenisProdukController extends Controller
{

    public function index(Request $request)
    {
        $keyword = $request->input('search');

        $jenisProduk = JenisProduk::withCount('produk')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', '%' . $keyword . '%');
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

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
        // Cek apakah jenis produk ini masih digunakan oleh produk lain
        if ($jenis_produk->produk()->count() > 0) {
            return redirect()
                ->route('jenis-produk.index')
                ->with('error', 'Jenis produk ini tidak dapat dihapus karena masih terikat dengan data produk!');
        }

        // Jika aman (tidak ada produk yang pakai), lakukan hapus
        $jenis_produk->delete();

        return redirect()->route('jenis-produk.index')->with('success', 'Jenis produk berhasil dihapus.');
    }
}