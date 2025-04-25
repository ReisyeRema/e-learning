<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Tugas;
use App\Models\Enrollments;
use App\Models\Pembelajaran;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MataPelajaranController extends Controller
{
    // public function index()
    // {
    //     $siswaId = Auth::id();
    //     $enrollments = Enrollments::where('siswa_id', $siswaId)
    //         ->where('status', 'approved')
    //         ->with(['pembelajaran' => function ($query) {
    //             $query->withCount('enrollments') // Hitung jumlah siswa di pembelajaran
    //                 ->withCount('pertemuanMateri');
    //         }])
    //         ->get();

    //     $profileSekolah = ProfilSekolah::first(); 

    //     return view('pages.siswa.mataPelajaran.index', compact('enrollments','profileSekolah'));
    // }

    public function index()
    {
        // Log aktivitas
        $this->logActivity('Mengakses List Mata Pelajaran', 'User membuka halaman list mata pelajaran');

        $siswaId = Auth::id();

        $enrollments = Enrollments::where('siswa_id', $siswaId)
            ->where('status', 'approved')
            ->with(['pembelajaran' => function ($query) use ($siswaId) {
                $query->withCount(['enrollments', 'pertemuanMateri'])
                    ->with([
                        'pertemuanTugas.tugas.submitTugas' => function ($q) use ($siswaId) {
                            $q->where('siswa_id', $siswaId);
                        },
                        'pertemuanKuis.kuis.hasilKuis' => function ($q) use ($siswaId) {
                            $q->where('siswa_id', $siswaId);
                        }
                    ]);
            }])
            ->get();

        // Tambahkan progress ke setiap enrollment
        foreach ($enrollments as $enrollment) {
            $pembelajaran = $enrollment->pembelajaran;

            $totalTugas = $pembelajaran->pertemuanTugas->count();
            $totalKuis = $pembelajaran->pertemuanKuis->count();

            $tugasSelesai = 0;
            foreach ($pembelajaran->pertemuanTugas as $ptugas) {
                if ($ptugas->tugas && $ptugas->tugas->submitTugas->isNotEmpty()) {
                    $tugasSelesai++;
                }
            }

            $kuisSelesai = 0;
            foreach ($pembelajaran->pertemuanKuis as $pkuis) {
                if ($pkuis->kuis && $pkuis->kuis->hasilKuis->isNotEmpty()) {
                    $kuisSelesai++;
                }
            }

            $progressTugas = $totalTugas > 0 ? ($tugasSelesai / $totalTugas) * 50 : 0;
            $progressKuis = $totalKuis > 0 ? ($kuisSelesai / $totalKuis) * 50 : 0;

            $enrollment->progress = round($progressTugas + $progressKuis);
        }

        $profileSekolah = ProfilSekolah::first();

        return view('pages.siswa.mataPelajaran.index', compact('enrollments', 'profileSekolah'));
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

        // Log aktivitas (setelah $pembelajaran ditemukan)
        $this->logActivity(
            'Mengakses Mata Pelajaran ' . $pembelajaran->nama_mapel,
            'User membuka halaman mata pelajaran ' . $pembelajaran->nama_mapel
        );

        $siswaId = Auth::id();

        $tugas = Tugas::with(['submitTugas' => function ($query) use ($siswaId) {
            $query->where('siswa_id', $siswaId);
        }])->get();

        $profileSekolah = ProfilSekolah::first();

        return view('pages.siswa.mataPelajaran.show', compact('pembelajaran', 'tugas', 'profileSekolah'));
    }


    protected function logActivity($activity, $details = '')
    {
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'details' => $details,
        ]);
    }
}
