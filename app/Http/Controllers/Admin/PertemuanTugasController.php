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
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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


    public function update(PertemuanTugasRequest $request, $id)
    {

        $data = PertemuanTugas::findOrFail($id);
        $data->pertemuan_id = $request->pertemuan_id;  
        $data->tugas_id = $request->tugas_id;          
        $data->deadline = $request->deadline;          
        $data->save();

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui.');
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
            ->get()
            ->sortBy(fn($enroll) => strtolower($enroll->siswa->name ?? '')) // urutkan berdasarkan nama siswa
            ->values(); // reset key


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


    // public function export_excel()
    // {
    //     $guruId = Auth::id();

    //     // Ambil satu pembelajaran yang diampu guru (asumsi satu kelas/tahun/mapel per export)
    //     $pembelajaran = \App\Models\Pembelajaran::with(['kelas', 'tahunAjaran'])
    //         ->where('guru_id', $guruId)
    //         ->first();

    //     if (!$pembelajaran) {
    //         abort(404, 'Data pembelajaran tidak ditemukan.');
    //     }

    //     // Ambil data untuk nama file
    //     $namaKelas = $pembelajaran->kelas->nama_kelas ?? 'Kelas';
    //     $tahunAjaran = $pembelajaran->tahunAjaran->nama_tahun ?? 'TahunAjaran';
    //     $mapel = $pembelajaran->nama_mapel ?? 'Mapel';

    //     // Slugify nama agar aman untuk nama file
    //     $namaKelasSlug = str_replace(' ', '_', $namaKelas);
    //     $tahunAjaranSlug = str_replace([' ', '/', '\\'], '-', $tahunAjaran); // ganti slash agar aman
    //     $mapelSlug = str_replace(' ', '_', $mapel);

    //     $filename = "nilai_tugas_{$namaKelasSlug}_{$tahunAjaranSlug}_{$mapelSlug}.xlsx";

    //     // Jalankan export
    //     return Excel::download(new \App\Exports\ExportNilaiTugasMultiSheet($guruId), $filename);
    // }

    public function export_excel(Request $request)
    {
        $guruId = Auth::id();
        $pembelajaranId = $request->query('pembelajaran_id');

        $pembelajaran = \App\Models\Pembelajaran::with(['kelas', 'tahunAjaran'])
            ->where('guru_id', $guruId)
            ->when($pembelajaranId, function ($query, $pembelajaranId) {
                $query->where('id', $pembelajaranId);
            })
            ->first();

        if (!$pembelajaran) {
            abort(404, 'Data pembelajaran tidak ditemukan.');
        }

        $namaKelas = $pembelajaran->kelas->nama_kelas ?? 'Kelas';
        $tahunAjaran = $pembelajaran->tahunAjaran->nama_tahun ?? 'TahunAjaran';
        $mapel = $pembelajaran->nama_mapel ?? 'Mapel';

        $namaKelasSlug = str_replace(' ', '_', $namaKelas);
        $tahunAjaranSlug = str_replace([' ', '/', '\\'], '-', $tahunAjaran);
        $mapelSlug = str_replace(' ', '_', $mapel);

        $filename = "nilai_tugas_{$namaKelasSlug}_{$tahunAjaranSlug}_{$mapelSlug}.xlsx";

        return Excel::download(
            new \App\Exports\ExportNilaiTugasMultiSheet($guruId, $pembelajaran->id),
            $filename
        );
    }
}
