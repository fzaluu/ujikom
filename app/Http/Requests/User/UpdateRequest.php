<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            // Mengabaikan email milik user yang sedang diedit agar tidak error unique
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id ?? $this->route('user')),
            ],
            'password' => 'nullable|min:8', // Password opsional saat edit (boleh kosong jika tidak diubah)
            'role_id' => 'required|exists:roles,id',
        ];
    }
}