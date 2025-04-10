<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Kuis;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KuisSiswaController extends Controller
{
    public function action($mapel, $kelas, $tahunAjaran, $judulKuis)
    {
        // Cari pembelajaran berdasarkan URL slug
        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->whereHas('kelas', function ($query) use ($kelas) {
                $query->whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas]);
            })
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaran) {
                $query->whereRaw("REPLACE(nama_tahun, '/', '-') = ?", [$tahunAjaran]);
            })
            ->firstOrFail();

        return view('pages.siswa.kuis.action', [
            'pembelajaran' => $pembelajaran,
        ]);
    }
}
