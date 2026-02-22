# Panduan Pengembangan Fitur Template Content Undangan

Halaman ini berfungsi untuk mengelola data konten default yang akan digunakan sebagai basis untuk setiap template undangan. Data ini diambil dari tabel `undangans` yang memiliki flag `for_template = true`.

## Struktur Halaman (`index.vue`)

### 1. Header & Layout

- Menggunakan `adminDashboardLayout` sebagai wrapper utama untuk konsistensi UI Admin.
- **Header Title**: Menampilkan Judul halaman dengan icon `mdi:book-open-page-variant-outline` (Iconify).

### 2. Tampilan Data (Card Grid)

Alih-alih menggunakan tabel linear, data ditampilkan dalam bentuk **Card Grid** yang responsif:

- **Desktop**: 4 kolom per baris (`lg="3"`).
- **Tablet/Laptop Kecil**: 3 kolom per baris (`md="4"`).
- **Tablet**: 2 kolom per baris (`sm="6"`).
- **Mobile**: 1 kolom per baris (`cols="12"`).

### 3. Elemen Setiap Kartu

Setiap kartu menampilkan informasi kunci berikut:

- **Thumbnail**: Gambar utama dari `thumbnail_path`. Jika kosong, akan menampilkan gambar placeholder.
- **Judul Undangan**: Nama identifikasi dari konten template tersebut.
- **Waktu Akad Nikah**: Menampilkan tanggal dan waktu akad nikah yang diambil melalui relasi `templateUndanganPernikahan`. Format tanggal menggunakan standard Indonesia (contoh: 22 Februari 2026).
- **Tombol Edit Konten**: Untuk mengelola isi teks, salam, dan video pada template.
- **Tombol Edit Setting Konten**: Untuk mengelola parameter teknis dan konfigurasi khusus konten tersebut.
- **Tombol Hapus**: Menghapus data konten template dengan konfirmasi keamanan.

## Logika Controller (`TemplateContentUndanganController.php`)

### `index()`

- Melakukan query ke model `Undangan` dengan eager loading relasi `templateUndanganPernikahan`.
- Memfilter data hanya yang memiliki `for_template = true`.
- Mengurutkan berdasarkan data terbaru (`latest()`).
- Mengirimkan data ke view melalui properti `templates`.

### `destroy($id)`

- Mencari data berdasarkan ID.
- Melakukan penghapusan permanen.
- Memberikan feedback sukses melalui session flash yang akan ditangkap oleh Toast di frontend.

## Aturan Pengembangan (Garis Besar)

1.  **UI/UX**: Menggabungkan kekuatan **Vuetify** untuk komponen (Cards, Buttons, Chips) dan **Tailwind CSS** untuk layout/spacing agar kode lebih bersih dan fleksibel.
2.  **Alerting**:
    - **SweetAlert Modal**: Digunakan untuk konfirmasi hapus data.
    - **SweetAlert Toast**: Digunakan untuk notifikasi sukses yang muncul di pojok kanan atas.
3.  **Localisation**: Seluruh teks antarmuka dan pesan validasi/feedback menggunakan Bahasa Indonesia sesuai standard project.
4.  **Responsive**: Pastikan tampilan card tetap proporsional di berbagai ukuran layar dengan menggunakan grid system yang tepat.
