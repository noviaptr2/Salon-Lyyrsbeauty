@extends('layouts.app')
@section('title','Login | LyyrsBeauty')
@section('content')
<div class="container" style="max-width:560px">
 <div class="form-card">
  <p class="section-label">WELCOME BACK ♡</p><h1 class="section-title">Login LyyrsBeauty</h1>
  <p class="section-description">Masuk untuk mengatur profil dan antrean salonmu.</p>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
  <form method="POST" action="{{ route('login.post') }}">@csrf
   <div class="form-group"><label for="email">Email Gmail</label><input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@gmail.com" required autocomplete="email"></div>
   <div class="form-group"><label for="password">Password</label><input class="form-control" id="password" type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password"></div>
   <label style="display:flex;gap:8px;align-items:center;margin-bottom:20px"><input type="checkbox" name="remember"> Ingat saya</label>
   <button class="btn" style="width:100%" type="submit">Login ♡</button>
  </form>
  <p style="text-align:center;margin-top:20px">Belum punya akun? <a style="color:var(--pink-dark);font-weight:700" href="{{ route('register') }}">Daftar sebagai customer</a></p>
 </div>
</div>
@endsection
