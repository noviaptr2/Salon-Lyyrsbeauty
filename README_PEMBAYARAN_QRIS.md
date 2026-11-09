# Update Pembayaran QRIS — LyyrsBeauty

## Alur booking
1. Pelanggan memilih layanan dan mengirim data booking.
2. Sistem menyimpan booking dengan status `menunggu_pembayaran`; nomor antrean belum diterbitkan.
3. Halaman pembayaran menampilkan total pembayaran berdasarkan harga layanan saat booking dibuat.
4. Pelanggan memindai QRIS resmi salon dan mengunggah bukti pembayaran.
5. Admin memeriksa bukti pembayaran di Dashboard Admin.
6. Jika disetujui, sistem mengubah status pembayaran menjadi `terverifikasi` dan membuat nomor antrean. Jika ditolak, pelanggan dapat mengunggah bukti baru.

## Pemasangan
1. Cadangkan folder project dan database terlebih dahulu.
2. Salin/ekstrak file update ke project Laravel yang digunakan. Jangan mengganti `.env` jika konfigurasi lokal berbeda.
3. Pastikan `.env` menunjuk ke database project yang benar.
4. Jalankan:
   ```bash
   php artisan migrate
   php artisan optimize:clear
   ```
5. Jalankan aplikasi:
   ```bash
   php artisan serve
   ```

## QRIS resmi
Letakkan gambar QRIS resmi milik salon di:
`public/images/qris-lyyora.png`

QRIS pada halaman ini adalah QRIS statis yang dipasang oleh pemilik salon. Nominal total ditampilkan dari harga layanan yang tersimpan saat booking dibuat. Pelanggan harus membayar nominal yang tertera. Sistem ini menggunakan verifikasi manual, bukan konfirmasi transaksi otomatis dari penyedia QRIS.

## Bukti pembayaran
Bukti pembayaran disimpan di `public/storage/bukti-pembayaran/`. Pastikan folder `public/storage` tersedia dan dapat ditulisi oleh PHP.

## Database
Migrasi baru menambahkan:
- `total_pembayaran`
- `payment_status`
- `payment_proof`
- `payment_verified_at`

Kolom `nomor_antrean` dibuat nullable agar booking yang belum dibayar belum memiliki nomor antrean. Data lama diberi status pembayaran default `terverifikasi` untuk mempertahankan antrean yang sudah ada.
