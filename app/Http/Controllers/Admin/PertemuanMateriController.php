<?php

namespace App\Http\Controllers\Admin;

use App\Models\Materi;
use App\Models\Pertemuan;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\PertemuanMateri;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PertemuanMateriRequest;

class PertemuanMateriController extends Controller
{

    public function store(PertemuanMateriRequest $request, $pembelajaran_id)
    {
        $validatedData = $request->validated();
        $validatedData['pembelajaran_id'] = $pembelajaran_id;

        // Ambil data pembelajaran berdasarkan ID
        $pembelajaran = Pembelajaran::with(['kelas', 'tahunAjaran'])->findOrFail($pembelajaran_id);

        // Buat slug untuk mapel dan kelas
        $mapelSlug = Str::slug($pembelajaran->nama_mapel);
        $kelasSlug = Str::slug($pembelajaran->kelas->nama_kelas);
        $tahunAjaranSlug = str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun);
        $semesterSlug = Str::slug($pembelajaran->semester);


        // Simpan data ke database
        PertemuanMateri::create($validatedData);

        // Redirect ke route yang sesuai dengan slug
        return redirect()->route('submit-materi.show', [
            'mapel' => $mapelSlug,
            'kelas' => $kelasSlug,
            'tahunAjaran' => $tahunAjaranSlug,
            'semester' => $semesterSlug
        ])->with('success', 'Data berhasil ditambahkan.');
    }


    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'judul' => 'required|string|max:255',
    //         'pertemuan_id' => 'required|exists:pertemuan,id'
    //     ]);

    //     try {
    //         $materi = PertemuanMateri::findOrFail($id);
    //         $materi->update([
    //             'materi_id' => $request->judul,
    //             'pertemuan_id' => $request->pertemuan_id
    //         ]);

    //         return response()->json(['success' => true, 'message' => 'Materi berhasil diperbarui.']);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => 'Gagal memperbarui materi.'], 500);
    //     }
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pertemuan_id' => 'required|exists:pertemuan,id',
            'materi_id' => 'required|exists:materi,id',
        ]);

        $data = PertemuanMateri::findOrFail($id);
        $data->pertemuan_id = $request->pertemuan_id;
        $data->materi_id = $request->materi_id;
        $data->save();

        return redirect()->back()->with('success', 'Materi berhasil diperbarui.');
    }



    public function destroy($id)
    {
        try {
            $pertemuanMateri = PertemuanMateri::findOrFail($id);
            $pertemuanMateri->delete();

            return response()->json(['success' => true, 'message' => 'Materi berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus materi.'], 500);
        }
    }
}
