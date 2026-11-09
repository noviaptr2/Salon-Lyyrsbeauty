@extends('layouts.app')
@section('title', 'Tambah Layanan | Lyyora Salon')
@section('content')
<div class="container" style="max-width:850px">
    <p class="section-label">ADMIN • ADD SERVICE</p>
    <h1 class="section-title">Tambah Layanan Baru 🌷</h1>
    <p class="section-description">Isi detail layanan dan unggah foto jika tersedia.</p>
    @if($errors->any())<div class="alert alert-error">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
    <form action="{{ route('admin.layanan.store') }}" method="POST" enctype="multipart/form-data" style="background:#fff;border:1px solid var(--border);border-radius:22px;padding:25px">
        @csrf
        <div class="form-group"><label>Nama layanan</label><input class="form-control" name="nama_layanan" value="{{ old('nama_layanan') }}" required maxlength="255"></div>
        <div class="form-group"><label>Deskripsi</label><textarea class="form-control" name="deskripsi" rows="4">{{ old('deskripsi') }}</textarea></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
            <div class="form-group"><label>Harga (Rp)</label><input class="form-control" type="number" name="harga" min="0" step="1000" value="{{ old('harga') }}" required></div>
            <div class="form-group"><label>Durasi (menit)</label><input class="form-control" type="number" name="durasi" min="1" value="{{ old('durasi',30) }}" required></div>
        </div>
        <div class="form-group"><label>Foto layanan (maks. 2 MB)</label><input class="form-control" type="file" name="foto" accept=".jpg,.jpeg,.png,.webp"></div>
        <div style="display:flex;gap:10px;flex-wrap:wrap"><button class="btn" type="submit">Simpan Layanan</button><a class="btn btn-outline" href="{{ route('admin.layanan.index') }}">Batal</a></div>
    </form>
</div>
@endsection
