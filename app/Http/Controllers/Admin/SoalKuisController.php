<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kuis;
use App\Models\SoalKuis;
use Illuminate\Http\Request;
use App\Imports\SoalKuisImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\SoalKuisRequest;

class SoalKuisController extends Controller
{
    public function index(Request $request)
    {
        $kuisId = $request->get('kuis_id');
        $soalKuis = SoalKuis::where('kuis_id', $kuisId)->get();
        $kuis = Kuis::findOrFail($kuisId);
        return view('pages.admin.kuis.soal', compact('kuisId', 'soalKuis', 'kuis'));
    }

    public function create($kuis_id)
    {
        $kuis = Kuis::findOrFail($kuis_id);
        return view('pages.admin.kuis.create-soal', compact('kuis'));
    }

    public function store(SoalKuisRequest $request, $kuis_id)
    {
        $validatedData = $request->validated();

        // Simpan gambar jika ada
        if ($request->hasFile('gambar')) {
            $newImage = $request->file('gambar')->store('gambar_soal', 'public');
            $validatedData['gambar'] = $newImage;
        } else {
            $validatedData['gambar'] = null;
        }

        // Tidak perlu json_encode, cukup isi array langsung
        if ($request->type_soal !== 'Objective') {
            $validatedData['pilihan_jawaban'] = null;
        }

        $validatedData['kuis_id'] = $kuis_id;

        SoalKuis::create($validatedData);

        return redirect()->route('soal.index', ['kuis_id' => $kuis_id])
            ->with('success', 'Data berhasil ditambahkan.');
    }


    public function update(SoalKuisRequest $request, $id)
    {
        $soalKuis = SoalKuis::findOrFail($id);
        $validatedData = $request->validated();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($soalKuis->gambar) {
                Storage::disk('public')->delete($soalKuis->gambar);
            }

            // Simpan gambar baru
            $newImage = $request->file('gambar')->store('gambar_soal', 'public');
            $validatedData['gambar'] = $newImage;
        }

        // Tidak perlu json_encode
        if ($request->type_soal !== 'Objective') {
            $validatedData['pilihan_jawaban'] = null;
        }

        $soalKuis->update($validatedData);

        return redirect()->route('soal.index', ['kuis_id' => $soalKuis->kuis_id])
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $soalKuis = SoalKuis::findOrFail($id);
        $kuis_id = $soalKuis->kuis_id;

        // Hapus gambar jika ada
        if ($soalKuis->gambar) {
            Storage::disk('public')->delete($soalKuis->gambar);
        }

        $soalKuis->delete();

        return redirect()->route('soal.index', ['kuis_id' => $kuis_id])
            ->with('success', 'Soal berhasil dihapus.');
    }

    public function import(Request $request, $kuisId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SoalKuisImport($kuisId), $request->file('file'));

        return redirect()->back()->with('success', 'Soal berhasil diimpor.');
    }

    public function exportTemplate()
    {
        $path = public_path('assets/templateExcel/template_soal_import.xlsx');

        if (!file_exists($path)) {
            abort(404, 'Template file not found.');
        }

        return response()->download($path, 'template_soal_import.xlsx');
    }
}
