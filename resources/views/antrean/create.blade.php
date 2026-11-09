@extends('layouts.app')

@section('title', 'Daftar Antrean | LyyrsBeauty')

@section('content')

<div class="container">

<div class="form-card">

    <div class="section-heading" style="margin-bottom:28px;">
        <p style="color:#d96e96; font-weight:700;">REGISTRATION</p>

        <h2>Daftar Antrean</h2>

        <p>
            Isi data booking terlebih dahulu. Nomor antrean diterbitkan
            setelah pembayaran QRIS diverifikasi admin.
        </p>
    </div>

    {{-- INFORMASI OPERASIONAL --}}
    <div style="
        background:linear-gradient(135deg,#fff0f5,#fff8fa);
        border:1px solid #f3dce5;
        padding:18px;
        border-radius:14px;
        margin-bottom:22px;
    ">
        <h3 style="color:#c94f7c;margin-bottom:12px;">
            🌸 Informasi Antrean LyyrsBeauty
        </h3>

        <p style="margin-bottom:12px;">
            ⏰ <strong>Jam Operasional:</strong> 08.00–21.30 WIB
        </p>

        <p style="font-weight:700;margin-bottom:8px;">
            Kuota Maksimal Layanan per Hari:
        </p>

        <div style="display:grid;gap:8px;">

            <div style="display:flex;justify-content:space-between;background:white;padding:10px 13px;border-radius:9px;">
                <span>Hair Coloring</span>
                <strong style="color:#c94f7c;">8 antrean</strong>
            </div>

            <div style="display:flex;justify-content:space-between;background:white;padding:10px 13px;border-radius:9px;">
                <span>Hair Styling</span>
                <strong style="color:#c94f7c;">10 antrean</strong>
            </div>

            <div style="display:flex;justify-content:space-between;background:white;padding:10px 13px;border-radius:9px;">
                <span>Creambath</span>
                <strong style="color:#c94f7c;">12 antrean</strong>
            </div>

        </div>

        <small style="display:block;margin-top:12px;color:#987987;">
            Pendaftaran mengikuti ketersediaan kuota dan estimasi waktu
            pelayanan. Antrean yang dibatalkan tidak dihitung dalam kuota.
        </small>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <strong>Periksa kembali data berikut:</strong>

            <ul style="padding-left:20px;margin-top:8px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($layanans->isEmpty())

        <div class="alert alert-error">
            Belum ada layanan salon yang tersedia. Tambahkan data layanan
            terlebih dahulu sebelum mendaftar antrean.
        </div>

        <a href="{{ route('layanan.index') }}" class="btn">
            Kembali ke Layanan
        </a>

    @else

        <form action="{{ route('antrean.store') }}" method="POST">
            @csrf

            {{-- NAMA --}}
            <div class="form-group">
                <label for="nama_pelanggan">Nama Lengkap</label>

                <input
                    type="text"
                    id="nama_pelanggan"
                    name="nama_pelanggan"
                    value="{{ old('nama_pelanggan') }}"
                    placeholder="Masukkan nama lengkap"
                    maxlength="255"
                    required
                >
            </div>

            {{-- NOMOR HP --}}
            <div class="form-group">
                <label for="no_hp">Nomor HP</label>

                <input
                    type="tel"
                    id="no_hp"
                    name="no_hp"
                    value="{{ old('no_hp') }}"
                    placeholder="Contoh: 081234567890"
                    maxlength="20"
                    required
                >
            </div>

            {{-- PILIH LAYANAN --}}
            <div class="form-group">
                <label for="layanan_id">Pilih Layanan</label>

                <select id="layanan_id" name="layanan_id" required>
                    <option value="">-- Pilih layanan salon --</option>

                    @foreach($layanans as $layanan)

                        @php
                            $nama = strtolower(trim($layanan->nama_layanan));

                            $kuota = match(true) {
                                str_contains($nama, 'hair coloring') => 8,
                                str_contains($nama, 'hair styling') => 10,
                                str_contains($nama, 'creambath') => 12,
                                default => 0,
                            };
                        @endphp

                        <option
                            value="{{ $layanan->id }}"
                            data-harga="{{ $layanan->harga }}"
                            data-durasi="{{ $layanan->durasi }}"
                            data-kuota="{{ $kuota }}"
                            {{ old('layanan_id') == $layanan->id ? 'selected' : '' }}
                        >
                            {{ $layanan->nama_layanan }}
                            — Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                        </option>

                    @endforeach
                </select>
            </div>

            {{-- DETAIL LAYANAN --}}
            <div
                id="detail-layanan"
                style="
                    display:none;
                    background:#fff0f5;
                    padding:16px;
                    border-radius:12px;
                    margin-bottom:22px;
                "
            >
                <h4 style="color:#c94f7c;margin-bottom:10px;">
                    Detail Layanan
                </h4>

                <p>
                    💰 Harga:
                    <strong id="harga-layanan">-</strong>
                </p>

                <p>
                    ⏱️ Durasi:
                    <strong id="durasi-layanan">-</strong>
                </p>

                <p>
                    🎟️ Kuota Maksimal:
                    <strong id="kuota-layanan">-</strong>
                </p>
            </div>

            {{-- TOMBOL --}}
            <button type="submit" class="btn" style="width:100%;">
                Lanjut ke Pembayaran QRIS ♡
            </button>

        </form>

    @endif

</div>

</div>

<script>
    const pilihanLayanan = document.getElementById('layanan_id');
    const detailLayanan = document.getElementById('detail-layanan');

    const hargaLayanan = document.getElementById('harga-layanan');
    const durasiLayanan = document.getElementById('durasi-layanan');
    const kuotaLayanan = document.getElementById('kuota-layanan');

    function tampilkanDetailLayanan() {

        const pilihan = pilihanLayanan?.selectedOptions[0];

        if (!pilihan || !pilihan.value) {
            detailLayanan.style.display = 'none';
            return;
        }

        const harga = Number(pilihan.dataset.harga);
        const durasi = pilihan.dataset.durasi;
        const kuota = Number(pilihan.dataset.kuota);

        hargaLayanan.textContent = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(harga);

        durasiLayanan.textContent = durasi + ' menit';

        kuotaLayanan.textContent = kuota > 0
            ? kuota + ' antrean per hari'
            : 'Sesuai pengaturan layanan';

        detailLayanan.style.display = 'block';
    }

    pilihanLayanan?.addEventListener('change', tampilkanDetailLayanan);

    tampilkanDetailLayanan();
</script>

@endsection