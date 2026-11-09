@extends('layouts.app')

@section('title', 'Layanan Salon | LyyrsBeauty')

@section('content')

<style>
    .service-hero {
        background: linear-gradient(135deg, #fff0f5, #fce0eb);
        padding: 60px 20px;
        text-align: center;
    }

    .service-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(32px, 5vw, 46px);
        margin: 10px 0;
    }

    .service-hero p {
        color: #8c7780;
    }

    .service-section {
        padding: 55px 0;
    }

    .service-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }

    .service-card {
        background: #fff;
        border: 1px solid #f3dce5;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(180, 100, 130, 0.07);
        transition: transform 0.25s, box-shadow 0.25s;
    }

    .service-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(180, 100, 130, 0.14);
    }

    .service-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
        background: #fce0eb;
    }

    .service-content {
        padding: 22px;
    }

    .service-content h3 {
        font-family: 'Playfair Display', serif;
        font-size: 23px;
        margin-bottom: 8px;
    }

    .service-description {
        color: #8c7780;
        font-size: 14px;
        min-height: 48px;
    }

    .service-price {
        color: #d96e96;
        font-size: 21px;
        font-weight: 700;
        margin: 17px 0 5px;
    }

    .service-duration {
        color: #8c7780;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .service-content .btn {
        text-align: center;
        width: 100%;
    }

    .empty-service {
        text-align: center;
        padding: 35px 20px;
        background: #fff;
        border: 1px solid #f3dce5;
        border-radius: 20px;
    }
</style>

<section class="service-hero">
    <div class="container">
        <p style="color:#d96e96; font-weight:700;">
            BEAUTY & SELF CARE
        </p>

    <h1>Our Beauty Services ♡</h1>

    <p>
        Temukan perawatan favoritmu dan luangkan waktu untuk dirimu sendiri.
    </p>
</div>

</section>

<section class="service-section">
    <div class="container">

    @if($layanans->isEmpty())

        <div class="empty-service">
            <h3>Belum Ada Layanan</h3>

            <p style="color:#8c7780; margin-top:8px;">
                Layanan salon akan ditampilkan di sini setelah data ditambahkan.
            </p>
        </div>

    @else

        <div class="service-grid">

            @foreach($layanans as $layanan)

                <article class="service-card">

                    @if($layanan->foto)
                        <img
                            src="{{ asset('storage/' . $layanan->foto) }}"
                            alt="{{ $layanan->nama_layanan }}"
                            class="service-image"
                            loading="lazy"
                        >
                    @else
                        <div
                            class="service-image"
                            style="display:flex; align-items:center; justify-content:center; font-size:65px;"
                        >
                            🌸
                        </div>
                    @endif

                    <div class="service-content">

                        <h3>{{ $layanan->nama_layanan }}</h3>

                        <p class="service-description">
                            {{ $layanan->deskripsi ?: 'Perawatan kecantikan dengan pelayanan terbaik.' }}
                        </p>

                        <div class="service-price">
                            Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                        </div>

                        <p class="service-duration">
                            ◷ Durasi sekitar {{ $layanan->durasi }} menit
                        </p>

                        <a
                            href="{{ route('antrean.create') }}"
                            class="btn"
                        >
                            Booking Sekarang ♡
                        </a>

                    </div>

                </article>

            @endforeach

        </div>

    @endif

</div>

</section>

@endsection
