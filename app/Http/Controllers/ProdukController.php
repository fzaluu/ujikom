<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\JenisProduk;
use App\Http\Requests\Produk\StoreRequest;
use App\Http\Requests\Produk\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $selectedJenis = null;
        if ($request->filled('jenis_id')) {
            $selectedJenis = JenisProduk::find($request->jenis_id);
        }

        $products = Produk::with(['user', 'jenisProduk'])
            ->when($request->filled('jenis_id'), function ($query) use ($request) {
                $query->where('jenis_id', $request->jenis_id);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('nama', 'like', "%{$request->search}%");
            })
            // --- 1. URUTKAN UTAMA: BERDASARKAN ID JENIS (ASC) ---
            ->orderByRaw("CASE WHEN jenis_id IS NULL THEN 1 ELSE 0 END ASC")
            ->orderBy('jenis_id', 'asc')
            // --- 2. URUTKAN KEDUA: BERDASARKAN PRIORITAS STOK DI DALAM JENISNYA ---
            ->orderByRaw("
                CASE 
                    WHEN stok = 0 THEN 1
                    WHEN stok <= 10 THEN 2
                    WHEN stok > 100 THEN 4
                    ELSE 3
                END ASC
            ")
            // --- 3. URUTKAN KETIGA: DATA TERBARU JIKA STOK & JENIS SAMA ---
            ->latest('id') 
            ->paginate(10)
            ->withQueryString();

        return view('produk.index', compact('products', 'selectedJenis'));
    }

    public function create()
    {
        $jenisProduk = JenisProduk::orderBy('nama')->get();
        return view('produk.create', compact('jenisProduk'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'produk_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Simpan langsung fisik file ke folder public/products
            $file->move(public_path('products'), $filename);
            
            // Path relatif yang disimpan ke database
            $data['foto'] = 'products/' . $filename;
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Produk $produk)
    {
        $produk->load('jenisProduk');
        return view('produk.show', compact('produk'));
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $jenisProduk = JenisProduk::orderBy('nama')->get();
        return view('produk.edit', compact('produk', 'jenisProduk'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            // Hapus file fisik lama jika ada di public/products
            if ($produk->foto && file_exists(public_path($produk->foto))) {
                @unlink(public_path($produk->foto));
            }

            $file = $request->file('foto');
            $filename = 'produk_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('products'), $filename);
            
            $data['foto'] = 'products/' . $filename;
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diubah!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        try {
            // Hapus data dari database terlebih dahulu
            $produk->delete();

            // Hapus file fisik foto dari public/products jika ada
            if ($produk->foto && file_exists(public_path($produk->foto))) {
                @unlink(public_path($produk->foto));
            }

            return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()
                ->route('produk.index')
                ->with('error', 'Produk tidak dapat dihapus karena masih tercatat dalam riwayat transaksi penjualan!');
        }
    }
}