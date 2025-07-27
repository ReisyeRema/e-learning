<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kuis;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\HasilKuis;
use App\Models\WaliKelas;
use App\Models\Enrollments;
use App\Models\SubmitTugas;
use App\Models\TahunAjaran;
use App\Models\Pembelajaran;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Models\DetailAbsensi;
use App\Models\PertemuanKuis;
use App\Models\PertemuanTugas;
use App\Models\PertemuanMateri;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** SUPER ADMIN*/
        $roles = Role::all();
        $roleNames = [];
        $userCounts = [];

        foreach ($roles as $role) {
            $roleNames[] = $role->name;
            $userCounts[] = User::role($role->name)->count();
        }

        // Ambil data login per hari untuk 7 hari terakhir
        $loginPerDay = DB::table('login_activities')
            ->select(DB::raw('DATE(login_at) as date'), DB::raw('count(*) as total'))
            ->where('login_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = $loginPerDay->pluck('date');
        $totals = $loginPerDay->pluck('total');

        // Ambil total login per role untuk 7 hari terakhir
        $loginByRole = DB::table('login_activities')
            ->join('users', 'login_activities.user_id', '=', 'users.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name as role', DB::raw('count(*) as total'))
            ->where('login_at', '>=', Carbon::now()->startOfWeek())
            ->groupBy('roles.name')
            ->orderByDesc('total')
            ->get();

        $loginRoleLabels = $loginByRole->pluck('role');
        $loginRoleCounts = $loginByRole->pluck('total');


        /** ADMIN*/

        // Get counts for Kurikulum, Kelas, Guru, Siswa, Pembelajaran
        $kurikulumCount = \App\Models\Kurikulum::count();
        $kelasCount = \App\Models\Kelas::count();
        $guruCount = \App\Models\Guru::count();
        $siswaCount = \App\Models\Siswa::count();
        $pembelajaranCount = \App\Models\Pembelajaran::count();



        // Get Pembelajaran Aktif per Rentang Tahun Ajaran
        $tahunAjarans = \App\Models\TahunAjaran::orderBy('id')->get();

        // Mendapatkan ID tahun awal dan tahun akhir dari input
        $tahunAwalId = $request->input('tahun_awal');
        $tahunAkhirId = $request->input('tahun_akhir');

        // Inisialisasi array untuk menyimpan data pembelajaran per tahun
        $pembelajaranAktifPerTahun = [];

        if ($tahunAwalId && $tahunAkhirId) {
            // Ambil tahun ajaran dalam rentang yang dipilih
            $tahunAjaranRange = \App\Models\TahunAjaran::whereBetween('id', [$tahunAwalId, $tahunAkhirId])
                ->orderBy('id')
                ->get();

            // Ambil jumlah pembelajaran aktif per tahun dalam rentang yang dipilih
            foreach ($tahunAjaranRange as $tahun) {
                $jumlah = \App\Models\Pembelajaran::where('tahun_ajaran_id', $tahun->id)->count();
                $pembelajaranAktifPerTahun[] = [
                    'nama' => $tahun->nama_tahun,
                    'jumlah' => $jumlah,
                ];
            }
        }

        $tahunAjarans = TahunAjaran::all();
        // Jika ada filter tahun_ajaran, ambil data pembelajaran sesuai tahun ajaran
        $tahunAjaranId = $request->input('tahun_ajaran');
        $pembelajaranQuery = Pembelajaran::query();
        if ($tahunAjaranId) {
            $pembelajaranQuery->where('tahun_ajaran_id', $tahunAjaranId);
        }
        // Ambil jumlah pembelajaran aktif berdasarkan tahun ajaran yang dipilih
        $pembelajaranAktifCount = $pembelajaranQuery->count();


        // Get Distribusi Guru Permapel
        $kelas = Kelas::all();
        $kelasId = $request->input('kelas');
        if ($kelasId) {
            $pembelajaranQuery->where('kelas_id', $kelasId);
        }
        // Mengambil data jumlah guru per mata pelajaran
        $guruPerMapel = $pembelajaranQuery->select('nama_mapel', DB::raw('count(distinct guru_id) as guru_count'))
            ->groupBy('nama_mapel')
            ->get();

        // Menyusun data untuk chart
        $labels = $guruPerMapel->pluck('nama_mapel')->toArray();
        $data = $guruPerMapel->pluck('guru_count')->toArray();

        // Ambil aktivitas pengguna terakhir
        $activities = UserActivity::where('user_id', Auth::id())
            ->orderBy('performed_at', 'desc')
            ->limit(10)
            ->get();



        /** Guru*/
        // Jumlah Materi, tugas, kuis
        $guruId = Auth::id();
        $pembelajaranList = Pembelajaran::with('kelas', 'tahunAjaran')
            ->where('guru_id', $guruId)
            ->get();

        $pembelajaranFilter = $request->input('pembelajaran');

        $pembelajaranQuery = Pembelajaran::where('guru_id', $guruId);

        if ($pembelajaranFilter) {
            $pembelajaranQuery->where('id', $pembelajaranFilter);
        }

        $pembelajaranIds = $pembelajaranQuery->pluck('id');

        $jumlahMateri = PertemuanMateri::whereIn('pembelajaran_id', $pembelajaranIds)->count();
        $jumlahTugas = PertemuanTugas::whereIn('pembelajaran_id', $pembelajaranIds)->count();
        $jumlahKuis = PertemuanKuis::whereIn('pembelajaran_id', $pembelajaranIds)->count();


        // Progress tugas dan kuis
        $pertemuanId = $request->input('pertemuan');

        $pertemuanTugasQuery = PertemuanTugas::with('tugas')
            ->whereIn('pembelajaran_id', $pembelajaranIds);
        $pertemuanKuisQuery = PertemuanKuis::with('kuis')
            ->whereIn('pembelajaran_id', $pembelajaranIds);

        if ($pertemuanId) {
            $pertemuanTugasQuery->where('pertemuan_id', $pertemuanId);
            $pertemuanKuisQuery->where('pertemuan_id', $pertemuanId);
        }

        $tugasList = $pertemuanTugasQuery->get();
        $kuisList = $pertemuanKuisQuery->get();

        $progressTugas = $tugasList->map(function ($item) {
            $terkumpul = SubmitTugas::where('tugas_id', $item->tugas_id)->count();
            $total = Enrollments::where('pembelajaran_id', $item->pembelajaran_id)->count();
            return [
                'nama' => 'Tugas ' . $item->tugas->judul,
                'terkumpul' => $terkumpul,
                'total' => $total,
            ];
        });

        $progressKuis = $kuisList->map(function ($item) {
            $terkumpul = HasilKuis::where('kuis_id', $item->kuis_id)->count();
            $total = Enrollments::where('pembelajaran_id', $item->pembelajaran_id)->count();
            return [
                'nama' => 'Kuis ' . $item->kuis->judul,
                'terkumpul' => $terkumpul,
                'total' => $total,
            ];
        });


        // Ambil deadline tugas terdekat (dalam 7 hari)
        $now = Carbon::now();
        $upcomingTugas = PertemuanTugas::with('tugas', 'pembelajaran.kelas')
            ->whereBetween('deadline', [$now, $now->copy()->addDays(7)])
            ->whereIn('pembelajaran_id', $pembelajaranIds)
            ->get();


        // Ambil deadline kuis terdekat (dalam 7 hari)
        $upcomingKuis = PertemuanKuis::with('kuis', 'pembelajaran.kelas')
            ->whereBetween('deadline', [$now, $now->copy()->addDays(7)])
            ->whereIn('pembelajaran_id', $pembelajaranIds)
            ->get();








        /** Wali Kelas */
        $user = Auth::user();

        $daftarAmpu = WaliKelas::with(['kelas', 'tahunAjaran'])
            ->where('guru_id', $user->id)
            ->get();

        $kelasId = $request->kelas_id;
        $tahunAjaranId = $request->tahun_ajaran_id;

        $waliKelas = WaliKelas::where('guru_id', $user->id)
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->first();

        // Inisialisasi default agar tidak error meskipun $waliKelas null
        $laki = $perempuan = $jumlahSiswa = $jumlahMapel = 0;
        $namaKelas = '-';
        $progresTugas = $progresKuis = $totalTugas = $totalKuis = $dikerjakanTugas = $dikerjakanKuis = 0;

        $kehadiranChartData = [
            'labels' => [],
            'hadir' => [],
            'izin' => [],
            'sakit' => [],
            'alpha' => []
        ];

        if ($waliKelas) {
            $kelasId = $waliKelas->kelas_id;
            $tahunAjaranId = $waliKelas->tahun_ajaran_id;

            // Nama kelas
            $namaKelas = optional($waliKelas->kelas)->nama_kelas ?? '-';

            // Jumlah siswa dan gender
            $jumlahSiswa = Siswa::where('kelas_id', $kelasId)->count();
            $laki = Siswa::where('kelas_id', $kelasId)->where('jenis_kelamin', 'Laki-Laki')->count();
            $perempuan = Siswa::where('kelas_id', $kelasId)->where('jenis_kelamin', 'Perempuan')->count();

            // Mapel
            $jumlahMapel = Pembelajaran::where('kelas_id', $kelasId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->count();

            $pembelajarans = Pembelajaran::where('kelas_id', $kelasId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->get();

            // Statistik Kehadiran
            $labels = ['Jumlah Kehadiran'];
            $hadirCount = $izinCount = $sakitCount = $alphaCount = 0;

            foreach ($pembelajarans as $pembelajaran) {
                $absensiData = DetailAbsensi::whereHas('absensi', function ($q) use ($pembelajaran) {
                    $q->where('pembelajaran_id', $pembelajaran->id);
                })->whereHas('siswa.siswa', function ($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                })->get();

                $hadirCount += $absensiData->where('keterangan', 'Hadir')->count();
                $izinCount  += $absensiData->where('keterangan', 'Izin')->count();
                $sakitCount += $absensiData->where('keterangan', 'Sakit')->count();
                $alphaCount += $absensiData->where('keterangan', 'Alpha')->count();
            }

            $kehadiranChartData = [
                'labels' => $labels,
                'hadir' => [$hadirCount],
                'izin' => [$izinCount],
                'sakit' => [$sakitCount],
                'alpha' => [$alphaCount],
            ];


            // Progress Tugas & Kuis
            $siswaIds = Siswa::where('kelas_id', $kelasId)->pluck('user_id');
            $pembelajaranIds = $pembelajarans->pluck('id');

            $tugasIds = PertemuanTugas::whereIn('pembelajaran_id', $pembelajaranIds)->pluck('tugas_id');
            $kuisIds  = PertemuanKuis::whereIn('pembelajaran_id', $pembelajaranIds)->pluck('kuis_id');

            $totalTugas = Tugas::whereIn('id', $tugasIds)->count();
            $totalKuis  = Kuis::whereIn('id', $kuisIds)->count();

            $dikerjakanTugas = SubmitTugas::whereIn('siswa_id', $siswaIds)
                ->whereIn('tugas_id', $tugasIds)
                ->count();

            $dikerjakanKuis = HasilKuis::whereIn('siswa_id', $siswaIds)
                ->whereIn('kuis_id', $kuisIds)
                ->where('is_done', true)
                ->count();

            $maxTugas = $totalTugas * max($siswaIds->count(), 1);
            $maxKuis = $totalKuis * max($siswaIds->count(), 1);

            $progresTugas = $maxTugas > 0 ? round(($dikerjakanTugas / $maxTugas) * 100) : 0;
            $progresKuis  = $maxKuis > 0 ? round(($dikerjakanKuis / $maxKuis) * 100) : 0;
        }


        return view('dashboard', compact(
            'roleNames',
            'dates',
            'totals',
            'userCounts',
            'kurikulumCount',
            'kelasCount',
            'guruCount',
            'siswaCount',
            'pembelajaranCount',
            'tahunAjarans',
            'pembelajaranAktifPerTahun',
            'kelas',
            'labels',
            'data',
            'pembelajaranList',
            'jumlahMateri',
            'jumlahTugas',
            'jumlahKuis',
            'progressTugas',
            'progressKuis',
            'upcomingTugas',
            'upcomingKuis',
            'loginRoleLabels',
            'loginRoleCounts',
            'activities',
            'jumlahSiswa',
            'namaKelas',
            'laki',
            'perempuan',
            'kehadiranChartData',
            'jumlahMapel',
            'totalTugas',
            'dikerjakanTugas',
            'progresTugas',
            'totalKuis',
            'dikerjakanKuis',
            'progresKuis',
            'daftarAmpu',
        ));
    }
}
