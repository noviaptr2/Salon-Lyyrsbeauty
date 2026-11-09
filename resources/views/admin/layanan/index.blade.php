@extends('layouts.app')
@section('title', 'Kelola Layanan | Lyyora Salon')
@section('content')
<div class="container">
    <p class="section-label">ADMIN • SERVICE MANAGEMENT</p>
    <h1 class="section-title">Kelola Layanan 🌸</h1>
    <p class="section-description">Tambah, ubah, dan kelola layanan salon yang tampil di halaman pelanggan.</p>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-error">{{ session('error') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-error">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
    @endif

    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:22px">
        <h2>Daftar Layanan</h2>
        <a class="btn" href="{{ route('admin.layanan.create') }}">+ Tambah Layanan</a>
    </div>

    <div style="overflow-x:auto;background:#fff;border:1px solid var(--border);border-radius:18px;padding:12px">
        <table style="width:100%;border-collapse:collapse;min-width:720px">
            <thead><tr style="text-align:left;background:#fff0f5">
                <th style="padding:13px">Foto</th><th>Nama Layanan</th><th>Harga</th><th>Durasi</th><th>Aksi</th>
            </tr></thead>
            <tbody>
            @forelse($layanans as $layanan)
                <tr style="border-bottom:1px solid var(--border)">
                    <td style="padding:12px">
                        @if($layanan->foto)
                            <img src="{{ asset('storage/'.$layanan->foto) }}" alt="{{ $layanan->nama_layanan }}" style="width:78px;height:65px;object-fit:cover;border-radius:12px">
                        @else
                            <div style="width:78px;height:65px;border-radius:12px;background:#fff0f5;display:grid;place-items:center;font-size:25px">🌸</div>
                        @endif
                    </td>
                    <td><strong>{{ $layanan->nama_layanan }}</strong><br><small>{{ \Illuminate\Support\Str::limit($layanan->deskripsi, 65) }}</small></td>
                    <td>Rp {{ number_format($layanan->harga, 0, ',', '.') }}</td>
                    <td>{{ $layanan->durasi }} menit</td>
                    <td>
                        <div style="display:flex;gap:8px;flex-wrap:wrap">
                            <a class="btn btn-outline" href="{{ route('admin.layanan.edit', $layanan) }}">Edit</a>
                            <form action="{{ route('admin.layanan.destroy', $layanan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn" type="submit" style="background:#a93e62">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="padding:28px;text-align:center">Belum ada layanan. Klik “Tambah Layanan” untuk menambahkan layanan salon.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:22px"><a href="{{ route('admin.dashboard') }}" class="btn btn-outline">← Kembali ke Dashboard</a></div>
</div>
@endsection
