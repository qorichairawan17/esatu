<?php

namespace App\Http\Requests\Profile;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
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
        $userId = Auth::id();

        return [
            'namaDepan' => ['required', 'string', 'min:3', 'max:100', 'regex:/[a-zA-Z]/'],
            'namaBelakang' => ['nullable', 'string', 'min:3', 'max:100', 'regex:/[a-zA-Z]/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$userId],
            'kontak' => ['required', 'numeric', 'digits_between:10,15'],
            'tanggalLahir' => ['required', 'date_format:d-m-Y', 'before:today'],
            'jenisKelamin' => ['required', 'in:Laki-Laki,Perempuan'],
            'alamat' => ['required', 'string', 'min:10', 'max:1000', 'regex:/[a-zA-Z0-9]/'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'namaDepan.required' => 'Nama depan wajib diisi.',
            'namaDepan.min' => 'Nama depan minimal 3 karakter.',
            'namaDepan.max' => 'Nama depan maksimal 100 karakter.',
            'namaDepan.regex' => 'Nama depan harus mengandung huruf.',
            'namaBelakang.min' => 'Nama belakang minimal 3 karakter.',
            'namaBelakang.max' => 'Nama belakang maksimal 100 karakter.',
            'namaBelakang.regex' => 'Nama belakang harus mengandung huruf.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.max' => 'Email maksimal 255 karakter.',
            'kontak.required' => 'Nomor HP/Telepon wajib diisi.',
            'kontak.numeric' => 'Nomor HP/Telepon hanya boleh berisi angka.',
            'kontak.digits_between' => 'Nomor HP/Telepon harus terdiri dari 10 hingga 15 angka.',
            'tanggalLahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggalLahir.date_format' => 'Format tanggal lahir tidak valid. Gunakan format (HH-BB-TTTT).',
            'tanggalLahir.before' => 'Tanggal lahir tidak boleh hari ini atau di masa depan.',
            'jenisKelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenisKelamin.in' => 'Pilihan jenis kelamin tidak valid.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.min' => 'Alamat minimal 10 karakter.',
            'alamat.max' => 'Alamat maksimal 1000 karakter.',
            'alamat.regex' => 'Alamat harus mengandung kombinasi huruf atau angka, tidak boleh hanya simbol.',
        ];
    }
}
