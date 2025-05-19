<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileSekolahController extends Controller
{
    public function index()
    {
        // Log aktivitas
        $this->logActivity('Mengakses Data Profile Sekolah', 'User membuka halaman data profile sekolah');


        return view('pages.admin.profilSekolah.index');
    }

    public function show()
    {
        return ProfilSekolah::first();
    }

    public function update(Request $request)
    {
        // Validasi input dengan pesan kesalahan khusus
        $messages = [
            'nama_sekolah.required' => 'Nama Sekolah harus diisi.',
            'nama_sekolah.string' => 'Nama Sekolah harus berupa teks.',
            'nama_sekolah.max' => 'Nama Sekolah tidak boleh lebih dari 255 karakter.',
            'alamat.required' => 'Alamat harus diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'akreditas.required' => 'Akreditasi harus diisi.',
            'akreditas.string' => 'Akreditasi harus berupa teks.',
            'akreditas.max' => 'Akreditasi tidak boleh lebih dari 255 karakter.',
            'no_hp.required' => 'Nomor HP harus diisi.',
            'no_hp.numeric' => 'Nomor HP hanya boleh berisi angka.',
            'no_hp.digits_between' => 'Nomor HP harus terdiri dari 10 hingga 15 digit.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berupa file dengan format: jpeg, png, jpg, gif.',
            'foto.max' => 'Foto tidak boleh lebih dari 2048 kilobyte.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'latitude.between' => 'Latitude harus antara -90 dan 90 derajat.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'longitude.between' => 'Longitude harus antara -180 dan 180 derajat.',
        ];

        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'alamat' => 'required|string',
            'akreditas' => 'required|string|max:255',
            'no_hp' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ], $messages);

        $profilSekolah = ProfilSekolah::first();
        $profilSekolah->nama_sekolah = $request->nama_sekolah;
        $profilSekolah->alamat = strip_tags($request->alamat);
        $profilSekolah->akreditas = $request->akreditas;
        $profilSekolah->no_hp = $request->no_hp;
        $profilSekolah->email = $request->email;
        $profilSekolah->latitude = $request->latitude;
        $profilSekolah->longitude = $request->longitude;


        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada dan file-nya ada di disk 'public'
            if ($profilSekolah->foto && Storage::disk('public')->exists('logo_sekolah/' . $profilSekolah->foto)) {
                Storage::disk('public')->delete('logo_sekolah/' . $profilSekolah->foto); // Hapus foto lama
            }

            // Ambil ekstensi file dan buat nama file baru
            $extension = $request->file('foto')->getClientOriginalExtension();
            $newImage = $request->nama_sekolah . '.' . now()->timestamp . '.' . $extension;

            // Simpan foto baru ke disk public/logo_sekolah
            $storedPath = $request->file('foto')->storeAs('logo_sekolah', $newImage, 'public');

            // Update path foto pada model profilSekolah
            $profilSekolah->foto = $newImage;
        }


        $profilSekolah->save();

        return redirect()->route('profilesekolah.index')->with('success', 'Perubahan berhasil disimpan');
    }

    // Menambahkan log aktivitas
    protected function logActivity($activity, $details = '')
    {
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'details' => $details,
        ]);
    }
}
