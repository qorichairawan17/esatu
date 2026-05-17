<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AuthRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'namaDepan' => 'required',
            'namaBelakang' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'privacy_policy' => 'accepted',
        ];
    }

    public function messages()
    {
        return [
            'namaDepan.required' => 'Nama depan harus diisi.',
            'namaBelakang.required' => 'Nama belakang harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf kapital dan huruf kecil.',
            'password.numbers' => 'Password harus mengandung setidaknya satu angka.',
            'password.symbols' => 'Password harus mengandung setidaknya satu simbol.',
            'privacy_policy.accepted' => 'Kamu harus menyetujui Kebijakan Privasi & Persyaratan Penggunaan.',
        ];
    }
}
