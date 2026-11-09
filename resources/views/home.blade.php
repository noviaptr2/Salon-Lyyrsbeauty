
@extends('layouts.app')

@section('title', 'LyyrsBeauty | Beauty & Hair Studio')

@push('styles')
<style>
    /* =====================================
       HERO SECTION
    ===================================== */

    .hero {
        position: relative;
        isolation: isolate;
        background: #fff0f5;
        overflow: hidden;
    }

    .hero::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -1;
        background:
            linear-gradient(
                90deg,
                rgba(255,250,248,.97),
                rgba(255,240,245,.88),
                rgba(255,240,245,.58)
            ),
            url('/images/salon-home.jpg') center/cover no-repeat;
    }

    .hero-inner {
        max-width: 900px;
        margin: auto;
        padding: 65px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 22px;
    }

    .hero-content {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .hero-badge {
        display: inline-block;
        padding: 8px 15px;
        border-radius: 30px;
        background: white;
        color: var(--pink-dark);
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 20px;
        border: 1px solid var(--border);
    }

    .hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(38px, 5vw, 64px);
        line-height: 1.13;
        margin-bottom: 20px;
    }

    .hero h1 span {
        color: var(--pink-dark);
        font-style: italic;
    }

    .hero-description {
        color: var(--muted);
        font-size: 15px;
        line-height: 1.9;
        max-width: 650px;
        margin-bottom: 15px;
    }

    /* =====================================
       FOTO SALON - POSISI TENGAH
    ===================================== */

    .hero-image-wrap {
        position: relative;
        width: 100%;
        max-width: 750px;
        min-height: 380px;
        margin: 15px auto 10px;
        align-self: center;
        border-radius: 180px 180px 24px 24px;
        overflow: hidden;
        background:
            linear-gradient(
                180deg,
                rgba(255,240,245,.10),
                rgba(85,35,58,.20)
            ),
            url('/images/salon-home.jpg') center/cover no-repeat;
        box-shadow: 0 25px 65px rgba(151,76,106,.18);
    }

    .hero-image-overlay {
        position: absolute;
        inset: 0;
    }

    /* =====================================
       LOGO CARD
    ===================================== */

    .logo-card {
        position: absolute;
        bottom: 20px;
        left: 0;
        background: rgba(255,255,255,.96);
        backdrop-filter: blur(8px);
        padding: 12px 16px;
        border-radius: 0 22px 22px 0;
        box-shadow: 0 12px 35px rgba(100,50,70,.12);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 260px;
        z-index: 5;
        text-align: left;
    }

    .logo-card img {
        display: block;
        width: 65px;
        height: 65px;
        object-fit: contain;
        object-position: center;
        flex-shrink: 0;
    }

    .logo-card strong {
        display: block;
        font-family: 'Playfair Display', serif;
        color: var(--pink-dark);
        font-size: 16px;
    }

    .logo-card small {
        color: var(--muted);
        font-size: 11px;
    }

    /* =====================================
       HERO BUTTONS
    ===================================== */

    .hero-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .hero-note {
        margin-top: 15px;
        font-size: 13px;
        color: var(--muted);
        text-align: center;
    }

    /* =====================================
       BENEFITS
    ===================================== */

    .benefits {
        display: grid;
        grid-template-columns: repeat(3,1fr);
        gap: 22px;
        margin-top: 38px;
    }

    .benefit {
        padding: 25px;
        background: white;
        border: 1px solid var(--border);
        border-radius: 18px;
    }

    .benefit-icon {
        font-size: 28px;
        margin-bottom: 12px;
    }

    .benefit h3 {
        font-size: 16px;
        margin-bottom: 7px;
    }

    .benefit p {
        color: var(--muted);
        font-size: 13px;
    }

    /* =====================================
       SERVICE SECTION
    ===================================== */

    .service-section {
        background: white;
    }

    .service-heading {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .service-grid {
        display: grid;
        grid-template-columns: repeat(3,minmax(0,1fr));
        gap: 24px;
    }

    .service-card {
        background: var(--cream);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        transition: .25s;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(151,76,106,.10);
    }

    .service-photo {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background: var(--pink-light);
    }

    .service-content {
        padding: 22px;
    }

    .service-content h3 {
        font-family: 'Playfair Display',serif;
        font-size: 22px;
        margin-bottom: 9px;
    }

    .service-content p {
        color: var(--muted);
        font-size: 13px;
        min-height: 45px;
    }

    .service-meta {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        align-items: center;
        margin: 18px 0;
        font-size: 13px;
    }

    .service-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--pink-dark);
    }

    .service-duration {
        color: var(--muted);
    }

    /* =====================================
       CTA SECTION
    ===================================== */

    .cta-section {
        margin: 65px 24px;
        padding: 55px 25px;
        border-radius: 28px;
        background: linear-gradient(120deg,#d96b91,#b77ba8);
        text-align: center;
        color: white;
    }

    .cta-section h2 {
        font-family: 'Playfair Display',serif;
        font-size: clamp(28px,4vw,42px);
        margin-bottom: 14px;
    }

    .cta-section p {
        max-width: 550px;
        margin: 0 auto 25px;
        color: #fff4f8;
    }

    .cta-section .btn {
        background: white;
        color: var(--pink-dark);
    }

    /* =====================================
       RESPONSIVE TABLET
    ===================================== */

    @media(max-width:850px) {

        .hero-inner {
            padding: 45px 22px;
            gap: 18px;
        }

        .hero-image-wrap {
            width: 100%;
            max-width: 650px;
            min-height: 340px;
        }

        .benefits {
            grid-template-columns: 1fr;
        }

        .service-grid {
            grid-template-columns: repeat(2,minmax(0,1fr));
        }
    }

    /* =====================================
       RESPONSIVE MOBILE
    ===================================== */

    @media(max-width:550px) {

        .hero-inner {
            padding: 38px 18px;
            gap: 16px;
        }

        .hero h1 {
            font-size: 36px;
        }

        .hero-description {
            font-size: 13px;
        }

        .hero-image-wrap {
            min-height: 270px;
            border-radius: 120px 120px 18px 18px;
        }

        .logo-card {
            min-width: 220px;
            padding: 9px 12px;
        }

        .logo-card img {
            width: 55px;
            height: 55px;
        }

        .hero-buttons {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr;
        }

        .hero-buttons .btn {
            width: 100%;
        }

        .hero-note {
            font-size: 11px;
        }

        .service-grid {
            grid-template-columns: 1fr;
        }

        .service-photo {
            height: 230px;
        }

        .cta-section {
            margin: 35px 18px;
            padding: 35px 18px;
        }
    }

</style>
@endpush

@section('content')

{{-- =====================================
     HERO SECTION
===================================== --}}

<section class="hero">

    <div class="hero-inner">

        <div class="hero-content">

            <div class="hero-badge">
                ✨ YOUR BEAUTY, OUR PASSION
            </div>

            <h1>
                Saatnya Tampil<br>
                Lebih <span>Cantik</span><br>
                dan Percaya Diri.
            </h1>

            <p class="hero-description">
                Selamat datang di LyyrsBeauty, tempat untuk merawat
                dan mempercantik rambutmu dengan pelayanan yang nyaman.
                Pilih layanan favoritmu dan pesan antrean dengan mudah
                melalui aplikasi kami.
            </p>

            {{-- FOTO SALON DI TENGAH --}}

            <div class="hero-image-wrap">

                <div class="hero-image-overlay"
                     role="img"
                     aria-label="Suasana salon kecantikan">
                </div>

                <div class="logo-card">

                    <img
                        src="{{ asset('images/logo-salon.png') }}"
                        alt="Logo LyyrsBeauty"
                        onerror="this.style.display='none'"
                    >

                    <div>
                        <strong>LyyrsBeauty</strong>
                        <small>Beauty & Hair Studio</small>
                    </div>

                </div>

            </div>

            {{-- TOMBOL BOOKING --}}

            <div class="hero-buttons">

                <a href="{{ route(auth()->check() ? 'antrean.create' : 'login') }}"
                   class="btn">
                    Booking Sekarang →
                </a>

                <a href="{{ route('layanan.index') }}"
                   class="btn btn-outline">
                    Lihat Layanan
                </a>

            </div>

            <p class="hero-note">
                ♡ Perawatan rambut · Kecantikan · Kenyamanan
            </p>

        </div>

    </div>

</section>

{{-- =====================================
     BENEFITS SECTION
===================================== --}}

<section class="container">

    <div class="section-label">
        Why Choose Us?
    </div>

    <h2 class="section-title">
        Perawatan yang Membuatmu<br>
        Merasa Lebih Istimewa
    </h2>

    <p class="section-description">
        Kami ingin memberikan pengalaman perawatan yang praktis,
        nyaman, dan menyenangkan. Dengan sistem antrean online,
        kamu dapat mendaftar tanpa harus mengantre untuk melakukan
        pendaftaran secara langsung.
    </p>

    <div class="benefits">

        <div class="benefit">
            <div class="benefit-icon">💗</div>
            <h3>Pelayanan Ramah</h3>
            <p>
                Memberikan pengalaman perawatan yang nyaman
                dan menyenangkan untuk pelanggan.
            </p>
        </div>

        <div class="benefit">
            <div class="benefit-icon">✂️</div>
            <h3>Pilihan Perawatan</h3>
            <p>
                Tersedia pilihan layanan rambut yang bisa
                disesuaikan dengan kebutuhanmu.
            </p>
        </div>

        <div class="benefit">
            <div class="benefit-icon">📱</div>
            <h3>Antrean Online</h3>
            <p>
                Daftar antrean dan periksa statusnya melalui
                website dengan lebih praktis.
            </p>
        </div>

    </div>

</section>

{{-- =====================================
     SERVICE SECTION
===================================== --}}

<section class="service-section">

    <div class="container">

        <div class="service-heading">

            <div>
                <div class="section-label">
                    Our Services
                </div>

                <h2 class="section-title">
                    Layanan Favorit
                </h2>

                <p class="section-description"
                   style="margin-bottom:0;">

                    Temukan perawatan yang cocok untuk
                    membuat rambutmu tampil lebih menarik.

                </p>
            </div>

            <a href="{{ route('layanan.index') }}"
               class="btn btn-outline">
                Semua Layanan →
            </a>

        </div>

        <div class="service-grid">

            @forelse ($layanans as $layanan)

                <article class="service-card">

                    @if ($layanan->foto)

                        <img
                            src="{{ asset('storage/' . $layanan->foto) }}"
                            alt="{{ $layanan->nama_layanan }}"
                            class="service-photo"
                        >

                    @else

                        <div class="service-photo"
                             style="display:flex;align-items:center;justify-content:center;font-size:55px;">
                            ✂️
                        </div>

                    @endif

                    <div class="service-content">

                        <h3>{{ $layanan->nama_layanan }}</h3>

                        <p>{{ $layanan->deskripsi }}</p>

                        <div class="service-meta">

                            <span class="service-price">
                                Rp{{ number_format($layanan->harga,0,',','.') }}
                            </span>

                            <span class="service-duration">
                                {{ $layanan->durasi }} menit
                            </span>

                        </div>

                        <a href="{{ route(auth()->check() ? 'antrean.create' : 'login') }}"
                           class="btn"
                           style="width:100%;">
                            Pilih Layanan
                        </a>

                    </div>

                </article>

            @empty

                <p>
                    Layanan belum tersedia. Silakan tambahkan
                    layanan melalui database terlebih dahulu.
                </p>

            @endforelse

        </div>

    </div>

</section>

{{-- =====================================
     CTA SECTION
===================================== --}}

<section class="cta-section">

    <h2>Ready for Your Beauty Day?</h2>

    <p>
        Yuk, pilih perawatan favoritmu dan daftarkan antrean
        di LyyrsBeauty. Mulai langkah kecil untuk tampil
        lebih percaya diri!
    </p>

    <a href="{{ route(auth()->check() ? 'antrean.create' : 'login') }}"
       class="btn">
        Daftar Antrean Sekarang ♡
    </a>

</section>

@endsection
