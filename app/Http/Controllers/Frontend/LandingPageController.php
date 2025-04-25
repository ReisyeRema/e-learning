<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;

class LandingPageController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        $profileSekolah = ProfilSekolah::first();

        $jumlahGuru = Guru::count();
        $jumlahSiswa = Siswa::count();
        $jumlahKelas = Kelas::count();
        $jumlahMapel = Pembelajaran::count();
        return view('pages.frontend.index', compact('kelas', 'profileSekolah', 'jumlahGuru', 'jumlahSiswa', 'jumlahKelas', 'jumlahMapel'));
    }
}
