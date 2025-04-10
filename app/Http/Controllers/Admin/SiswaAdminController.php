<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Str;
use App\Exports\ExportSiswa;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\SiswaAdminRequest;

class SiswaAdminController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data kelas untuk dropdown
        $kelas = \App\Models\Kelas::all();

        // Filter berdasarkan kelas jika ada parameter 'kelas_id'
        $users = User::role('Siswa')
            ->with(['siswa', 'siswa.kelas'])
            ->when($request->kelas_id, function ($query) use ($request) {
                return $query->whereHas('siswa', function ($q) use ($request) {
                    $q->where('kelas_id', $request->kelas_id);
                });
            })
            ->paginate(10);

        return view('pages.admin.siswa.index', compact('users', 'kelas'));
    }


    public function create()
    {
        $kelas = \App\Models\Kelas::all(); // Ambil semua data kelas
        return view('pages.admin.siswa.create', compact('kelas'));
    }

    public function store(SiswaAdminRequest $request)
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


        // Tambahkan role siswa pada user
        $user->assignRole('Siswa');


        // Simpan data detail siswa
        Siswa::create([
            'user_id' => $user->id,
            'kelas_id' => $request->kelas_id,
            'nis' => $request->nis,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa Berhasil Ditambahkan.');
    }


    public function edit($id)
    {
        $siswa = Siswa::with('user')->find($id);
        $kelas = \App\Models\Kelas::all();
        return view('pages.admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(SiswaAdminRequest $request, Siswa $siswa)
    {
        $user = $siswa->user;

        // Update data user
        $updateUserData = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ];

        // Periksa apakah password diisi
        if ($request->filled('password')) {
            $updateUserData['password'] = Hash::make($request->password); // Hash password untuk keamanan
            $updateUserData['password_plain'] = $request->password; // Simpan password plain
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

        // Update data siswa
        $siswa->update([
            'kelas_id' => $request->kelas_id,
            'nis' => $request->nis,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $siswa = siswa::find($id);
        $user = $siswa->user;

        if ($user->foto) {
            Storage::disk('public')->delete('foto_user/' . $user->foto);
        }

        $user->delete();
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil dihapus.');
    }

    public function export_excel(Request $request)
    {
        $kelasId = $request->get('kelas_id');

        // Tentukan nama file
        if ($kelasId) {
            $kelas = \App\Models\Kelas::find($kelasId);
            $kelasNama = $kelas ? str_replace(' ', '_', $kelas->nama_kelas) : 'kelas_tidak_ditemukan';
            // $tahunAjaran = $kelas ? str_replace('/', '-', $kelas->tahun_ajaran) : 'N/A';
            $fileName = "daftar_siswa_kelas_{$kelasNama}.xlsx";
        } else {
            $fileName = 'daftar_semua_siswa.xlsx';
        }

        return Excel::download(new ExportSiswa($kelasId), $fileName);
    }


    public function show($mapel, $kelas, $tahunAjaran)
    {
        // Ubah slug kembali ke format nama asli
        $mapelNama = Str::title(str_replace('-', ' ', $mapel));
        $kelasNama = Str::upper(str_replace('-', ' ', $kelas));
        
        // Ubah "2023-2024" kembali menjadi "2023/2024" agar cocok dengan database
        $tahunAjaranFormatted = str_replace('-', '/', $tahunAjaran);

        // Cari kelas berdasarkan nama
        $kelasData = Kelas::whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas])->firstOrFail();

        // Cari pembelajaran yang sesuai
        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->where('kelas_id', $kelasData->id)
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaranFormatted) {
                $query->where('nama_tahun', $tahunAjaranFormatted);
            })
            ->firstOrFail();

        // Ambil daftar siswa yang terdaftar dalam pembelajaran ini
        $siswaList = User::with(['enrollments' => function ($query) use ($pembelajaran) {
            $query->where('pembelajaran_id', $pembelajaran->id);
        }])->whereHas('enrollments', function ($query) use ($pembelajaran) {
            $query->where('pembelajaran_id', $pembelajaran->id);
        })->get();        


        return view('pages.admin.siswa.show', compact('pembelajaran', 'kelasData', 'siswaList'));
    }
}
