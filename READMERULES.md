#rules code

--Rules Query

1. ketika kamu akan melakukan query lihat terlebih dahulu model untuk nama dan relasi agar tidak ada miss
2. ketika ada query kompleks seperti filter search atau lainnya taruh kode query tersebut ke model yang terhubung

--rules Validation

1. selalu tambahkan custom text dalam bahasa indonesia untuk callback error validasi
2. jika validasi komples buat folder file yang sesuai dengan path di controllernya contoh file controller Controller/Admin/TemplateController maka buat path folder Admin/TemplateController

--rules Design(UI/UX)

1. selalu gunakan styling dari tailwind dan component dari vuetify yang konsisten dengan project ini
2. untuk component gunakan dari vuetify
3. jika suatu komponen vuetify sudah kuatur dalam file app.js/createVuetify.defaults maka gunakan component tersebut tanpa menambah styling lagi
4. untuk icon gunakan dari iconify
5. pelajari styling dari kode lain agar styling konsisten, bisa di skip jika kamu sudah mempelajarinya
6. desain fokus ke ux dan readibility dengan styling lebih flat
   --Rules Alert

7. Gunakan sweet alert modal jika suatu tindakan yang membutuhkan perhatian penuh atau konfirmasi berat seperti
   contoh:

- Konfirmasi Hapus (Destructive Action): "Apakah Anda yakin ingin menghapus data ini?" (Tombol merah/peringatan).
- Pesan Error Fatal: "Gagal menyambung ke server."
- Sukses Besar: Setelah user berhasil menyelesaikan proses panjang, seperti "Pembayaran Berhasil!".

2. Gunakan sweet alert toast jika suatu tindakan yang tidak membutuhkan perhatian penuh atau konfirmasi ringan.
   contoh:

- Pesan Sukses: "Data berhasil disimpan."
- Pesan Error: "Gagal menyimpan data."
- Pesan Peringatan: "Data akan segera kadaluarsa."

3. ketika input dan ketika kirim terjadi error validasi maka pindahkan screen ke lokasi input error dengan warna input merah

--rules tambahan

1. gunakan inertia untuk komunikasi vue dan laravel
