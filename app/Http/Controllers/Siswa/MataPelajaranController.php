<?php

namespace App\Http\Controllers\Siswa;

use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\Absensi;
use App\Models\Enrollments;
use App\Models\TahunAjaran;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Models\DetailAbsensi;
use App\Models\ProfilSekolah;
use Illuminate\Support\Facades\DB;
use Google\Client as Google_Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Google\Service\Drive as Google_Service_Drive;
use Google\Service\Drive\DriveFile as Google_Service_Drive_DriveFile;


class MataPelajaranController extends Controller
{

    public function index(Request $request)
    {
        // Log aktivitas
        $this->logActivity('Mengakses List Mata Pelajaran', 'User membuka halaman list mata pelajaran');

        $siswaId = Auth::id();

        $enrollments = Enrollments::where('siswa_id', $siswaId)
            ->where('status', 'approved')
            ->whereHas('pembelajaran', function ($query) use ($request) {
                $query->where('aktif', true);

                if ($request->filled('mapel')) {
                    $query->where('nama_mapel', 'like', '%' . $request->mapel . '%');
                }

                if ($request->filled('kelas')) {
                    $query->where('kelas_id', $request->kelas);
                }

                if ($request->filled('tahun_ajaran')) {
                    $query->where('tahun_ajaran_id', $request->tahun_ajaran);
                }
            })
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
            ->get()
            ->sortByDesc('created_at');

        // Draft (pembelajaran tidak aktif)
        $draftEnrollments = Enrollments::where('siswa_id', $siswaId)
            ->where('status', 'approved')
            ->whereHas('pembelajaran', fn($q) => $q->where('aktif', false))
            ->with('pembelajaran.kelas', 'pembelajaran.tahunAjaran')
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

        // Ambil data untuk dropdown filter
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $tahunAjaranList = TahunAjaran::orderBy('nama_tahun', 'desc')->get();

        $profileSekolah = ProfilSekolah::first();

        return view('pages.siswa.mataPelajaran.index', compact('enrollments', 'profileSekolah', 'draftEnrollments','kelasList',
        'tahunAjaranList'));
    }


    public function show($mapel, $kelas, $tahunAjaran, $semester)
    {
        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->whereHas('kelas', function ($query) use ($kelas) {
                $query->whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas]);
            })
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaran) {
                $query->whereRaw("REPLACE(nama_tahun, '/', '-') = ?", [$tahunAjaran]);
            })
            ->where('semester', Str::upper(str_replace('-', ' ', $semester)))
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

        // Ambil absensi aktif
        $absensiAktif = Absensi::where('pembelajaran_id', $pembelajaran->id)
            ->where('tanggal', Carbon::today())
            ->where('aktif', true)
            ->first();

        // Tambahkan kondisi waktu
        $absensiMasihAktif = false;
        $waktuAbsensiBelumDimulai = false;
        if ($absensiAktif) {
            $sekarang = Carbon::now();
            $jamMulai = Carbon::parse($absensiAktif->jam_mulai);
            $jamSelesai = Carbon::parse($absensiAktif->jam_selesai);

            if ($sekarang->lessThan($jamMulai)) {
                $waktuAbsensiBelumDimulai = true;
            }

            $absensiMasihAktif = $sekarang->between($jamMulai, $jamSelesai);
        }


        // Ambil detail absensi jika sudah absen hari ini
        $detailAbsensi = null;
        if ($absensiAktif) {
            $detailAbsensi = DetailAbsensi::where('absensi_id', $absensiAktif->id)
                ->where('siswa_id', $siswaId)
                ->first();
        }

        // Ambil riwayat absensi untuk siswa dan pembelajaran ini
        $riwayatAbsensi = DetailAbsensi::with(['absensi.pertemuan'])
            ->where('siswa_id', $siswaId)
            ->whereHas('absensi', function ($query) use ($pembelajaran) {
                $query->where('pembelajaran_id', $pembelajaran->id);
            })
            ->get();

        $isAktif = $pembelajaran->aktif === 1;


        $profileSekolah = ProfilSekolah::first();

        return view('pages.siswa.mataPelajaran.show', compact('pembelajaran', 'tugas', 'profileSekolah', 'absensiAktif', 'detailAbsensi', 'riwayatAbsensi', 'absensiMasihAktif', 'waktuAbsensiBelumDimulai', 'isAktif'));
    }

    public function lakukanAbsensi(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|in:Hadir,Izin,Sakit,Alfa',
            'surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $absensi = Absensi::findOrFail($id);
        $fileId = null;

        //Jika absensi aktif dan gunakan koordinat, validasi lokasi siswa
        if ($absensi->aktif && $absensi->gunakan_koordinat && $request->keterangan === 'Hadir') {
            // Ambil koordinat dari tabel profile_sekolah
            $sekolah = DB::table('profile_sekolah')->first();

            // Jika lokasi tidak dikirim
            if (!$request->filled(['latitude', 'longitude'])) {
                session()->flash('lokasi_error', 'Lokasi tidak tersedia. Aktifkan GPS.');
                return redirect()->back();
            }

            // Hitung jarak antara lokasi siswa dan sekolah
            $jarak = $this->hitungJarakMeter(
                $sekolah->latitude,
                $sekolah->longitude,
                $request->latitude,
                $request->longitude
            );

            // Jika jarak lebih dari 15 meter
            if ($jarak > 15) {
                session()->flash('lokasi_error', 'Anda berada di luar radius 15 meter dari sekolah.');
                return redirect()->back();
            }
        }

        // Upload surat jika Izin atau Sakit
        if (in_array($request->keterangan, ['Izin', 'Sakit']) && $request->hasFile('surat')) {
            $uploadedFile = $request->file('surat');
            $extension = $uploadedFile->getClientOriginalExtension();
            $newFileName = 'Surat-' . Auth::user()->name . '-' . now()->format('YmdHis') . '.' . $extension;
            $parentFolderId = env('GOOGLE_DRIVE_FOLDER');
            $folderId = $this->getOrCreateFolder('Surat Absensi', $parentFolderId);
            $fileId = $this->uploadFileToDrive($uploadedFile, $newFileName, $folderId);

            if (!$fileId) {
                return redirect()->back()->withErrors(['surat' => 'Gagal mengunggah surat ke Google Drive.']);
            }
        }

        // Simpan ke detail_absensi
        DetailAbsensi::updateOrCreate(
            [
                'absensi_id' => $absensi->id,
                'siswa_id' => Auth::id(),
            ],
            [
                'keterangan' => $request->keterangan,
                'surat' => $fileId,
            ]
        );

        return redirect()->back()->with('success', 'Absensi berhasil dilakukan.');
    }

    // Fungsi bantu hitung jarak antara dua titik koordinat (dalam meter)
    private function hitungJarakMeter($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }



    private function uploadFileToDrive($file, $fileName, $parentFolderId)
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setScopes([Google_Service_Drive::DRIVE_FILE]);

        $service = new Google_Service_Drive($client);

        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $fileName,
            'parents' => [$parentFolderId]
        ]);

        $content = file_get_contents($file->getPathname());

        $driveFile = $service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $file->getClientMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        return $driveFile->id ?? null;
    }


    private function getOrCreateFolder($folderName, $parentFolderId)
    {
        $client = new \Google_Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setScopes([Google_Service_Drive::DRIVE]);

        $service = new Google_Service_Drive($client);

        // Cek apakah folder sudah ada
        $query = "name='$folderName' and '$parentFolderId' in parents and mimeType='application/vnd.google-apps.folder' and trashed=false";
        $results = $service->files->listFiles(['q' => $query]);

        if (count($results->getFiles()) > 0) {
            return $results->getFiles()[0]->getId();
        }

        // Jika folder belum ada, buat baru
        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentFolderId]
        ]);

        $folder = $service->files->create($fileMetadata, ['fields' => 'id']);
        return $folder->id;
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
