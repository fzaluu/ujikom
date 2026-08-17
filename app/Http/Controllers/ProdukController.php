<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\JenisProduk;
use App\Http\Requests\Produk\StoreRequest;
use App\Http\Requests\Produk\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            // --- URUTKAN BERDASARKAN PRIORITAS STOK ---
            ->orderByRaw("
                CASE 
                    WHEN stok = 0 THEN 1
                    WHEN stok <= 5 THEN 2
                    WHEN stok > 100 THEN 4
                    ELSE 3
                END ASC
            ")
            ->latest('updated_at') // Urutan lapis kedua jika prioritas stoknya sama
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
        // $request->validated() sudah memastikan jenis_id wajib diisi sesuai aturan di StoreRequest
        $data = $request->validated();
        
        $data['user_id'] = auth()->id();

        if ($request->hasFile('foto')) {
            // Simpan ke folder 'products' di disk 'public'
            $data['foto'] = $request->file('foto')->store('products', 'public');
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
            // Hapus file lama jika ada agar tidak menumpuk
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }
            // Upload baru
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diubah!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus file fisik dari storage jika ada
        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}