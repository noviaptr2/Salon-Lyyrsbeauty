@extends('layouts.app')

@section('title', 'Daftar Antrean | LyyrsBeauty')

@section('content')

<section style="padding:48px 20px 30px;text-align:center;background:linear-gradient(135deg,#fff0f5,#fffaf8)">
    <p class="section-label">YOUR SALON JOURNEY</p>
    <h1 class="section-title">Daftar Antrean 🌸</h1>
    <p style="color:var(--muted)">Pantau jadwal perawatanmu dan kelola antrean yang masih menunggu.</p>

    <form action="{{ route('antrean.index') }}" method="GET"
          style="display:flex;gap:10px;max-width:650px;margin:22px auto;flex-wrap:wrap">

        <input class="form-control"
               style="flex:1;min-width:180px;border-radius:30px"
               type="search"
               name="q"
               value="{{ $query }}"
               placeholder="Cari nomor antrean atau nama...">

        <button class="btn">Cari</button>

        <a class="btn-cancel" href="{{ route('antrean.index') }}">Reset</a>
    </form>

    @if(auth()->user()->role !== 'admin')
        <a class="btn" href="{{ route('antrean.create') }}">＋ Buat Antrean Baru</a>
    @endif
</section>

<div class="container" style="padding-top:35px">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- JIKA BELUM ADA ANTREAN --}}
    @if($antreans->isEmpty())

        <div class="form-card" style="text-align:center;padding:45px">
            <div style="font-size:50px">🌷</div>
            <h2>Belum ada antrean</h2>

            <p style="color:var(--muted);margin:10px 0 20px">
                Antrean kamu akan tampil di sini setelah pendaftaran.
            </p>

            @if(auth()->user()->role !== 'admin')
                <a class="btn" href="{{ route('antrean.create') }}">
                    Daftar Sekarang
                </a>
            @endif
        </div>

    @else

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px">

            @foreach($antreans as $antrean)

                <article style="background:#fff;border:1px solid var(--border);border-radius:22px;padding:23px;box-shadow:0 12px 35px rgba(151,76,106,.07)">

                    {{-- HEADER KARTU --}}
                    <div style="display:flex;justify-content:space-between;gap:10px;align-items:start">

                        <span style="font-size:21px;font-weight:700;color:var(--pink-dark)">
                            {{ $antrean->nomor_antrean ?? 'Menunggu pembayaran' }}
                        </span>

                        <span style="padding:5px 12px;border-radius:20px;
                            background:{{ $antrean->status === 'selesai' ? '#e5f8eb' : ($antrean->status === 'batal' ? '#ffe7e7' : '#fff0c9') }};
                            font-size:12px;font-weight:700">

                            {{ ucfirst($antrean->status) }}

                        </span>
                    </div>

                    {{-- INFORMASI CUSTOMER --}}
                    <h3 style="margin-top:12px">
                        {{ $antrean->nama_pelanggan }}
                    </h3>

                    <p style="color:var(--muted)">
                        {{ $antrean->layanan?->nama_layanan ?? 'Layanan tidak tersedia' }}
                    </p>

                    {{-- TANGGAL ANTREAN --}}
                    <p style="margin-top:10px">
                        📅 {{ $antrean->tanggal_antrean?->format('d M Y') ?? '-' }}
                    </p>

                    {{-- PERKIRAAN JAM PELAYANAN --}}
                    @if($antrean->payment_status === 'terverifikasi' && $antrean->status !== 'batal')

                        <div style="
                            background:#fff0f5;
                            border:1px solid #f3dce5;
                            border-radius:14px;
                            padding:14px;
                            margin:15px 0;
                        ">

                            <p style="
                                color:#d96e96;
                                font-size:12px;
                                font-weight:700;
                                margin-bottom:6px;
                            ">
                                ⏰ PERKIRAAN JAM PELAYANAN
                            </p>

                            <div style="
                                font-size:25px;
                                font-weight:700;
                                color:#c94f7c;
                            ">
                                {{ $antrean->perkiraan_jam ?? '-' }} WIB
                            </div>

                            <small style="color:#987987">
                                Waktu pelayanan dihitung berdasarkan antrean sebelumnya.
                            </small>

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
                                'pesan' => 'Silakan selesaikan pembayaran QRIS.'
                            ],

                            'menunggu_verifikasi' => [
                                'label' => 'Menunggu Verifikasi',
                                'warna' => '#245c9c',
                                'background' => '#eaf3ff',
                                'ikon' => '🔎',
                                'pesan' => 'Bukti pembayaran sedang diperiksa admin.'
                            ],

                            'ditolak' => [
                                'label' => 'Pembayaran Ditolak',
                                'warna' => '#b4233d',
                                'background' => '#ffe9ed',
                                'ikon' => '✕',
                                'pesan' => 'Silakan periksa dan unggah ulang bukti pembayaran.'
                            ],

                            'terverifikasi' => [
                                'label' => 'Terverifikasi',
                                'warna' => '#18794e',
                                'background' => '#e6f7ed',
                                'ikon' => '✓',
                                'pesan' => 'Pembayaran berhasil dikonfirmasi oleh admin.'
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
                        gap:7px;
                        padding:13px 15px;
                        margin-top:14px;
                        margin-bottom:12px;
                        border-radius:13px;
                        background:{{ $infoPembayaran['background'] }};
                        border:1px solid {{ $infoPembayaran['warna'] }}33;
                    ">

                        <div style="
                            display:flex;
                            align-items:center;
                            gap:7px;
                            font-size:13px;
                            font-weight:700;
                            color:{{ $infoPembayaran['warna'] }};
                        ">
                            <span>{{ $infoPembayaran['ikon'] }}</span>
                            STATUS PEMBAYARAN
                        </div>

                        <strong style="
                            font-size:16px;
                            color:{{ $infoPembayaran['warna'] }};
                        ">
                            {{ $infoPembayaran['label'] }}
                        </strong>

                        <small style="color:#66545c;line-height:1.5;">
                            {{ $infoPembayaran['pesan'] }}
                        </small>

                    </div>

                    {{-- TOTAL PEMBAYARAN --}}
                    <p style="font-size:20px;color:var(--pink-dark);font-weight:700;margin:8px 0 18px">
                        Rp {{ number_format($antrean->total_pembayaran ?? $antrean->layanan?->harga ?? 0, 0, ',', '.') }}
                    </p>

                    {{-- TOMBOL AKSI --}}
                    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">

                        @if($antrean->payment_status === 'terverifikasi' && $antrean->status !== 'batal')

                            <a class="btn btn-outline" href="{{ route('antrean.show', $antrean) }}">
                                Detail
                            </a>

                        @elseif($antrean->status === 'batal')

                            <span style="font-size:13px;color:#c23b5a;font-weight:600">
                                Booking dibatalkan
                            </span>

                        @else

                            <a class="btn btn-outline" href="{{ route('antrean.payment', $antrean) }}">
                                Detail
                            </a>

                        @endif

                        {{-- BOOKING DIBATALKAN --}}
                        @if($antrean->status === 'batal')

                            <span style="font-size:13px;color:#c23b5a">
                                Pesanan sudah dibatalkan.
                            </span>

                        {{-- ADMIN --}}
                        @elseif(auth()->user()->role === 'admin')

                            @if($antrean->payment_status === 'menunggu_verifikasi')
                                <span style="font-size:13px;color:var(--muted)">
                                    Menunggu verifikasi pembayaran
                                </span>
                            @endif

                            <form method="POST"
                                  action="{{ route('admin.antrean.status', $antrean) }}"
                                  style="display:flex;gap:6px;flex-wrap:wrap">

                                @csrf
                                @method('PATCH')

                                <select name="status"
                                        class="form-control"
                                        style="width:auto;padding:8px"
                                        aria-label="Status">

                                    @foreach(['menunggu','dilayani','selesai','batal'] as $status)
                                        <option value="{{ $status }}"
                                            @selected($antrean->status === $status)>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach

                                </select>

                                <button class="btn">Update</button>
                            </form>

                        {{-- CUSTOMER --}}
                        @else

                            {{-- PEMBAYARAN BELUM DIVERIFIKASI --}}
                            @if($antrean->payment_status !== 'terverifikasi')

                                <a class="btn"
                                   href="{{ route('antrean.payment', $antrean) }}">

                                    {{ $antrean->payment_status === 'menunggu_verifikasi'
                                        ? 'Lihat Pembayaran'
                                        : 'Lanjutkan Pembayaran' }}

                                </a>

                                {{-- EDIT BOOKING --}}
                                @if(in_array($antrean->payment_status, ['menunggu_pembayaran', 'ditolak']))

                                    <a class="btn btn-outline"
                                       href="{{ route('antrean.edit', $antrean) }}">
                                        Edit Booking
                                    </a>

                                @endif

                            @else

                                {{-- ANTREAN SUDAH DIBAYAR --}}
                                @if($antrean->status !== 'selesai')

                                    <a class="btn btn-outline"
                                       href="{{ route('antrean.show', $antrean) }}">
                                        Lihat Nomor
                                    </a>

                                @endif

                            @endif

                            {{-- TOMBOL BATAL PESANAN --}}
                            @if(
                                !in_array($antrean->status, ['batal', 'dilayani', 'selesai']) &&
                                !in_array($antrean->payment_status, ['terverifikasi', 'menunggu_verifikasi'])
                            )

                                <form method="POST"
                                      action="{{ route('antrean.cancel', $antrean) }}"
                                      onsubmit="return confirm('Yakin ingin membatalkan booking ini?')"
                                      style="display:inline-flex">

                                    @csrf

                                    <button type="submit"
                                            class="btn btn-outline"
                                            style="border-color:#dc6b83;color:#c23b5a">

                                        ✕ Batalkan

                                    </button>

                                </form>

                            @endif

                        @endif

                    </div>

                </article>

            @endforeach

        </div>

    @endif

</div>

@endsection