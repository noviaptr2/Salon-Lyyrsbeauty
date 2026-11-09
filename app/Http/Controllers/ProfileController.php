<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'ends_with:@gmail.com', Rule::unique('users', 'email')->ignore($user->id)],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'email.ends_with' => 'Email harus menggunakan @gmail.com. Harap periksa kembali.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto_profil.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('foto-profil', 'public');
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (isset($data['foto_profil'])) {
            $user->foto_profil = $data['foto_profil'];
        }
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    public function photo()
    {
        $user = auth()->user();
        abort_unless($user && $user->foto_profil, 404);
        $disk = Storage::disk('public');
        abort_unless($disk->exists($user->foto_profil), 404);
        return response()->file($disk->path($user->foto_profil));
    }
}
