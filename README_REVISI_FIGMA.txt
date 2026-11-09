REVISI TAMPILAN LYYRSBEAUTY BERDASARKAN DESIGN UKOM FIGMA

Yang diubah:
- Menambahkan public/css/figma-match.css untuk menyelaraskan warna, font, navbar,
  hero beranda, kartu layanan, form, kartu antrean, profil, dashboard admin, dan tabel.
- Menautkan stylesheet tersebut dari resources/views/layouts/app.blade.php.
- Menambahkan aturan responsive agar tampilan mobile menyusun ulang elemen yang sama.

Yang tidak diubah:
- Route, controller, model, migration, database, nama field, validasi, proses booking,
  pembayaran QRIS, upload bukti, verifikasi admin, dan status antrean.
- Semua teks/data Blade tetap memakai binding Laravel yang sudah ada.

Cara menerapkan:
1. Backup folder project lama.
2. Extract ZIP ini.
3. Gunakan folder prjctukmslon sebagai project pengganti atau salin file yang berubah:
   resources/views/layouts/app.blade.php
   public/css/figma-match.css
4. Jalankan: php artisan optimize:clear
5. Refresh browser dengan Ctrl+F5.

Catatan:
- Penyesuaian ini menyelaraskan design system dan responsivitas berdasarkan SVG Figma
  dan struktur kode yang ada. Karena SVG Figma mengekspor teks/elemen menjadi vector,
  tidak semua teks desain dapat dipetakan otomatis satu-per-satu ke komponen Blade.
