<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Exports\ExportRekapAkhir;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class RekapAkhirController extends Controller
{
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

        
        return view('pages.admin.rekapAkhir.show', compact('pembelajaran', 'kelasData'));
    }


    public function export_excel(Request $request)
    {
        $guruId = Auth::id();
        $pembelajaranId = $request->query('pembelajaran_id');

        $pembelajaran = Pembelajaran::with(['kelas', 'tahunAjaran'])
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
        $semester = $pembelajaran->semester ?? 'Semester';

        $namaKelasSlug = str_replace(' ', '_', $namaKelas);
        $tahunAjaranSlug = str_replace([' ', '/', '\\'], '-', $tahunAjaran);
        $mapelSlug = str_replace(' ', '_', $mapel);
        $semesterSlug = str_replace(' ', '_', $semester);

        $filename = "rekapitulasi_akhir_{$namaKelasSlug}_{$tahunAjaranSlug}_{$mapelSlug}_{$semesterSlug}.xlsx";

        return Excel::download(
            new ExportRekapAkhir(
                $pembelajaran->id,
                $namaKelas,
                $tahunAjaran, $pembelajaran->guru,
                $semester,
            ),
            $filename
        );    }
}
