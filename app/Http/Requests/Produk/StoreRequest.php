<?php

namespace App\Http\Requests\Produk;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_id'   => 'nullable|exists:jenis_produk,id',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama'       => 'required|string|max:255',
            'harga_beli' => 'required|integer|min:0', // Diubah ke bahasa Indonesia
            'harga_jual' => 'required|integer|min:0', // Diubah ke bahasa Indonesia
            'stok'       => 'required|integer|min:0',  // Diubah ke bahasa Indonesia
        ];
    }
}