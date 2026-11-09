@extends('layouts.app')
@section('title','Daftar Akun | LyyrsBeauty')
@section('content')
<div class="container" style="max-width:560px"><div class="form-card">
 <p class="section-label">JOIN OUR BEAUTY CLUB</p><h1 class="section-title">Buat Akun Customer</h1>
 @if($errors->any())<div class="alert alert-error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
 <form method="POST" action="{{ route('register.post') }}">@csrf
  <div class="form-group"><label>Nama Lengkap</label><input class="form-control" name="name" value="{{ old('name') }}" required maxlength="100"></div>
  <div class="form-group"><label>Email Gmail</label><input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="nama@gmail.com" required></div>
  <div class="form-group"><label>Password (minimal 8 karakter)</label><input class="form-control" type="password" name="password" required minlength="8"></div>
  <div class="form-group"><label>Konfirmasi Password</label><input class="form-control" type="password" name="password_confirmation" required minlength="8"></div>
  <button class="btn" style="width:100%">Buat Akun ♡</button>
 </form><p style="text-align:center;margin-top:18px">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
</div></div>
@endsection
