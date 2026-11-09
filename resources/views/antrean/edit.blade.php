@extends('layouts.app')
@section('title','Edit Antrean | LyyrsBeauty')
@section('content')
<div class="container" style="max-width:700px"><div class="form-card">
 <p class="section-label">UPDATE YOUR BOOKING</p><h1 class="section-title">Edit Antrean</h1>
 @if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
 <form method="POST" action="{{ route('antrean.update',$antrean) }}">@csrf @method('PUT')
  <div class="form-group"><label>Nama Lengkap</label><input class="form-control" name="nama_pelanggan" value="{{ old('nama_pelanggan',$antrean->nama_pelanggan) }}" required></div>
  <div class="form-group"><label>Nomor HP</label><input class="form-control" name="no_hp" value="{{ old('no_hp',$antrean->no_hp) }}" required></div>
  <div class="form-group"><label>Layanan</label><select class="form-control" name="layanan_id" required>@foreach($layanans as $layanan)<option value="{{ $layanan->id }}" @selected(old('layanan_id',$antrean->layanan_id)==$layanan->id)>{{ $layanan->nama_layanan }} — Rp {{ number_format($layanan->harga,0,',','.') }}</option>@endforeach</select></div>
  <button class="btn">Simpan Perubahan</button> <a class="btn btn-outline" href="{{ route('antrean.index') }}">Batal</a>
 </form>
</div></div>
@endsection
