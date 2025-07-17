<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Tugas;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Models\PertemuanTugas;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TugasSiswaController extends Controller
{
    public function index()
    {
        $this->logActivity('Mengakses List Tugas', 'User membuka halaman list Tugas');

        $siswaId = Auth::id();

        // Ambil semua pertemuanTugas yang berasal dari pembelajaran yang di-enroll siswa
        $pertemuanTugasList = PertemuanTugas::with(['tugas', 'pembelajaran.kelas', 'pembelajaran.tahunAjaran'])
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

    protected function logActivity($activity, $details = '')
    {
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'details' => $details,
        ]);
    }
}
