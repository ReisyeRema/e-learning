<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Tugas;
use App\Models\Enrollments;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $siswaId = Auth::id();
        $enrollments = Enrollments::where('siswa_id', $siswaId)
            ->where('status', 'approved')
            ->with(['pembelajaran' => function ($query) {
                $query->withCount('enrollments') // Hitung jumlah siswa di pembelajaran
                    ->withCount('pertemuanMateri');
            }])
            ->get();
        
        $profileSekolah = ProfilSekolah::first(); 

        return view('pages.siswa.mataPelajaran.index', compact('enrollments','profileSekolah'));
    }

    public function show($mapel, $kelas, $tahunAjaran)
    {
        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->whereHas('kelas', function ($query) use ($kelas) {
                $query->whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas]);
            })
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaran) {
                $query->whereRaw("REPLACE(nama_tahun, '/', '-') = ?", [$tahunAjaran]);
            })
            ->with([
                'pertemuanMateri.materi',
                'pertemuanTugas.tugas',
                'pertemuanKuis.kuis'
            ])
            ->firstOrFail();

        $siswaId = Auth::id();

        $tugas = Tugas::with(['submitTugas' => function ($query) use ($siswaId) {
            $query->where('siswa_id', $siswaId);
        }])->get();

        $profileSekolah = ProfilSekolah::first(); 

        return view('pages.siswa.mataPelajaran.show', compact('pembelajaran','tugas','profileSekolah'));
    }
}
