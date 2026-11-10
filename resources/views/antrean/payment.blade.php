@extends('layouts.app')

@section('title', 'Pembayaran QRIS | LyyrsBeauty')

@section('content')

<div class="container" style="max-width:850px">
    <div class="form-card">

        {{-- HEADER --}}
        <div style="text-align:center;margin-bottom:26px">
            <p class="section-label">SECURE YOUR BOOKING</p>
            <h1 class="section-title">Pembayaran QRIS 💗</h1>
            <p style="color:var(--muted)">
                Selesaikan pembayaran sesuai nominal di bawah.
                Nomor antrean akan diberikan setelah admin memverifikasi pembayaran.
            </p>
        </div>

        {{-- NOTIFIKASI --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- STATUS PEMBAYARAN --}}
        @php
            $statusPembayaran = $antrean->payment_status ?? 'menunggu_pembayaran';

            $infoPembayaran = match ($statusPembayaran) {
                'menunggu_pembayaran' => [
                    'label' => 'Belum Dibayar',
                    'warna' => '#9a6700',
                    'background' => '#fff4d6',
                    'ikon' => '💳',
                    'pesan' => 'Silakan lakukan pembayaran melalui QRIS dan unggah bukti pembayaran.'
                ],

                'menunggu_verifikasi' => [
                    'label' => 'Menunggu Verifikasi',
                    'warna' => '#245c9c',
                    'background' => '#eaf3ff',
                    'ikon' => '🔎',
                    'pesan' => 'Bukti pembayaran sudah diterima dan sedang diperiksa oleh admin.'
                ],

                'ditolak' => [
                    'label' => 'Pembayaran Ditolak',
                    'warna' => '#b4233d',
                    'background' => '#ffe9ed',
                    'ikon' => '✕',
                    'pesan' => 'Pembayaran ditolak. Silakan periksa bukti pembayaran dan unggah ulang.'
                ],

                'terverifikasi' => [
                    'label' => 'Terverifikasi',
                    'warna' => '#18794e',
                    'background' => '#e6f7ed',
                    'ikon' => '✓',
                    'pesan' => 'Pembayaran berhasil dikonfirmasi oleh admin. Nomor antrean sudah dapat dilihat.'
                ],

                default => [
                    'label' => 'Status Tidak Diketahui',
                    'warna' => '#666',
                    'background' => '#f2f2f2',
                    'ikon' => '•',
                    'pesan' => 'Status pembayaran sedang diperbarui.'
                ],
            };
        @endphp

        <div style="
            display:flex;
            flex-direction:column;
            gap:8px;
            padding:17px 18px;
            margin-bottom:24px;
            border-radius:16px;
            background:{{ $infoPembayaran['background'] }};
            border:1px solid {{ $infoPembayaran['warna'] }}44;
        ">

            <div style="
                display:flex;
                align-items:center;
                gap:8px;
                font-size:13px;
                font-weight:700;
                color:{{ $infoPembayaran['warna'] }};
            ">
                <span>{{ $infoPembayaran['ikon'] }}</span>
                STATUS PEMBAYARAN
            </div>

            <strong style="
                font-size:19px;
                color:{{ $infoPembayaran['warna'] }};
            ">
                {{ $infoPembayaran['label'] }}
            </strong>

            <p style="font-size:13px;color:#66545c;line-height:1.6;margin:0">
                {{ $infoPembayaran['pesan'] }}
            </p>

        </div>

        {{-- TOTAL PEMBAYARAN --}}
        <div style="
            background:#fff0f5;
            border:1px solid #f1d3df;
            border-radius:22px;
            padding:24px;
            margin-bottom:24px;
        ">

            <p style="
                font-size:13px;
                color:var(--muted);
                font-weight:700;
                letter-spacing:1px;
            ">
                TOTAL YANG HARUS DIBAYAR
            </p>

            <div style="
                font-size:clamp(32px,6vw,46px);
                font-weight:800;
                color:var(--pink-dark);
                line-height:1.3;
                margin:8px 0 12px;
            ">
                Rp {{ number_format((float) ($antrean->total_pembayaran ?? $antrean->layanan->harga), 0, ',', '.') }}
            </div>

            <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap">
                <span>
                    <strong>Layanan:</strong>
                    {{ $antrean->layanan->nama_layanan }}
                </span>

                <span>
                    <strong>Durasi:</strong>
                    {{ $antrean->layanan->durasi }} menit
                </span>
            </div>

        </div>

        {{-- INFORMASI STATUS --}}
        @if($antrean->payment_status === 'menunggu_verifikasi')

            <div class="alert alert-success">
                Bukti pembayaran sudah dikirim dan sedang menunggu verifikasi admin.
                Nomor antrean belum diterbitkan.
            </div>

        @elseif($antrean->payment_status === 'ditolak')

            <div class="alert alert-error">
                Bukti pembayaran sebelumnya ditolak.
                Silakan unggah ulang bukti pembayaran yang benar.
            </div>

        @elseif($antrean->payment_status === 'terverifikasi')

            <div class="alert alert-success">
                Pembayaran berhasil diverifikasi oleh admin.
                Terima kasih sudah melakukan pembayaran di LyyrsBeauty! 💗
            </div>

        @endif

        {{-- QRIS DAN CARA PEMBAYARAN --}}
        <div style="
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:22px;
            align-items:start;
        ">

            {{-- GAMBAR QRIS --}}
            <div style="
                text-align:center;
                border:1px solid var(--border);
                border-radius:18px;
                padding:20px;
            ">

                <h3 style="margin-bottom:12px">Scan QRIS</h3>

                @if(file_exists(public_path('images/qris-lyyora.png')))

                    <img
                        src="{{ asset('images/qris-lyyora.png') }}"
                        alt="QRIS resmi LyyrsBeauty"
                        style="
                            display:block;
                            width:100%;
                            max-width:300px;
                            max-height:360px;
                            object-fit:contain;
                            margin:0 auto 12px;
                            border-radius:12px;
                        "
                    >

                    <p style="font-size:13px;color:var(--muted)">
                        Pastikan nominal pembayaran di aplikasi kamu
                        sama dengan jumlah yang tertera di atas.
                    </p>

                @else

                    <div style="
                        background:#fff8fb;
                        border:1px dashed #d96b91;
                        border-radius:14px;
                        padding:28px 16px;
                        margin-bottom:12px;
                    ">
                        <div style="font-size:45px">▦</div>

                        <strong>QRIS salon belum dipasang</strong>

                        <p style="font-size:13px;color:var(--muted);margin-top:8px">
                            Letakkan gambar QRIS resmi salon di
                            <code>public/images/qris-lyyora.png</code>.
                        </p>
                    </div>

                    <p style="font-size:13px;color:var(--muted)">
                        Gunakan hanya QRIS resmi milik salon.
                        Jangan unggah bukti pembayaran sebelum pembayaran benar-benar berhasil.
                    </p>

                @endif

            </div>

            {{-- CARA PEMBAYARAN --}}
            <div>

                <h3 style="margin-bottom:12px">Cara Pembayaran</h3>

                <ol style="
                    padding-left:20px;
                    color:var(--muted);
                    margin-bottom:22px;
                ">
                    <li>Buka aplikasi mobile banking atau e-wallet yang mendukung QRIS.</li>

                    <li>Scan QRIS resmi salon.</li>

                    <li>
                        Bayar tepat sebesar
                        <strong style="color:var(--pink-dark)">
                            Rp {{ number_format((float) ($antrean->total_pembayaran ?? $antrean->layanan->harga), 0, ',', '.') }}
                        </strong>.
                    </li>

                    <li>Simpan bukti pembayaran setelah transaksi berhasil.</li>

                    <li>Unggah bukti pembayaran di bawah.</li>
                </ol>

                {{-- BUKTI PEMBAYARAN --}}
                @if($antrean->payment_proof)

                    <div style="margin-bottom:16px">

                        <p style="font-weight:700;margin-bottom:8px">
                            Bukti yang sudah diunggah
                        </p>

                        <a
                            href="{{ asset('storage/' . $antrean->payment_proof) }}"
                            target="_blank"
                            rel="noopener"
                        >
                            <img
                                src="{{ asset('storage/' . $antrean->payment_proof) }}"
                                alt="Bukti pembayaran"
                                style="
                                    width:100%;
                                    max-width:260px;
                                    border-radius:12px;
                                    border:1px solid var(--border);
                                "
                            >
                        </a>

                    </div>

                @endif

                {{-- FORM UPLOAD BUKTI --}}
                @if(!in_array($antrean->payment_status, ['menunggu_verifikasi', 'terverifikasi']))

                    <form
                        method="POST"
                        action="{{ route('antrean.payment.proof', $antrean) }}"
                        enctype="multipart/form-data"
                    >

                        @csrf

                        <div class="form-group">

                            <label for="payment_proof">
                                Upload Bukti Pembayaran
                            </label>

                            <input
                                class="form-control"
                                id="payment_proof"
                                type="file"
                                name="payment_proof"
                                accept="image/png,image/jpeg,image/webp"
                                required
                            >

                            <small style="
                                display:block;
                                color:var(--muted);
                                margin-top:6px;
                            ">
                                Format JPG, PNG, atau WEBP. Maksimal 4 MB.
                            </small>

                        </div>

                        <button
                            class="btn"
                            type="submit"
                            style="width:100%"
                        >
                            Kirim Bukti Pembayaran
                        </button>

                    </form>

                @elseif($antrean->payment_status === 'menunggu_verifikasi')

                    <div style="
                        background:#eaf3ff;
                        padding:14px;
                        border-radius:12px;
                        color:#245c9c;
                        font-size:13px;
                    ">
                        🔎 Bukti pembayaran sedang diperiksa.
                        Tunggu konfirmasi dari admin.
                    </div>

                @elseif($antrean->payment_status === 'terverifikasi')

                    <div style="
                        background:#e6f7ed;
                        padding:14px;
                        border-radius:12px;
                        color:#18794e;
                        font-size:13px;
                    ">
                        ✓ Pembayaran sudah terverifikasi.
                        Kamu tidak perlu mengunggah bukti lagi.
                    </div>

                @endif

            </div>

        </div>

        {{-- TOMBOL NAVIGASI --}}
        <div style="
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            margin-top:26px;
        ">

            <a class="btn btn-outline" href="{{ route('antrean.index') }}">
                Kembali ke Booking
            </a>

            @if($antrean->payment_status === 'terverifikasi')

                <a class="btn" href="{{ route('antrean.show', $antrean) }}">
                    Lihat Nomor Antrean
                </a>

            @endif

        </div>

    </div>
</div>

@endsection