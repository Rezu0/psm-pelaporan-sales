<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $foto = $this->isMethod('POST') ? 'required' : 'nullable';

        return [
            'segment_id'    => ['required', Rule::exists('segments', 'id')->whereNull('deleted_at')],
            'company_name'  => ['required', 'string', 'max:255'],
            'contact_name'  => ['required', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'market_date'   => ['required', 'date', 'before_or_equal:now'],
            'description'   => ['required', 'string', 'max:3000'],
            'foto'          => [$foto, 'image', 'mimes:jpg,jpeg,png,webp', 'max:15360'],
        ];
    }

    public function messages(): array
    {
        return [
            'segment_id.required'         => 'Segmen wajib dipilih.',
            'segment_id.exists'           => 'Segmen tidak valid.',
            'company_name.required'       => 'Nama perusahaan wajib diisi.',
            'contact_name.required'       => 'Nama kontak wajib diisi.',
            'contact_phone.max'           => 'No. telepon maksimal 30 karakter.',
            'contact_email.email'         => 'Format email kontak tidak benar.',
            'market_date.required'        => 'Tanggal dan jam kunjungan wajib diisi.',
            'market_date.date'            => 'Tanggal kunjungan tidak valid.',
            'market_date.before_or_equal' => 'Tanggal kunjungan tidak boleh yang belum terjadi.',
            'description.required'        => 'Deskripsi wajib diisi.',
            'foto.required'               => 'Foto wajib dipilih.',
            'foto.image'                  => 'File harus berupa foto.',
            'foto.mimes'                  => 'Format foto harus JPG, PNG, atau WebP.',
            'foto.max'                    => 'Ukuran foto maksimal 15 MB.',
            'foto.uploaded'               => 'Foto gagal diunggah. Ukurannya mungkin terlalu besar.',
        ];
    }
}