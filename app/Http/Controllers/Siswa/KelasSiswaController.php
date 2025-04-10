<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Kelas;
use App\Models\Enrollments;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KelasSiswaController extends Controller
{
    public function show($id)
    {
        $kelas = Kelas::findOrFail($id);
        $pembelajaran = Pembelajaran::where('kelas_id', $id)->get();
        $siswaId = Auth::id();

        // Ambil semua enrollment siswa untuk mata pelajaran dalam kelas ini
        $enrollments = Enrollments::where('siswa_id', $siswaId)
            ->whereIn('pembelajaran_id', $pembelajaran->pluck('id'))
            ->get()
            ->keyBy('pembelajaran_id');

        $profileSekolah = ProfilSekolah::first(); 

        return view('pages.siswa.kelas.show', compact('kelas', 'pembelajaran', 'enrollments','profileSekolah'));
    }
}
