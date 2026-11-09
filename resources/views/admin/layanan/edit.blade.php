@extends('layouts.app')
@section('title', 'Edit Layanan | Lyyora Salon')
@section('content')
<div class="container" style="max-width:850px">
    <p class="section-label">ADMIN • EDIT SERVICE</p>
    <h1 class="section-title">Edit Layanan 🌸</h1>
    <p class="section-description">Perbarui informasi layanan yang ditampilkan kepada pelanggan.</p>
    @if($errors->any())<div class="alert alert-error">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
    <form action="{{ route('admin.layanan.update', $layanan) }}" method="POST" enctype="multipart/form-data" style="background:#fff;border:1px solid var(--border);border-radius:22px;padding:25px">
        @csrf @method('PUT')
        <div class="form-group"><label>Nama layanan</label><input class="form-control" name="nama_layanan" value="{{ old('nama_layanan', $layanan->nama_layanan) }}" required maxlength="255"></div>
        <div class="form-group"><label>Deskripsi</label><textarea class="form-control" name="deskripsi" rows="4">{{ old('deskripsi', $layanan->deskripsi) }}</textarea></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
            <div class="form-group"><label>Harga (Rp)</label><input class="form-control" type="number" name="harga" min="0" step="1000" value="{{ old('harga', $layanan->harga) }}" required></div>
            <div class="form-group"><label>Durasi (menit)</label><input class="form-control" type="number" name="durasi" min="1" value="{{ old('durasi', $layanan->durasi) }}" required></div>
        </div>
        @if($layanan->foto)<div style="margin-bottom:16px"><label>Foto saat ini</label><br><img src="{{ asset('storage/'.$layanan->foto) }}" alt="Foto layanan" style="width:180px;height:130px;object-fit:cover;border-radius:14px;margin-top:8px"></div>@endif
        <div class="form-group"><label>Ganti foto (opsional, maks. 2 MB)</label><input class="form-control" type="file" name="foto" accept=".jpg,.jpeg,.png,.webp"></div>
        <div style="display:flex;gap:10px;flex-wrap:wrap"><button class="btn" type="submit">Simpan Perubahan</button><a class="btn btn-outline" href="{{ route('admin.layanan.index') }}">Batal</a></div>
    </form>
</div>
@endsection
