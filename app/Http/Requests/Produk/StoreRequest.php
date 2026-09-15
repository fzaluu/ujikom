<?php

namespace App\Http\Requests\Produk;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Bagian penting ini yang otomatis membuang titik (misal "3.131" jadi "3131") sebelum divalidasi
        $this->merge([
            'harga_beli' => $this->harga_beli ? str_replace('.', '', $this->harga_beli) : null,
            'harga_jual' => $this->harga_jual ? str_replace('.', '', $this->harga_jual) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'jenis_id'   => 'nullable|exists:jenis_produk,id',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama'       => 'required|string|max:255',
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'stok'       => 'required|integer|min:0',
        ];
    }
}