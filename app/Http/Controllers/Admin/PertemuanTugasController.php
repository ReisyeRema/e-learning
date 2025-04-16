<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use App\Models\Enrollments;
use App\Models\SubmitTugas;
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


    public function listTugas($mapel, $kelas, $tahunAjaran, Request $request)
    {
        $mapelNama = Str::title(str_replace('-', ' ', $mapel));
        $kelasNama = Str::upper(str_replace('-', ' ', $kelas));
        $tahunAjaranFormatted = str_replace('-', '/', $tahunAjaran);

        $kelasData = Kelas::whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas])->firstOrFail();

        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->where('kelas_id', $kelasData->id)
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaranFormatted) {
                $query->where('nama_tahun', $tahunAjaranFormatted);
            })
            ->firstOrFail();

        $pertemuanTugas = $pembelajaran->pertemuanTugas()->with('tugas')->get();
        $tugasList = $pertemuanTugas->pluck('tugas')->unique('id');

        $enrollments = Enrollments::with('siswa')
            ->where('pembelajaran_id', $pembelajaran->id)
            ->get();

        $tugasIdAktif = $request->query('tugas_id');

        // Cuma ambil submit untuk tugas yang dipilih
        $submitTugas = SubmitTugas::with('siswa')
            ->where('tugas_id', $tugasIdAktif)
            ->get()
            ->groupBy(function ($item) {
                return $item->tugas_id . '-' . $item->siswa_id;
            });

        return view('pages.admin.tugas.list-tugas', compact(
            'tugasList',
            'pembelajaran',
            'kelasData',
            'enrollments',
            'submitTugas',
            'tugasIdAktif'
        ));
    }


    public function updateSkor(Request $request, $id)
    {
        $request->validate([
            'skor' => 'required|numeric|min:0|max:100',
        ]);

        $submit = SubmitTugas::findOrFail($id);
        $submit->skor = $request->skor;
        $submit->save();

        return redirect()->back()->with('success', 'Skor berhasil diperbarui.');
    }
}
