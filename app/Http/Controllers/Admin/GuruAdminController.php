<?php

namespace App\Http\Controllers\Admin;

use App\Models\Guru;
use App\Models\User;
use App\Exports\ExportGuru;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\GuruAdminRequest;

class GuruAdminController extends Controller
{
    public function index()
    {
        // Log aktivitas
        $this->logActivity('Mengakses Data Guru', 'User membuka halaman data guru');

        $users = User::role('Guru')->with('guru')->get();
        return view('pages.admin.guru.index', compact('users'));
    }

    public function create()
    {
        return view('pages.admin.guru.create');
    }

    public function store(GuruAdminRequest $request)
    {
        $newImage = '';

        if ($request->file('foto')) {
            $extension = $request->file('foto')->getClientOriginalExtension();
            $newImage = $request->name . '.' . now()->timestamp . '.' . $extension;

            // Simpan ke disk public/foto_user
            $request->file('foto')->storeAs('foto_user', $newImage, 'public');
        } else {
            $newImage = '';
        }

        $password = $request->password;

        // Simpan data user terlebih dahulu
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($password),
            'password_plain' => $password,
            'foto' => $newImage
        ]);

        // Tambahkan role Guru pada user
        $user->assignRole('Guru');


        // Simpan data detail guru
        Guru::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);

        return redirect()->route('guru.index')->with('success', 'Data Guru Berhasil Ditambahkan.');
    }


    public function edit($id)
    {
        $guru = Guru::with('user')->find($id);
        return view('pages.admin.guru.edit', compact('guru'));
    }

    public function update(GuruAdminRequest $request, Guru $guru)
    {
        $user = $guru->user;

        // Siapkan data user untuk diupdate
        $updateUserData = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ];

        // Periksa apakah password diisi
        if ($request->filled('password')) {
            $updateUserData['password'] = Hash::make($request->password); 
            $updateUserData['password_plain'] = $request->password; 
        }

        // Update foto jika ada file baru
        if ($request->hasFile('foto')) {
            $extension = $request->file('foto')->getClientOriginalExtension();
            $newImage = $request->name . '.' . now()->timestamp . '.' . $extension;

            // Simpan foto baru ke disk public/foto_user
            $request->file('foto')->storeAs('foto_user', $newImage, 'public');

            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete('foto_user/' . $user->foto);
            }

            $updateUserData['foto'] = $newImage;
        }

        $user->update($updateUserData);

        // Update data guru
        $guru->update([
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil diperbarui.');
    }




    public function destroy($id)
    {
        $guru = Guru::find($id);
        $user = $guru->user;

        if ($user->foto) {
            Storage::disk('public')->delete('foto_user/' . $user->foto);
        }

        $user->delete();
        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil dihapus.');
    }


    function export_excel()
    {
        return Excel::download(new ExportGuru, "guru.xlsx");
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
