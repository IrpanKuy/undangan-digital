<?php

namespace App\Rules;

class templateContentUndanganValidate
{
    /**
     * Get the validation rules.
     */
    public static function rules($id = null): array
    {
        return [
            // Undangan
            'judul' => 'required|string|max:255',
            'url' => 'required|string|max:255|unique:undangans,url' . ($id ? ",$id" : ""),
            'thumbnail' => ($id ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,webp|max:10240',
            'salam_pembuka' => 'required|string',
            'text_pembuka' => 'required|string',
            'video_youtube_url' => 'nullable|url',

            // Data Mempelai
            'nama_panggilan_pria' => 'required|string|max:255',
            'nama_lengkap_pria' => 'required|string|max:255',
            'keterangan_keluarga_pria' => 'required|string',
            'foto_pria' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'instagram_url_pria' => 'nullable|url|max:255',
            'tiktok_url_pria' => 'nullable|url|max:255',
            'x_url_pria' => 'nullable|url|max:255',
            'nama_panggilan_wanita' => 'required|string|max:255',
            'nama_lengkap_wanita' => 'required|string|max:255',
            'keterangan_keluarga_wanita' => 'required|string',
            'foto_wanita' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'instagram_url_wanita' => 'nullable|url|max:255',
            'tiktok_url_wanita' => 'nullable|url|max:255',
            'x_url_wanita' => 'nullable|url|max:255',
            'text_penutup' => 'required|string',

            // Template Undangan Pernikahan
            'tanggal_mulai_akad' => 'required|date',
            'waktu_mulai_akad' => 'required',
            'detail_lokasi_akad_nikah' => 'required|string',
            'lokasi_akad_nikah' => 'required|array',
            'lokasi_akad_nikah.lat' => 'required|numeric',
            'lokasi_akad_nikah.lng' => 'required|numeric',
            'doa_pengantinn_pria' => 'required|string',
            'doa_pengantin_wanita' => 'required|string',
            'no_rek_amplop' => 'nullable|string',
            'lokasi_pengiriman_kado' => 'nullable|string',

            // Acara
            'acaras' => 'required|array|min:1',
            'acaras.*.nama_acara' => 'required|string',
            'acaras.*.tanggal_acara' => 'required|date',
            'acaras.*.waktu_acara' => 'required',
            'acaras.*.detail_lokasi_acara' => 'required|string',
            'acaras.*.lokasi_acara' => 'required|array',
            'acaras.*.lokasi_acara.lat' => 'required|numeric',
            'acaras.*.lokasi_acara.lng' => 'required|numeric',

            // Gallery
            'galleries' => 'nullable|array',
            'galleries.*.file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',

            // Kisah Cinta
            'kisah_cintas' => 'required|array|min:1',
            'kisah_cintas.*.tanggal' => 'required|string',
            'kisah_cintas.*.judul' => 'required|string|max:255',
            'kisah_cintas.*.peristiwa' => 'required|string',
            'kisah_cintas.*.foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ];
    }

    /**
     * Get the validation error messages.
     */
    public static function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah digunakan.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat jpeg, png, webp atau jpg.',
            'max' => ':attribute maksimal 10 MB.',
            'url' => ':attribute harus berupa URL yang valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'min' => ':attribute minimal harus berisi :min item.',
        ];
    }

    /**
     * Get the validation attributes name.
     */
    public static function attributes(): array
    {
        return [
            'judul' => 'Judul Undangan',
            'url' => 'URL Undangan',
            'thumbnail' => 'Thumbnail',
            'salam_pembuka' => 'Salam Pembuka',
            'text_pembuka' => 'Text Pembuka',
            'video_youtube_url' => 'Link Video YouTube',
            'nama_panggilan_pria' => 'Nama Panggilan Pria',
            'nama_lengkap_pria' => 'Nama Lengkap Pria',
            'foto_pria' => 'Foto Pria',
            'nama_panggilan_wanita' => 'Nama Panggilan Wanita',
            'nama_lengkap_wanita' => 'Nama Lengkap Wanita',
            'foto_wanita' => 'Foto Wanita',
            'instagram_url_pria' => 'Instagram Pria',
            'tiktok_url_pria' => 'TikTok Pria',
            'x_url_pria' => 'Twitter/X Pria',
            'instagram_url_wanita' => 'Instagram Wanita',
            'tiktok_url_wanita' => 'TikTok Wanita',
            'x_url_wanita' => 'Twitter/X Wanita',
            'tanggal_mulai_akad' => 'Tanggal Akad',
            'waktu_mulai_akad' => 'Waktu Mulai Akad',
            'detail_lokasi_akad_nikah' => 'Detail Lokasi Akad',
            'acaras.*.nama_acara' => 'Nama Acara',
            'acaras.*.tanggal_acara' => 'Tanggal Acara',
            'acaras.*.waktu_acara' => 'Waktu Acara',
            'kisah_cintas.*.tanggal' => 'Tanggal Kisah Cinta',
            'kisah_cintas.*.judul' => 'Judul Kisah Cinta',
            'kisah_cintas.*.peristiwa' => 'Peristiwa Kisah Cinta',
        ];
    }
}
