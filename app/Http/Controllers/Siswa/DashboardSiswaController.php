<?php

namespace App\Http\Controllers\Siswa;

use Carbon\Carbon;
use App\Models\HasilKuis;
use App\Models\Enrollments;
use App\Models\SubmitTugas;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Models\PertemuanKuis;
use App\Models\ProfilSekolah;
use App\Models\PertemuanTugas;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardSiswaController extends Controller
{
    public function index(Request $request)
    {
        $siswaId = Auth::id();

        // === FILTER PEMBELAJARAN ===
        $pembelajaranKey = $request->input('pembelajaran_key');
        $mapelNama = $kelasId = $tahunAjaranId = null;

        if ($pembelajaranKey) {
            [$mapelNama, $kelasId, $tahunAjaranId] = explode('|', $pembelajaranKey);
        }

        // === AMBIL DATA ENROLLMENTS DAN PROGRESSNYA ===
        $enrollments = Enrollments::with([
            'pembelajaran.pertemuanTugas.tugas.submitTugas' => fn($q) => $q->where('siswa_id', $siswaId),
            'pembelajaran.pertemuanKuis.kuis.hasilKuis' => fn($q) => $q->where('siswa_id', $siswaId),
        ])
            ->where('siswa_id', $siswaId)
            ->when($pembelajaranKey, fn($query) => $query->whereHas('pembelajaran', function ($q) use ($mapelNama, $kelasId, $tahunAjaranId) {
                $q->where('nama_mapel', $mapelNama)
                    ->where('kelas_id', $kelasId)
                    ->where('tahun_ajaran_id', $tahunAjaranId);
            }))
            ->get();

        $progressList = $enrollments->map(function ($enroll) use ($siswaId) {
            $p = $enroll->pembelajaran;

            $tugasTotal = $p->pertemuanTugas->count();
            $tugasSelesai = $p->pertemuanTugas->filter(
                fn($pt) =>
                $pt->tugas->submitTugas->where('siswa_id', $siswaId)->isNotEmpty()
            )->count();

            $kuisTotal = $p->pertemuanKuis->count();
            $kuisSelesai = $p->pertemuanKuis->filter(
                fn($pk) =>
                $pk->kuis->hasilKuis->where('siswa_id', $siswaId)->where('is_done', true)->isNotEmpty()
            )->count();

            return [
                'pembelajaran' => $p,
                'nama_mapel' => $p->nama_mapel,
                'tugas_total' => $tugasTotal,
                'tugas_selesai' => $tugasSelesai,
                'kuis_total' => $kuisTotal,
                'kuis_selesai' => $kuisSelesai,
            ];
        });

        $pembelajaranList = $enrollments->pluck('pembelajaran')->unique(
            fn($item) =>
            $item->nama_mapel . '|' . $item->kelas_id . '|' . $item->tahun_ajaran_id
        );

        // === DEADLINE TUGAS DAN KUIS ===
        // Mendapatkan daftar pembelajaran yang sudah di-approve
        $enrolledPembelajaranIds = Enrollments::where('siswa_id', $siswaId)
            ->where('status', 'approved')
            ->pluck('pembelajaran_id');

        // Mendapatkan daftar tugas yang sudah disubmit
        $submittedTugasIds = SubmitTugas::where('siswa_id', $siswaId)
            ->whereIn('status', ['sudah_dikumpulkan', 'terlambat'])
            ->pluck('tugas_id');

        // Mendapatkan daftar kuis yang sudah dijawab
        $answeredKuisIds = HasilKuis::where('siswa_id', $siswaId)
            ->where('is_done', true)
            ->pluck('kuis_id');

        // Mendapatkan tugas yang memiliki deadline di masa depan
        $tugasDeadline = PertemuanTugas::with('tugas', 'pembelajaran')
            ->whereIn('pembelajaran_id', $enrolledPembelajaranIds)
            ->where('deadline', '>=', Carbon::now()) 
            ->whereNotIn('tugas_id', $submittedTugasIds)
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        // Mendapatkan kuis yang memiliki deadline di masa depan
        $kuisDeadline = PertemuanKuis::with('kuis', 'pembelajaran')
            ->whereIn('pembelajaran_id', $enrolledPembelajaranIds)
            ->where('deadline', '>=', Carbon::now()) 
            ->whereNotIn('kuis_id', $answeredKuisIds)
            ->orderBy('deadline')
            ->limit(5)
            ->get();



        // === STATUS PEMBELAJARAN TERAKHIR ===
        $statusPembelajaran = UserActivity::where('user_id', Auth::id())
            ->orderBy('performed_at', 'desc')
            ->limit(10)
            ->get();


        // === PROFIL SEKOLAH ===
        $profileSekolah = ProfilSekolah::first();

        return view('pages.siswa.dashboard.index', compact(
            'profileSekolah',
            'progressList',
            'pembelajaranKey',
            'pembelajaranList',
            'tugasDeadline',
            'kuisDeadline',
            'statusPembelajaran'
        ));
    }
}
