<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\Materi;
use App\Models\Pertemuan;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\PertemuanTugas;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\TugasRequest;
use App\Http\Requests\Admin\MateriRequest;
use Google\Client as Google_Client;
use Google\Service\Drive as Google_Service_Drive;
use Google\Service\Drive\DriveFile as Google_Service_Drive_DriveFile;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with('materi')->where('user_id', Auth::id())->get();
        $materi = Materi::where('user_id', Auth::id())->get();
        return view('pages.admin.tugas.index', compact('tugas', 'materi'));
    }

    // public function store(TugasRequest $request)
    // {
    //     // Pastikan file ada
    //     if (!$request->hasFile('file_path')) {
    //         return redirect()->back()->withErrors(['file_path' => 'File materi harus diunggah!']);
    //     }

    //     $uploadedFile = $request->file('file_path');
    //     $extension = $uploadedFile->getClientOriginalExtension();
    //     $newFileName = $request->judul . '.' . now()->timestamp . '.' . $extension;

    //     // Simpan file ke dalam direktori storage/app/public/materi
    //     $uploadedFile->storeAs('tugas', $newFileName, 'public');

    //     // Simpan ke database
    //     Tugas::create([
    //         'user_id' => Auth::id(),
    //         'materi_id' => $request->materi_id,
    //         'judul' => $request->judul,
    //         'deskripsi' => $request->deskripsi,
    //         'file_path' => 'tugas/' . $newFileName,
    //         'mime_type' => $uploadedFile->getClientMimeType(),
    //         'file_size' => $uploadedFile->getSize(),
    //     ]);

    //     return redirect()->route('tugas.index')->with('success', 'Tugas Berhasil Ditambah');
    // }

    public function store(TugasRequest $request)
    {
        if (!$request->hasFile('file_path')) {
            return redirect()->back()->withErrors(['file_path' => 'File materi harus diunggah!']);
        }

        $uploadedFile = $request->file('file_path');
        $extension = $uploadedFile->getClientOriginalExtension();
        $newFileName = $request->judul . '-' . now()->timestamp . '.' . $extension;

        // Ambil ID folder utama (E-Learning) dari .env
        $eLearningFolderId = env('GOOGLE_DRIVE_FOLDER');

        // Dapatkan atau buat folder "Materi" di dalam "E-Learning"
        $materiFolderId = $this->getOrCreateFolder('Tugas', $eLearningFolderId);

        // Upload file ke folder "Materi"
        $fileId = $this->uploadFileToDrive($uploadedFile, $newFileName, $materiFolderId);

        if (!$fileId) {
            return redirect()->back()->withErrors(['file_path' => 'Gagal mengunggah file ke Google Drive.']);
        }

        // Simpan informasi file di database
        Tugas::create([
            'user_id' => Auth::id(),
            'materi_id' => $request->materi_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $fileId, // Simpan File ID saja
            'mime_type' => $uploadedFile->getClientMimeType(),
            'file_size' => $uploadedFile->getSize(),
        ]);

        return redirect()->route('tugas.index')->with('success', 'Materi Berhasil Ditambah');
    }

    // public function update(TugasRequest $request, $id)
    // {
    //     $tugas = Tugas::findOrFail($id);

    //      // Cek apakah yang mengakses adalah pemilik materi
    //      if (Auth::id() !== $tugas->user_id) {
    //         return redirect()->back()->withErrors(['unauthorized' => 'Anda tidak memiliki izin untuk mengedit materi ini.']);
    //     }

    //     // Cek apakah ada file baru yang diunggah
    //     if ($request->hasFile('file_path')) {
    //         // Hapus file lama jika ada
    //         if ($tugas->file_path && Storage::disk('public')->exists($tugas->file_path)) {
    //             Storage::disk('public')->delete($tugas->file_path);
    //         }

    //         // Upload file baru
    //         $uploadedFile = $request->file('file_path');
    //         $extension = $uploadedFile->getClientOriginalExtension();
    //         $newFileName = $request->judul . '.' . now()->timestamp . '.' . $extension;

    //         // Simpan file baru ke dalam direktori storage/app/public/tugas
    //         $uploadedFile->storeAs('tugas', $newFileName, 'public');

    //         // Update data dengan file baru
    //         $tugas->update([
    //             'materi_id' => $request->materi_id,
    //             'judul' => $request->judul,
    //             'deskripsi' => $request->deskripsi,
    //             'file_path' => 'tugas/' . $newFileName,
    //             'mime_type' => $uploadedFile->getClientMimeType(),
    //             'file_size' => $uploadedFile->getSize(),
    //         ]);
    //     } else {
    //         // Jika file tidak diubah, hanya update judul dan deskripsi
    //         $tugas->update([
    //             'materi_id' => $request->materi_id,
    //             'judul' => $request->judul,
    //             'deskripsi' => $request->deskripsi,
    //         ]);
    //     }

    //     return redirect()->route('tugas.index')->with('success', 'Tugas Berhasil Diupdate');
    // }


    public function update(TugasRequest $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

        // Cek apakah yang mengakses adalah pemilik tugas
        if (Auth::id() !== $tugas->user_id) {
            return redirect()->back()->withErrors(['unauthorized' => 'Anda tidak memiliki izin untuk mengedit tugas ini.']);
        }

        $data = [
            'materi_id' => $request->materi_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ];

        // Cek apakah ada file baru yang diunggah
        if ($request->hasFile('file_path')) {
            $uploadedFile = $request->file('file_path');
            $extension = $uploadedFile->getClientOriginalExtension();
            $newFileName = $request->judul . '-' . now()->timestamp . '.' . $extension;

            // Ambil ID folder utama dari .env
            $eLearningFolderId = env('GOOGLE_DRIVE_FOLDER');

            // Dapatkan atau buat folder "tugas" di dalam "E-Learning"
            $tugasFolderId = $this->getOrCreateFolder('Tugas', $eLearningFolderId);

            // Jika ada file lama, update isinya daripada menghapus dan mengunggah ulang
            if ($tugas->file_path) {
                $updated = $this->updateFileOnDrive($tugas->file_path, $uploadedFile);

                if (!$updated) {
                    return redirect()->back()->withErrors(['file_path' => 'Gagal memperbarui file di Google Drive.']);
                }
            } else {
                // Jika tidak ada file lama, upload file baru
                $newFileId = $this->uploadFileToDrive($uploadedFile, $newFileName, $tugasFolderId);
                if (!$newFileId) {
                    return redirect()->back()->withErrors(['file_path' => 'Gagal mengunggah file ke Google Drive.']);
                }
                $data['file_path'] = $newFileId;
            }

            $data['mime_type'] = $uploadedFile->getClientMimeType();
            $data['file_size'] = $uploadedFile->getSize();
        }

        // Update data tugas
        $tugas->update($data);

        return redirect()->route('tugas.index')->with('success', 'Tugas Berhasil Diupdate');
    }


    // public function destroy($id)
    // {
    //     $tugas = Tugas::findOrFail($id);

    //     // Pastikan hanya pemilik materi yang bisa menghapus
    //     if (Auth::id() !== $tugas->user_id) {
    //         return redirect()->back()->withErrors(['unauthorized' => 'Anda tidak memiliki izin untuk menghapus materi ini.']);
    //     }

    //     // Hapus file jika ada
    //     if ($tugas->file_path && Storage::disk('public')->exists($tugas->file_path)) {
    //         Storage::disk('public')->delete($tugas->file_path);
    //     }

    //     // Hapus data dari database
    //     $tugas->delete();

    //     return redirect()->route('tugas.index')->with('success', 'Tugas Berhasil Dihapus');
    // }


    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);

        // Pastikan hanya pemilik tugas yang bisa menghapus
        if (Auth::id() !== $tugas->user_id) {
            return redirect()->back()->withErrors(['unauthorized' => 'Anda tidak memiliki izin untuk menghapus tugas ini.']);
        }

        // Hapus file dari Google Drive jika ada
        if ($tugas->file_path) {
            $deleted = $this->deleteFileFromDrive($tugas->file_path);

            if (!$deleted) {
                return redirect()->back()->withErrors(['file_path' => 'Gagal menghapus file dari Google Drive.']);
            }
        }

        // Hapus data dari database
        $tugas->delete();

        return redirect()->route('tugas.index')->with('success', 'Tugas Berhasil Dihapus');
    }


    public function show($mapel, $kelas, $tahunAjaran, $semester)
    {
        // Ubah slug kembali ke format nama asli
        $mapelNama = Str::title(str_replace('-', ' ', $mapel));
        $kelasNama = Str::upper(str_replace('-', ' ', $kelas));
        $semesterNama = Str::upper(str_replace('-', ' ', $semester));

        // Ubah "2023-2024" kembali menjadi "2023/2024" agar cocok dengan database
        $tahunAjaranFormatted = str_replace('-', '/', $tahunAjaran);

        // Cari kelas berdasarkan nama
        $kelasData = Kelas::whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas])->firstOrFail();

        // Cari pembelajaran yang sesuai
        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->where('kelas_id', $kelasData->id)
            ->where('semester', $semesterNama)
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaranFormatted) {
                $query->where('nama_tahun', $tahunAjaranFormatted);
            })
            ->firstOrFail();

        // menampilakn select tambah materi di pertemuan materi
        $pertemuanSemua = Pertemuan::all();
        $tugas = Tugas::where('user_id', Auth::id())->get();

        // Ambil hanya pertemuan yang ada di tabel pertemuan_materi
        $pertemuanIds = PertemuanTugas::where('pembelajaran_id', $pembelajaran->id)
            ->pluck('pertemuan_id')
            ->unique(); // Hindari duplikasi

        // Ambil detail pertemuan yang sesuai, lalu urutkan berdasarkan id
        $pertemuan = Pertemuan::whereIn('id', $pertemuanIds)
            ->orderBy('id', 'asc')
            ->get();

        return view('pages.admin.tugas.show', compact('pembelajaran', 'kelasData', 'tugas', 'pertemuanSemua', 'pertemuan','pertemuanIds'));
    }

    // klik pertemuan agar tampil tugas
    public function getTugasByPertemuan($pertemuan_id)
    {
        $pembelajaran_id = request()->query('pembelajaran_id');

        $tugas = PertemuanTugas::where('pertemuan_id', $pertemuan_id)
            ->where('pembelajaran_id', $pembelajaran_id) // filter tambahan
            ->with('tugas')
            ->get();

        return response()->json($tugas);
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


    private function updateFileOnDrive($fileId, $file)
    {
        try {
            $client = new Google_Client();
            $client->setAuthConfig(storage_path('app/google/credentials.json'));
            $client->setScopes([Google_Service_Drive::DRIVE_FILE]);

            $service = new Google_Service_Drive($client);

            $fileMetadata = new Google_Service_Drive_DriveFile();

            $content = file_get_contents($file->getPathname());

            $updatedFile = $service->files->update(
                $fileId,
                $fileMetadata,
                [
                    'data' => $content,
                    'mimeType' => $file->getClientMimeType(),
                    'uploadType' => 'multipart',
                    'fields' => 'id'
                ]
            );

            return $updatedFile->id ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }


    private function deleteFileFromDrive($fileId)
    {
        try {
            $client = new Google_Client();
            $client->setAuthConfig(storage_path('app/google/credentials.json'));
            $client->setScopes([Google_Service_Drive::DRIVE_FILE]);

            $service = new Google_Service_Drive($client);
            $service->files->delete($fileId);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
