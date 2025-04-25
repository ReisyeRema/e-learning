<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Kuis;
use App\Models\SoalKuis;
use App\Models\HasilKuis;
use App\Models\JawabanKuis;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Models\PertemuanKuis;
use App\Models\ProfilSekolah;
use App\Models\SiswaKuisSession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KuisSiswaController extends Controller
{
    // public function action($mapel, $kelas, $tahunAjaran, $judulKuis)
    // {
    //     // Cari pembelajaran berdasarkan URL slug
    //     $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
    //         ->whereHas('kelas', function ($query) use ($kelas) {
    //             $query->whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas]);
    //         })
    //         ->whereHas('tahunAjaran', function ($query) use ($tahunAjaran) {
    //             $query->whereRaw("REPLACE(nama_tahun, '/', '-') = ?", [$tahunAjaran]);
    //         })
    //         ->firstOrFail();

    //     $kuis = Kuis::with('soalKuis')
    //         ->where('judul', str_replace('-', ' ', $judulKuis))
    //         ->firstOrFail();

    //     return view('pages.siswa.kuis.action', [
    //         'pembelajaran' => $pembelajaran,
    //         'kuis' => $kuis,
    //         'soalKuis' => $kuis->soalKuis,
    //     ]);
    // }

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

        // Ambil kuis berdasarkan judul
        $kuis = Kuis::with('soalKuis')->where('judul', str_replace('-', ' ', $judulKuis))->firstOrFail();

        // Cari pertemuan kuis terkait (jika ada)
        $pertemuanKuis = PertemuanKuis::where('kuis_id', $kuis->id)
            ->where('pembelajaran_id', $pembelajaran->id)
            ->first();

        if (!$pertemuanKuis) {
            abort(404, 'Pertemuan kuis tidak ditemukan.');
        }

        // Cek apakah siswa sudah memiliki token aktif
        $session = SiswaKuisSession::where('pertemuan_kuis_id', $pertemuanKuis->id)
            ->where('siswa_id', Auth::id())
            ->first();

        if (!$session || !$session->token) {
            return redirect()->route('mata-pelajaran.show', [
                'mapel' => $mapel,
                'kelas' => $kelas,
                'tahunAjaran' => $tahunAjaran,
            ])->with('token_error', 'Silakan masukkan token terlebih dahulu.');
        }

        return view('pages.siswa.kuis.action', [
            'pembelajaran' => $pembelajaran,
            'kuis' => $kuis,
            'soalKuis' => $kuis->soalKuis,
        ]);
    }


    public function kumpulkan(Request $request)
    {
        $request->validate([
            'kuis_id' => 'required|exists:kuis,id',
        ]);

        $userId = Auth::id();
        $kuisId = $request->kuis_id;
        $jumlahBenar = 0;
        $jumlahSoal = 0;
        $jawabanJSON = [];

        foreach ($request->all() as $key => $jawabanUser) {
            if (Str::startsWith($key, 'soal_')) {
                $soalId = str_replace('soal_', '', $key);
                $soal = SoalKuis::find($soalId);

                if (!$soal || is_null($jawabanUser)) {
                    continue;
                }

                $jumlahSoal++; // hitung total soal yang dijawab
                $isCorrect = false;

                if ($soal->type_soal === 'Essay') {
                    $isCorrect = strtolower(trim($soal->jawaban_benar)) === strtolower(trim($jawabanUser));
                } elseif (in_array($soal->type_soal, ['Objective', 'TrueFalse'])) {
                    $isCorrect = $soal->jawaban_benar == $jawabanUser;
                }

                if ($isCorrect) {
                    $jumlahBenar++;
                }

                $jawabanJSON[$soalId] = [
                    'jawaban' => $jawabanUser,
                    'status_jawaban' => $isCorrect
                ];
            }
        }

        // Hitung skor sebagai persentase
        $skorTotal = $jumlahSoal > 0 ? round(($jumlahBenar / $jumlahSoal) * 100) : 0;

        // Simpan ke tabel hasil_kuis
        HasilKuis::updateOrCreate(
            [
                'kuis_id' => $kuisId,
                'siswa_id' => $userId
            ],
            [
                'jawaban_user' => json_encode($jawabanJSON),
                'skor_total' => $skorTotal,
                'is_done' => true
            ]
        );

        // jam selesai
        $pertemuanId = PertemuanKuis::where('kuis_id', $kuisId)->value('id');

        if ($pertemuanId) {
            SiswaKuisSession::where('pertemuan_kuis_id', $pertemuanId)
                ->where('siswa_id', $userId)
                ->update([
                    'jam_selesai' => now()->format('H:i:s'),
                ]);
        }

        return redirect()->route('list-kuis.index')->with('success', 'Jawaban berhasil dikumpulkan!');
    }


    // public function index()
    // {
    //     $siswaId = Auth::id();

    //     $kuisList = Kuis::whereHas('pertemuanKuis.pembelajaran.enrollments', function ($query) use ($siswaId) {
    //         $query->where('siswa_id', $siswaId);
    //     })
    //         ->with(['pertemuanKuis.pembelajaran' => function ($query) {
    //             $query->withCount('enrollments')
    //                 ->withCount('pertemuanMateri');
    //         }, 'hasilKuis' => function ($query) use ($siswaId) {
    //             $query->where('siswa_id', $siswaId);
    //         }])
    //         ->get()
    //         ->sortByDesc(function ($tugas) {
    //             return optional($tugas->pertemuanKuis->sortByDesc('created_at')->first())->created_at;
    //         });

    //     $profileSekolah = ProfilSekolah::first();

    //     return view('pages.siswa.kuis.index', compact('kuisList', 'profileSekolah'));
    // }

    public function index()
    {
        $this->logActivity('Mengakses List Kuis', 'User membuka halaman list Kuis');

        $siswaId = Auth::id();

        // Ambil semua pertemuanTugas yang berasal dari pembelajaran yang di-enroll siswa
        $pertemuanKuisList = \App\Models\PertemuanKuis::with(['kuis', 'pembelajaran.kelas', 'pembelajaran.tahunAjaran'])
            ->whereHas('pembelajaran.enrollments', function ($query) use ($siswaId) {
                $query->where('siswa_id', $siswaId);
            })
            ->with(['kuis.hasilKuis' => function ($query) use ($siswaId) {
                $query->where('siswa_id', $siswaId);
            }])
            ->get()
            ->sortByDesc('created_at'); // Atau bisa juga berdasarkan deadline

        $profileSekolah = ProfilSekolah::first();

        return view('pages.siswa.kuis.index', compact('pertemuanKuisList', 'profileSekolah'));
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
