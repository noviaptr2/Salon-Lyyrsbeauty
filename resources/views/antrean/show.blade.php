@extends('layouts.app')

@section('title', 'Nomor Antrean | LyyrsBeauty')

@section('content')
<div class="container">
    <div class="form-card" style="text-align:center">
        <div style="font-size:65px;margin-bottom:10px">🌸</div>

        <p style="color:#d96e96;font-weight:700">
            PEMBAYARAN TERVERIFIKASI
        </p>

        <h1 style="font-family:'Playfair Display',serif;font-size:32px;margin:10px 0">
            Terima Kasih, {{ $antrean->nama_pelanggan }}!
        </h1>

        <p style="color:#8c7780">
            Pembayaran kamu sudah dikonfirmasi. Berikut nomor antrean yang bisa disimpan.
        </p>

        <div style="background:#fff0f5;border-radius:20px;padding:25px;margin:28px 0">
            <p style="font-weight:600">Nomor Antrean Kamu</p>

            <div class="queue-number">
                {{ $antrean->nomor_antrean }}
            </div>

            <p style="color:#8c7780;font-size:13px">
                Tunjukkan nomor ini kepada petugas salon saat datang.
            </p>
        </div>

        <div style="text-align:left;background:#fffafb;border:1px solid #f3dce5;padding:22px;border-radius:16px">

            <p style="margin-bottom:12px">
                <strong>Nama:</strong> {{ $antrean->nama_pelanggan }}
            </p>

            <p style="margin-bottom:12px">
                <strong>Layanan:</strong> {{ $antrean->layanan->nama_layanan }}
            </p>

            <p style="margin-bottom:12px">
                <strong>Total dibayar:</strong>
                Rp {{ number_format((float) ($antrean->total_pembayaran ?? $antrean->layanan->harga), 0, ',', '.') }}
            </p>

            <p style="margin-bottom:12px">
                <strong>Metode pembayaran:</strong> QRIS
            </p>

            <p style="margin-bottom:12px">
                <strong>Tanggal antrean:</strong>
                {{ $antrean->tanggal_antrean->format('d-m-Y') }}
            </p>

            <p style="margin-bottom:12px">
                <strong>Durasi:</strong>
                {{ $antrean->layanan->durasi }} menit
            </p>

            {{-- PERKIRAAN JAM PELAYANAN --}}
            <div style="background:#fff0f5;border:1px solid #f3dce5;border-radius:12px;padding:15px;margin:18px 0">
                <p style="color:#d96e96;font-weight:700;margin-bottom:6px">
                    ⏰ PERKIRAAN JAM PELAYANAN
                </p>

                <div style="font-size:25px;font-weight:700;color:#c94f7c">
                    {{ $antrean->perkiraan_jam ?? '-' }} WIB
                </div>

                <small style="color:#8c7780">
                    Waktu pelayanan diperkirakan berdasarkan durasi antrean sebelumnya.
                </small>
            </div>

            <p style="margin-bottom:12px">
                <strong>Status pembayaran:</strong>
                <span style="color:#216c3a;font-weight:700">
                    Terverifikasi
                </span>
            </p>

            <p>
                <strong>Status antrean:</strong>
                {{ ucfirst($antrean->status) }}
            </p>
        </div>

        <p style="color:#8c7780;margin:22px 0">
            Simpan halaman ini sebagai bukti nomor antreanmu.
        </p>

        <a href="{{ route('home') }}" class="btn">
            Kembali ke Beranda
        </a>

        <a href="{{ route('antrean.index') }}" class="btn-outline" style="margin-left:8px">
            Lihat Booking
        </a>
    </div>
</div>
@endsection