<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Kelas;
use App\Models\WaliKelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WaliKelasRequest;

class WaliKelasController extends Controller
{
    public function index()
    {
        $waliKelas = WaliKelas::all();
        $tahunAjaran = TahunAjaran::all();
        $kelas = Kelas::all();
        $guru = User::getGuru();
        return view('pages.admin.waliKelas.index', compact('waliKelas', 'tahunAjaran', 'kelas', 'guru'));
    }


    public function store(WaliKelasRequest $request)
    {
        $data = $request->validated();

        // Simpan data wali kelas
        WaliKelas::create($data);

        // Ambil user berdasarkan guru_id
        $guru = User::find($data['guru_id']);

        // Berikan role 'Guru' dan 'Wali Kelas' (jika belum punya)
        if ($guru) {
            $guru->assignRole('Guru'); // Tidak akan menambah ganda kalau sudah ada
            $guru->assignRole('Wali Kelas');
        }

        return redirect()->route('wali-kelas.index')->with('success', 'Wali Kelas Berhasil Ditambah');
    }



    public function update(WaliKelasRequest $request, $id)
    {
        $waliKelas = WaliKelas::findOrFail($id);
        $data = $request->validated();

        $waliKelas->update($data);

        // Pastikan user punya kedua role
        $guru = User::find($data['guru_id']);
        if ($guru) {
            $guru->assignRole('Guru');
            $guru->assignRole('Wali Kelas');
        }

        return redirect()->route('wali-kelas.index')->with('success', 'Wali Kelas Berhasil Diupdate');
    }

    public function destroy($id)
    {
        $waliKelas = WaliKelas::findOrFail($id);

        // Ambil user (guru) terkait
        $guru = User::find($waliKelas->guru_id);

        // Hapus data wali kelas
        $waliKelas->delete();

        // Cek apakah guru ini masih menjadi wali di tempat lain
        $masihMenjadiWali = WaliKelas::where('guru_id', $guru->id)->exists();

        // Jika tidak, cabut role 'Wali Kelas'
        if (!$masihMenjadiWali && $guru->hasRole('Wali Kelas')) {
            $guru->removeRole('Wali Kelas');
        }

        return redirect()->route('wali-kelas.index')->with('success', 'Wali Kelas Berhasil Dihapus');
    }


    public function toggleAktif(Request $request, $id)
    {
        $waliKelas = WaliKelas::findOrFail($id);

        // Ubah status aktif
        $waliKelas->aktif = !$waliKelas->aktif;
        $waliKelas->save();

        return back()->with('success', 'Status Wali Kelas diperbarui.');
    }
}
