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

        $tugasList = Tugas::whereHas('pertemuanTugas.pembelajaran.enrollments', function ($query) use ($siswaId) {
            $query->where('siswa_id', $siswaId);
        })
            ->with(['pertemuanTugas.pembelajaran' => function ($query) {
                $query->withCount('enrollments') 
                    ->withCount('pertemuanMateri'); 
            }, 'submitTugas' => function ($query) use ($siswaId) {
                $query->where('siswa_id', $siswaId);
            }])
            ->get()
            ->sortByDesc(function ($tugas) {
                return optional($tugas->pertemuanTugas->sortByDesc('created_at')->first())->created_at;
            });
        
        $profileSekolah = ProfilSekolah::first(); 

        return view('pages.siswa.tugas.index', compact('tugasList','profileSekolah'));
    }
}
