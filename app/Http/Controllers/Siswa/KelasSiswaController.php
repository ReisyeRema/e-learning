<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Kelas;
use App\Models\Enrollments;
use App\Models\TahunAjaran;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KelasSiswaController extends Controller
{
    public function show($id, Request $request)
    {
        $kelas = Kelas::findOrFail($id);
        $siswaId = Auth::id();

        // Ambil tahun ajaran yang memiliki pembelajaran pada kelas ini
        $tahunAjaranList = TahunAjaran::whereHas('pembelajaran', function ($query) use ($id) {
            $query->where('kelas_id', $id);
        })->get();

        $tahunAjaranDipilih = $request->input('tahun_ajaran_id') ?? $tahunAjaranList->first()?->id;

        $pembelajaran = Pembelajaran::where('kelas_id', $id)
            ->where('tahun_ajaran_id', $tahunAjaranDipilih)
            ->get();

        $enrollments = Enrollments::where('siswa_id', $siswaId)
            ->whereIn('pembelajaran_id', $pembelajaran->pluck('id'))
            ->get()
            ->keyBy('pembelajaran_id');

        $profileSekolah = ProfilSekolah::first();

        return view('pages.siswa.kelas.show', compact(
            'kelas',
            'pembelajaran',
            'enrollments',
            'profileSekolah',
            'tahunAjaranList',
            'tahunAjaranDipilih'
        ));
    }
}
