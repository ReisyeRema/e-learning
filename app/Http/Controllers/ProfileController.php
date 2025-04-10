<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        $profile = $user->hasRole('Guru')
            ? $user->guru
            : ($user->hasRole('Siswa') ? $user->siswa : null);

        $kelas = \App\Models\Kelas::all();

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
            'kelas' => $kelas,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update data umum (User)
        $user->fill($request->only('name', 'email','username'));
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Update foto jika ada file baru
        if ($request->hasFile('foto')) {
            $request->validate([
                'foto' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validasi file foto
            ]);

            $extension = $request->file('foto')->getClientOriginalExtension();
            $newImage = $user->name . '.' . now()->timestamp . '.' . $extension;

            // Simpan foto baru ke disk public/foto_user
            $request->file('foto')->storeAs('foto_user', $newImage, 'public');

            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete('foto_user/' . $user->foto);
            }

            // Simpan nama file baru ke database
            $user->foto = $newImage;
        }

        $user->save();

        // Update data spesifik berdasarkan role
        if ($user->hasRole('Guru')) {
            $user->guru->update($request->only('nip', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'status'));
        } elseif ($user->hasRole('Siswa')) {
            $user->siswa->update($request->only('nis', 'kelas_id', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat'));
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function edit_siswa(Request $request): View
    {
        $user = $request->user();

        $profile = $user->hasRole('Siswa')
            ? $user->siswa : null;

        $kelas = \App\Models\Kelas::all();
        $profileSekolah = ProfilSekolah::first(); 


        return view('pages.siswa.dataDiri.edit', [
            'user' => $user,
            'profile' => $profile,
            'kelas' => $kelas,
            'profileSekolah' => $profileSekolah,
        ]);
    }


    public function update_siswa(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update data umum (User)
        $user->fill($request->only('name', 'email','username'));
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Update foto jika ada file baru
        if ($request->hasFile('foto')) {
            $request->validate([
                'foto' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validasi file foto
            ]);

            $extension = $request->file('foto')->getClientOriginalExtension();
            $newImage = $user->name . '.' . now()->timestamp . '.' . $extension;

            // Simpan foto baru ke disk public/foto_user
            $request->file('foto')->storeAs('foto_user', $newImage, 'public');

            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete('foto_user/' . $user->foto);
            }

            // Simpan nama file baru ke database
            $user->foto = $newImage;
        }

        $user->save();

        // Update data spesifik berdasarkan role
        if ($user->hasRole('Siswa')) {
            $user->siswa->update($request->only('tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat'));
        }

        return Redirect::route('profile-siswa.edit')->with('status', 'profile-updated');
    }
}
