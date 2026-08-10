<?php

namespace App\Http\Requests\JenisProduk;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:100',
                // ->ignore() dipakai saat update supaya nama sendiri tidak dianggap duplikat.
                // 'jenis_produk' route parameter (bukan 'jenisProduk') karena itu nama parameter di URI.
                Rule::unique('jenis_produk', 'nama')->ignore($this->route('jenis_produk')),
                'foto'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.unique' => 'Jenis produk dengan nama ini sudah ada.',
        ];
    }
}
