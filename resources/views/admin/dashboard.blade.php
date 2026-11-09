@extends('layouts.app')
@section('title','Dashboard Admin | LyyrsBeauty')
@section('content')
<div class="container">
    <section class="dashboard-hero">
        <p style="font-size:11px;letter-spacing:2.4px;font-weight:800;opacity:.78;margin-bottom:10px">LYYRSBEAUTY • ADMIN SPACE</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:clamp(29px,4vw,43px);line-height:1.15;margin-bottom:12px">Dashboard Admin</h1>
        <p style="max-width:600px;color:#f8e9ef">Halo, {{ auth()->user()->name }}. Pantau aktivitas salon, kelola layanan, dan verifikasi pembayaran dalam satu tempat.</p>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:23px;position:relative;z-index:1">
            <a class="btn" href="{{ route('admin.layanan.index') }}" style="background:#fff;color:#8e3e60">✦ Kelola Layanan</a>
            <a class="btn btn-outline" href="{{ route('antrean.index') }}" style="color:#fff;border-color:rgba(255,255,255,.5)">Lihat Antrean</a>
        </div>
    </section>

    @if(session('success'))<div class="alert alert-success" style="margin-top:20px">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-error" style="margin-top:20px">{{ session('error') }}</div>@endif

    <div style="display:flex;justify-content:space-between;align-items:end;gap:12px;flex-wrap:wrap;margin:30px 0 15px">
        <div><p class="section-label" style="margin-bottom:5px">RINGKASAN SALON</p><h2 style="font-family:'Playfair Display',serif;font-size:25px">Statistik utama</h2></div>
        <span style="font-size:12px;color:var(--muted)">Diperbarui otomatis dari database</span>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(205px,1fr));gap:16px;margin-bottom:30px">
        <article class="stat-card" style="animation-delay:.04s"><div class="stat-icon">✂️</div><p style="color:var(--muted);font-size:13px;margin-bottom:8px">Total Semua Layanan</p><div class="stat-value">{{ $totalLayanan }}</div><p style="font-size:12px;color:var(--muted);margin-top:8px">Jenis layanan salon terdaftar</p><a href="{{ route('admin.layanan.index') }}" style="display:inline-block;margin-top:13px;font-size:12px;color:var(--pink-dark);font-weight:800">Kelola layanan ↗</a></article>
        <article class="stat-card" style="animation-delay:.10s"><div class="stat-icon">🗓️</div><p style="color:var(--muted);font-size:13px;margin-bottom:8px">Total Semua Antrean</p><div class="stat-value">{{ $totalAntrean }}</div><p style="font-size:12px;color:var(--muted);margin-top:8px">Termasuk antrean yang dibatalkan</p></article>
        <article class="stat-card" style="animation-delay:.16s"><div class="stat-icon">💗</div><p style="color:var(--muted);font-size:13px;margin-bottom:8px">Antrean Selesai</p><div class="stat-value">{{ $antreanSelesai }}</div><p style="font-size:12px;color:var(--muted);margin-top:8px">Pembayaran terverifikasi dan layanan selesai</p></article>
        <article class="stat-card" style="animation-delay:.22s"><div class="stat-icon">💳</div><p style="color:var(--muted);font-size:13px;margin-bottom:8px">Menunggu Verifikasi</p><div class="stat-value">{{ $pendingPayments->count() }}</div><p style="font-size:12px;color:var(--muted);margin-top:8px">Bukti pembayaran QRIS perlu diperiksa</p></article>
        <article class="stat-card" style="animation-delay:.28s"><div class="stat-icon">↩️</div><p style="color:var(--muted);font-size:13px;margin-bottom:8px">Booking Dibatalkan</p><div class="stat-value">{{ $bookingBatal }}</div><p style="font-size:12px;color:var(--muted);margin-top:8px">Pembatalan dari pelanggan atau admin</p></article>
        <article class="stat-card" style="animation-delay:.34s"><div class="stat-icon">✧</div><p style="color:var(--muted);font-size:13px;margin-bottom:8px">Total Pendapatan</p><div class="stat-value" style="font-size:clamp(22px,2.7vw,30px)">Rp {{ number_format($totalPendapatan,0,',','.') }}</div><p style="font-size:12px;color:var(--muted);margin-top:8px">Dari antrean yang sudah selesai</p></article>
    </div>

    <section class="dashboard-section" style="margin-bottom:25px">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:18px">
            <div><p class="section-label" style="margin-bottom:5px">PEMBAYARAN</p><h2 style="font-family:'Playfair Display',serif;font-size:24px">Verifikasi QRIS</h2></div>
            <span style="background:#fff0c9;color:#8a6100;padding:7px 13px;border-radius:30px;font-weight:800;font-size:12px">{{ $pendingPayments->count() }} menunggu</span>
        </div>
        @if($pendingPayments->isEmpty())
            <div style="text-align:center;padding:30px 15px;background:#fff9fb;border:1px dashed var(--border);border-radius:18px;color:var(--muted)"><div style="font-size:30px;margin-bottom:8px">♡</div>Belum ada bukti pembayaran yang menunggu verifikasi.</div>
        @else
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(270px,1fr));gap:16px">
            @foreach($pendingPayments as $payment)
                <article style="border:1px solid var(--border);border-radius:18px;padding:18px;transition:transform .2s,box-shadow .2s" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 28px rgba(71,37,51,.08)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <p style="font-size:10px;color:var(--pink-dark);font-weight:800;letter-spacing:1.5px">BUKTI PEMBAYARAN</p>
                    <h3 style="margin:7px 0 2px">{{ $payment->nama_pelanggan }}</h3>
                    <p style="font-size:13px;color:var(--muted)">{{ $payment->layanan?->nama_layanan ?? 'Layanan tidak tersedia' }} · {{ $payment->no_hp }}</p>
                    <p style="font-size:23px;font-weight:800;color:var(--pink-dark);margin:12px 0">Rp {{ number_format((float)($payment->total_pembayaran ?? $payment->layanan?->harga ?? 0),0,',','.') }}</p>
                    @if($payment->payment_proof)<a href="{{ asset('storage/'.$payment->payment_proof) }}" target="_blank" rel="noopener"><img src="{{ asset('storage/'.$payment->payment_proof) }}" alt="Bukti pembayaran {{ $payment->nama_pelanggan }}" style="width:100%;max-height:230px;object-fit:contain;background:#fff8fb;border-radius:12px;border:1px solid var(--border);margin:8px 0 12px"></a>@endif
                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                        <form method="POST" action="{{ route('admin.antrean.payment.verify',$payment) }}" onsubmit="return confirm('Pastikan uang benar-benar masuk. Verifikasi dan terbitkan nomor antrean?')">@csrf<button class="btn" type="submit">Verifikasi & Terbitkan</button></form>
                        <form method="POST" action="{{ route('admin.antrean.payment.reject',$payment) }}" onsubmit="return confirm('Tolak bukti pembayaran ini?')">@csrf<button class="btn" type="submit" style="background:#a84c67">Tolak</button></form>
                    </div>
                </article>
            @endforeach
            </div>
        @endif
    </section>

    <section class="dashboard-section">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:18px">
            <div><p class="section-label" style="margin-bottom:5px">AKTIVITAS TERBARU</p><h2 style="font-family:'Playfair Display',serif;font-size:24px">Antrean terbaru</h2></div>
            <a class="btn btn-outline" href="{{ route('antrean.index') }}">Kelola semua antrean →</a>
        </div>
        <div style="overflow-x:auto;border:1px solid var(--border);border-radius:16px">
            <table style="width:100%;border-collapse:collapse;min-width:700px">
                <thead><tr style="text-align:left;background:#fff2f7"><th style="padding:13px">Nomor</th><th>Customer</th><th>Layanan</th><th>Tanggal</th><th>Status</th><th>Pembayaran</th><th>Harga</th></tr></thead>
                <tbody>
                @forelse($antreans as $a)
                    <tr style="border-bottom:1px solid var(--border)">
                        <td style="padding:13px;font-weight:800;color:var(--pink-dark)">{{ $a->nomor_antrean ?? 'Menunggu pembayaran' }}</td><td>{{ $a->nama_pelanggan }}</td><td>{{ $a->layanan?->nama_layanan ?? '-' }}</td><td>{{ $a->tanggal_antrean?->format('d-m-Y') }}</td>
                        <td><span style="display:inline-block;padding:5px 10px;border-radius:20px;background:{{ $a->status === 'batal' ? '#ffe7e7' : ($a->status === 'selesai' ? '#e5f8eb' : '#fff0c9') }};color:{{ $a->status === 'batal' ? '#b4233f' : '#604b35' }};font-weight:800;font-size:11px">{{ $a->status === 'batal' ? 'DIBATALKAN' : ucfirst($a->status) }}</span></td>
                        <td>{{ ucfirst(str_replace('_',' ',$a->payment_status ?? 'terverifikasi')) }}</td><td>Rp {{ number_format($a->total_pembayaran ?? $a->layanan?->harga ?? 0,0,',','.') }}</td>
                    </tr>
                @empty<tr><td colspan="7" style="padding:24px;text-align:center;color:var(--muted)">Belum ada antrean.</td></tr>@endforelse
                </tbody>
            </table>
        </div>
        <p style="font-size:12px;color:var(--muted);margin-top:12px">Pendapatan dihitung dari antrean berstatus selesai dengan pembayaran terverifikasi.</p>
    </section>
</div>
@endsection
