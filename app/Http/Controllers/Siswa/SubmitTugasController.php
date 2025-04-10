<?php

namespace App\Http\Controllers\Siswa;

use App\Models\SubmitTugas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Google\Client as Google_Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Siswa\SubmitTugasRequest;
use Google\Service\Drive as Google_Service_Drive;
use Google\Service\Drive\DriveFile as Google_Service_Drive_DriveFile;


class SubmitTugasController extends Controller
{
    public function store(SubmitTugasRequest $request)
    {
        if (!$request->hasFile('file_path') && !$request->filled('url')) {
            return redirect()->back()->withErrors(['file_path' => 'Harus mengunggah file atau mengisi URL!']);
        }

        $uploadedFile = $request->file('file_path');

        // Jika hanya mengisi URL tanpa file, langsung simpan ke database
        if (!$uploadedFile) {
            SubmitTugas::create([
                'tugas_id' => $request->tugas_id,
                'siswa_id' => Auth::id(),
                'file_path' => null,
                'url' => $request->url,
                'mime_type' => null,
                'file_size' => null,
                'skor' => null,
                'status' => 'sudah_dikumpulkan',
            ]);

            return redirect()->route('list-tugas.index')->with('success', 'Tugas berhasil dikumpulkan!');
        }

        // Jika ada file yang diunggah, proses upload ke Google Drive
        $extension = $uploadedFile->getClientOriginalExtension();
        $newFileName = 'Tugas-' . now()->timestamp . '.' . $extension;

        // Ambil ID folder utama dari .env
        $eLearningFolderId = env('GOOGLE_DRIVE_FOLDER');

        // Dapatkan atau buat folder "Tugas" di dalam "E-Learning"
        $tugasFolderId = $this->getOrCreateFolder('Submit Tugas', $eLearningFolderId);

        // Upload file ke Google Drive
        $fileId = $this->uploadFileToDrive($uploadedFile, $newFileName, $tugasFolderId);

        if (!$fileId) {
            return redirect()->back()->withErrors(['file_path' => 'Gagal mengunggah file ke Google Drive.']);
        }

        // Simpan informasi tugas di database
        SubmitTugas::create([
            'tugas_id' => $request->tugas_id,
            'siswa_id' => Auth::id(),
            'file_path' => $fileId, // Simpan File ID saja
            'url' => $request->url,
            'mime_type' => $uploadedFile->getClientMimeType(),
            'file_size' => $uploadedFile->getSize(),
            'skor' => null, // Skor diisi setelah tugas dikumpul
            'status' => 'sudah_dikumpulkan',
        ]);

        return redirect()->route('list-tugas.index')->with('success', 'Tugas berhasil dikumpulkan!');
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

    public function destroy($id)
    {
        $tugas = SubmitTugas::where('tugas_id', $id)->where('siswa_id', Auth::id())->first();

        if (!$tugas) {
            return response()->json(['success' => false, 'message' => 'Tugas tidak ditemukan.'], 404);
        }

        // Cek apakah tugas memiliki relasi ke pertemuanTugas dan pembelajaran
        if (!$tugas->tugas || !$tugas->tugas->pertemuanTugas || !$tugas->tugas->pertemuanTugas->first() || !$tugas->tugas->pertemuanTugas->first()->pembelajaran) {
            return response()->json(['success' => false, 'message' => 'Data pembelajaran tidak ditemukan.'], 404);
        }

        // Ambil informasi pembelajaran sebelum tugas dihapus
        $pembelajaran = $tugas->tugas->pertemuanTugas->first()->pembelajaran;

        // Buat slug untuk mapel, kelas, dan tahun ajaran
        $mapelSlug = Str::slug($pembelajaran->nama_mapel);
        $kelasSlug = Str::slug($pembelajaran->kelas->nama_kelas);
        $tahunAjaranSlug = str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun);

        // Hapus file dari Google Drive jika ada
        if ($tugas->file_path) {
            $this->deleteFileFromDrive($tugas->file_path);
        }

        // Hapus dari database
        $tugas->delete();

        // Redirect ke route dengan slug
        return redirect()->route('mata-pelajaran.show', [
            'mapel' => $mapelSlug,
            'kelas' => $kelasSlug,
            'tahunAjaran' => $tahunAjaranSlug
        ])->with('success', 'Tugas Berhasil Dihapus');
    }


    // Fungsi untuk menghapus file dari Google Drive
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
