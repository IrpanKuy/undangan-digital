# Panduan Pengembangan Fitur Template Content Undangan

--edit dan create

## Aturan Pengembangan Halaman Create dan Edit

untuk membuat create dan edit ini akan menggunakan table berikut

1. undangans
2. template_undangan_pernikahans yang terhubung dari undangans
3. data_mempelais yang terhubung dari undangans
4. gallery_undangans yang terhubung dari undangans
5. kisah_cintas yang terhubung dari undangans
6. acaras yang terhubung dari undangans

## isi form

terbagi menjadi 2 grid pada desktop dan 1 grid pada mobile dan terbagi menjadi 2 tab yaitu tab content dan tab pengaturan

--tab content

1. konten utama dari tabel undangans
    - judul
    - url dengan fitur cek url uniq atau tidak
    - thumbnail_path
    - salam_pembuka
    - text_pembuka

2. Data Mempelai dari tabel data_mempelais
    - nama_panggilan_pria
    - nama_lengkap_pria
    - keterangan_keluarga_pria
    - foto_pria_path
    - nama_panggilan_wanita
    - nama_lengkap_wanita
    - keterangan_keluarga_wanita
    - foto_wanita_path
    - text_penutup

3. Data Pernikahan dari template_undangan_pernikahans
    - tanggal_mulai_akad
    - waktu_mulai_akad
    - waktu_selesai_akad
    - detail_lokasi_akad_nikah
    - lokasi_akad_nikah
    - doa_pengantinn_pria
    - doa_pengantin_wanita
    - no_rek_amplop
    - lokasi_pengiriman_kado

4. Acara dari tabel acaras
    - nama_acara
    - waktu_acara
    - detail_lokasi_acara
    - lokasi_acara

5. Gallery Undangan dari tabel gallery_undangans (bisa lebih dari 1)
    - image_path

6. Kisah Cinta dari tabel kisah_cintas (bisa lebih dari 1)
    - tanggal
    - peristiwa
    - foto

7. Hadiah dari tabel template_undangan_pernikahans
    - no_rek_amplop
    - lokasi_pengiriman_kado

8. Video youtube dari tabel undangans
    - video_youtube_url

catatan:

- untuk kolom geography di lokasi akad nikah dan lokasi acara menggunakan leaflet js dengan fitur interaktif dengan drag and drop untuk menentukan latitude longitude, dan convert ke geojson yang bisa diterima kolom geography
- untuk foto menggunakan file uppload
- untuk video menggunakan url youtube
- untuk tanggal menggunakan date picker
- untuk waktu menggunakan time picker
- untuk doa menggunakan text
- untuk no_rek_amplop menggunakan text
- untuk lokasi_pengiriman_kado menggunakan text
- untuk video_youtube_url menggunakan input biasa

--tab pengaturan dari tabel pengaturan_undangans

1. reservation_form menggunakan toggle switch
2. komentar_undangan menggunakan toggle switch
3. jumlah_kehadiran menggunakan toggle switch
4. musik_undangan text input
