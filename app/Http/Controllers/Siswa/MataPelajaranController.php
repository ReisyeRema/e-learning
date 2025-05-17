<?php

namespace App\Http\Controllers\Siswa;

use Carbon\Carbon;
use App\Models\Tugas;
use App\Models\Absensi;
use App\Models\Enrollments;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Models\DetailAbsensi;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Google\Service\Drive as Google_Service_Drive;
use Google\Service\Drive\DriveFile as Google_Service_Drive_DriveFile;
use Google\Client as Google_Client;


class MataPelajaranController extends Controller
{

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
        // $absensiMasihAktif = false;
        // if ($absensiAktif) {
        //     $sekarang = Carbon::now();
        //     $jamSelesai = Carbon::parse($absensiAktif->jam_selesai);
        //     $absensiMasihAktif = $sekarang->lessThanOrEqualTo($jamSelesai);
        // }

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


        $profileSekolah = ProfilSekolah::first();

        return view('pages.siswa.mataPelajaran.show', compact('pembelajaran', 'tugas', 'profileSekolah', 'absensiAktif', 'detailAbsensi', 'riwayatAbsensi', 'absensiMasihAktif', 'waktuAbsensiBelumDimulai'));
    }


    public function lakukanAbsensi(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|in:Hadir,Izin,Sakit,Alfa',
            'surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $absensi = Absensi::findOrFail($id);
        $fileId = null;

        if (in_array($request->keterangan, ['Izin', 'Sakit']) && $request->hasFile('surat')) {
            $uploadedFile = $request->file('surat');
            $extension = $uploadedFile->getClientOriginalExtension();
            $newFileName = 'Surat-' . Auth::user()->name . '-' . now()->format('YmdHis') . '.' . $extension;

            // Ambil ID folder utama dari .env, misal: GOOGLE_DRIVE_FOLDER=ID_FOLDER_UTAMA
            $parentFolderId = env('GOOGLE_DRIVE_FOLDER');

            // Buat atau cari folder "Surat Absensi" di dalam folder utama
            $folderId = $this->getOrCreateFolder('Surat Absensi', $parentFolderId);

            // Upload file ke Google Drive
            $fileId = $this->uploadFileToDrive($uploadedFile, $newFileName, $folderId);

            if (!$fileId) {
                return redirect()->back()->withErrors(['surat' => 'Gagal mengunggah surat ke Google Drive.']);
            }
        }

        DetailAbsensi::updateOrCreate(
            [
                'absensi_id' => $absensi->id,
                'siswa_id' => Auth::id(),
            ],
            [
                'keterangan' => $request->keterangan,
                'surat' => $fileId, // Simpan file ID Google Drive
            ]
        );

        return redirect()->back()->with('success', 'Absensi berhasil dilakukan.');
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
            return $results->getFiles()[0]->getId(); // Folder sudah ada, ambil ID-nya
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

    // public function getAbsensiByPertemuan($pertemuan_id)
    // {
    //     $pembelajaran_id = request()->query('pembelajaran_id');

    //     $absensi = DetailAbsensi::where('pertemuan_id', $pertemuan_id)
    //         ->where('pembelajaran_id', $pembelajaran_id)
    //         ->with('absensi')
    //         ->get();

    //     return response()->json($absensi);
    // }



    protected function logActivity($activity, $details = '')
    {
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'details' => $details,
        ]);
    }
}
