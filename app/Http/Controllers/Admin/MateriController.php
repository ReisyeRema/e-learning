<?php

namespace App\Http\Controllers\Admin;

use Google;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Pertemuan;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use App\Models\PertemuanMateri;
use Google\Client as Google_Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\MateriRequest;
use Google\Service\Drive as Google_Service_Drive;
use Google\Service\Drive\DriveFile as Google_Service_Drive_DriveFile;

class MateriController extends Controller
{
    public function index()
    {
        $materi = Materi::where('user_id', Auth::id())->get();
        return view('pages.admin.materi.index', compact('materi'));
    }


    // public function store(MateriRequest $request)
    // {
    //     // Pastikan file ada
    //     if (!$request->hasFile('file_path')) {
    //         return redirect()->back()->withErrors(['file_path' => 'File materi harus diunggah!']);
    //     }

    //     $uploadedFile = $request->file('file_path');
    //     $extension = $uploadedFile->getClientOriginalExtension();
    //     $newFileName = $request->judul . '.' . now()->timestamp . '.' . $extension;

    //     // Simpan file ke dalam direktori storage/app/public/materi
    //     // $uploadedFile->storeAs('materi', $newFileName, 'public');

    //     // Upload file ke Google Drive
    //     $filePath = Storage::disk('google')->putFileAs('', $uploadedFile, $newFileName);

    //     // Dapatkan link file yang diunggah
    //     $googleDriveFileUrl = "https://drive.google.com/file/d/$filePath/view";

    //     // Simpan ke database
    //     Materi::create([
    //         'user_id' => Auth::id(),
    //         'judul' => $request->judul,
    //         'deskripsi' => $request->deskripsi,
    //         'file_path' => $googleDriveFileUrl,
    //         'mime_type' => $uploadedFile->getClientMimeType(),
    //         'file_size' => $uploadedFile->getSize(),
    //     ]);

    //     return redirect()->route('materi.index')->with('success', 'Materi Berhasil Ditambah');
    // }

    // public function update(MateriRequest $request, $id)
    // {
    //     $materi = Materi::findOrFail($id);

    //     // Cek apakah yang mengakses adalah pemilik materi
    //     if (Auth::id() !== $materi->user_id) {
    //         return redirect()->back()->withErrors(['unauthorized' => 'Anda tidak memiliki izin untuk mengedit materi ini.']);
    //     }

    //     // Cek apakah ada file baru yang diunggah
    //     if ($request->hasFile('file_path')) {
    //         // Hapus file lama jika ada
    //         // if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
    //         //     Storage::disk('public')->delete($materi->file_path);
    //         // }
    //         if ($materi->file_path) {
    //             $oldFileId = basename($materi->file_path); // Ambil ID file dari URL
    //             Storage::disk('google')->delete($oldFileId);
    //         }

    //         // Upload file baru
    //         $uploadedFile = $request->file('file_path');
    //         $extension = $uploadedFile->getClientOriginalExtension();
    //         $newFileName = $request->judul . '.' . now()->timestamp . '.' . $extension;

    //         // Simpan file baru ke dalam direktori storage/app/public/materi
    //         // $uploadedFile->storeAs('materi', $newFileName, 'public');

    //         // Simpan file di Google Drive dan dapatkan ID-nya
    //         $fileId = Storage::disk('google')->putFileAs('', $uploadedFile, $newFileName);

    //         // Buat URL file baru
    //         $googleDriveFileUrl = "https://drive.google.com/file/d/$fileId/view";

    //         // Update data dengan file baru
    //         $materi->update([
    //             'judul' => $request->judul,
    //             'deskripsi' => $request->deskripsi,
    //             'file_path' => $googleDriveFileUrl,
    //             'mime_type' => $uploadedFile->getClientMimeType(),
    //             'file_size' => $uploadedFile->getSize(),
    //         ]);
    //     } else {
    //         // Jika file tidak diubah, hanya update judul dan deskripsi
    //         $materi->update([
    //             'judul' => $request->judul,
    //             'deskripsi' => $request->deskripsi,
    //         ]);
    //     }

    //     return redirect()->route('materi.index')->with('success', 'Materi Berhasil Diupdate');
    // }

    public function store(MateriRequest $request)
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
        $materiFolderId = $this->getOrCreateFolder('Materi', $eLearningFolderId);

        // Upload file ke folder "Materi"
        $fileId = $this->uploadFileToDrive($uploadedFile, $newFileName, $materiFolderId);

        if (!$fileId) {
            return redirect()->back()->withErrors(['file_path' => 'Gagal mengunggah file ke Google Drive.']);
        }

        // Simpan informasi file di database
        Materi::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $fileId, // Simpan File ID saja
            'mime_type' => $uploadedFile->getClientMimeType(),
            'file_size' => $uploadedFile->getSize(),
        ]);

        return redirect()->route('materi.index')->with('success', 'Materi Berhasil Ditambah');
    }



    public function update(MateriRequest $request, $id)
    {
        $materi = Materi::findOrFail($id);

        // Cek apakah yang mengakses adalah pemilik materi
        if (Auth::id() !== $materi->user_id) {
            return redirect()->back()->withErrors(['unauthorized' => 'Anda tidak memiliki izin untuk mengedit materi ini.']);
        }

        $data = [
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

            // Dapatkan atau buat folder "Materi" di dalam "E-Learning"
            $materiFolderId = $this->getOrCreateFolder('Materi', $eLearningFolderId);

            // Jika ada file lama, update isinya daripada menghapus dan mengunggah ulang
            if ($materi->file_path) {
                $updated = $this->updateFileOnDrive($materi->file_path, $uploadedFile);

                if (!$updated) {
                    return redirect()->back()->withErrors(['file_path' => 'Gagal memperbarui file di Google Drive.']);
                }
            } else {
                // Jika tidak ada file lama, upload file baru
                $newFileId = $this->uploadFileToDrive($uploadedFile, $newFileName, $materiFolderId);
                if (!$newFileId) {
                    return redirect()->back()->withErrors(['file_path' => 'Gagal mengunggah file ke Google Drive.']);
                }
                $data['file_path'] = $newFileId;
            }

            $data['mime_type'] = $uploadedFile->getClientMimeType();
            $data['file_size'] = $uploadedFile->getSize();
        }

        // Update data materi
        $materi->update($data);

        return redirect()->route('materi.index')->with('success', 'Materi Berhasil Diupdate');
    }




    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);

        // Pastikan hanya pemilik materi yang bisa menghapus
        if (Auth::id() !== $materi->user_id) {
            return redirect()->back()->withErrors(['unauthorized' => 'Anda tidak memiliki izin untuk menghapus materi ini.']);
        }

        // Hapus file dari Google Drive jika ada
        if ($materi->file_path) {
            $deleted = $this->deleteFileFromDrive($materi->file_path);

            if (!$deleted) {
                return redirect()->back()->withErrors(['file_path' => 'Gagal menghapus file dari Google Drive.']);
            }
        }

        // Hapus data dari database
        $materi->delete();

        return redirect()->route('materi.index')->with('success', 'Materi Berhasil Dihapus');
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
        $materi = Materi::where('user_id', Auth::id())->get();

        // Ambil hanya pertemuan yang ada di tabel pertemuan_materi
        $pertemuanIds = PertemuanMateri::where('pembelajaran_id', $pembelajaran->id)
            ->pluck('pertemuan_id')
            ->unique(); // Hindari duplikasi

        // Ambil detail pertemuan yang sesuai, lalu urutkan berdasarkan id
        $pertemuan = Pertemuan::whereIn('id', $pertemuanIds)
            ->orderBy('id', 'asc')
            ->get();

        return view('pages.admin.materi.show', compact('pembelajaran', 'kelasData', 'pertemuan', 'materi', 'pertemuanSemua'));
    }


    // klik pertemuan agar tampil materi
    public function getMateriByPertemuan($pertemuan_id)
    {
        $pembelajaran_id = request()->query('pembelajaran_id');

        $materi = PertemuanMateri::where('pertemuan_id', $pertemuan_id)
            ->where('pembelajaran_id', $pembelajaran_id)
            ->with('materi')
            ->get();

        return response()->json($materi);
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
