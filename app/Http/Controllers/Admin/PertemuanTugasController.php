<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\PertemuanTugas;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PertemuanTugasRequest;

class PertemuanTugasController extends Controller
{
    public function store(PertemuanTugasRequest $request, $pembelajaran_id)
    {
        $data['deadline'] = \Carbon\Carbon::parse($request->deadline)->format('Y-m-d H:i:s');

        $validatedData = $request->validated();
        $validatedData['pembelajaran_id'] = $pembelajaran_id;

        // Ambil data pembelajaran berdasarkan ID
        $pembelajaran = Pembelajaran::with(['kelas', 'tahunAjaran'])->findOrFail($pembelajaran_id);

        // Buat slug untuk mapel dan kelas
        $mapelSlug = Str::slug($pembelajaran->nama_mapel);
        $kelasSlug = Str::slug($pembelajaran->kelas->nama_kelas);
        $tahunAjaranSlug = str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun);

        // Simpan data ke database
        PertemuanTugas::create($validatedData);

        // Redirect ke route yang sesuai dengan slug
        return redirect()->route('submit-tugas.show', [
            'mapel' => $mapelSlug,
            'kelas' => $kelasSlug,
            'tahunAjaran' => $tahunAjaranSlug
        ])->with('success', 'Data berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        try {
            $pertemuanTugas = PertemuanTugas::findOrFail($id);
            $pertemuanTugas->delete();

            return response()->json(['success' => true, 'message' => 'Materi berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus materi.'], 500);
        }
    }
}
