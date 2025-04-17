<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Tugas;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TugasSiswaController extends Controller
{
    public function index()
    {
        $siswaId = Auth::id();

        // Ambil semua pertemuanTugas yang berasal dari pembelajaran yang di-enroll siswa
        $pertemuanTugasList = \App\Models\PertemuanTugas::with(['tugas', 'pembelajaran.kelas', 'pembelajaran.tahunAjaran'])
            ->whereHas('pembelajaran.enrollments', function ($query) use ($siswaId) {
                $query->where('siswa_id', $siswaId);
            })
            ->with(['tugas.submitTugas' => function ($query) use ($siswaId) {
                $query->where('siswa_id', $siswaId);
            }])
            ->get()
            ->sortByDesc('created_at'); // Atau bisa juga berdasarkan deadline

        $profileSekolah = ProfilSekolah::first();

        return view('pages.siswa.tugas.index', compact('pertemuanTugasList', 'profileSekolah'));
    }
}
