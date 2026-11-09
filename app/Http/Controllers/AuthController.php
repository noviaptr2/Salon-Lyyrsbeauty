<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class AuthController extends Controller {
 public function showLogin() { return view('auth.login'); }
 public function login(Request $request) {
  $request->validate(['email'=>['required','email','ends_with:@gmail.com'],'password'=>'required'], [
   'email.ends_with'=>'Email harus menggunakan @gmail.com. Harap periksa kembali.',
   'email.email'=>'Format email tidak valid.',
   'password.required'=>'Password wajib diisi.'
  ]);
  if (!Auth::attempt($request->only('email','password'), $request->boolean('remember'))) {
   return back()->withErrors(['email'=>'Email atau password salah.'])->onlyInput('email');
  }
  $request->session()->regenerate();
  return Auth::user()->role === 'admin' ? redirect()->route('admin.dashboard') : redirect()->route('home');
 }
 public function showRegister() { return view('auth.register'); }
 public function register(Request $request) {
  $data=$request->validate([
   'name'=>'required|string|max:100',
   'email'=>['required','email','ends_with:@gmail.com','unique:users,email'],
   'password'=>['required','confirmed',Password::min(8)]
  ], ['email.ends_with'=>'Email harus menggunakan @gmail.com. Harap periksa kembali.','password.confirmed'=>'Konfirmasi password tidak sama.']);
  $data['password']=Hash::make($data['password']); $data['role']='customer';
  $user=User::create($data); Auth::login($user); $request->session()->regenerate();
  return redirect()->route('home')->with('success','Akun berhasil dibuat. Selamat datang di LyyrsBeauty!');
 }
 public function logout(Request $request) {
  Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
  return redirect()->route('login')->with('success','Kamu berhasil logout.');
 }
}
