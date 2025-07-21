<?php

namespace App\Http\Controllers\Admin;

use App\Exports\WaliKelas\ExportAbsensiKelasMultisheet;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WaliKelas\ExportNilaiKuisKelasMultisheet;
use App\Exports\WaliKelas\ExportNilaiTugasKelasMultisheet;

class HalamanWalasController extends Controller
{
    public function daftarSiswa($kelas, $tahunAjaran)
    {
        // Slug to actual format
        $kelasNama = Str::upper(str_replace('-', ' ', $kelas));
        $tahunAjaranFormatted = str_replace('-', '/', $tahunAjaran);

        $kelasData = Kelas::whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas])->firstOrFail();

        $waliKelas = WaliKelas::where('kelas_id', $kelasData->id)
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaranFormatted) {
                $query->where('nama_tahun', $tahunAjaranFormatted);
            })
            ->firstOrFail();

        // Ambil daftar siswa berdasarkan kelas_id
        $siswaList = Siswa::where('kelas_id', $kelasData->id)->get();

        return view('pages.admin.waliKelas.list-siswa-kelas', compact('waliKelas', 'siswaList'));
    }

    public function daftarMapel($kelas, $tahunAjaran)
    {
        // Slug to actual format
        $kelasNama = Str::upper(str_replace('-', ' ', $kelas));
        $tahunAjaranFormatted = str_replace('-', '/', $tahunAjaran);

        $kelasData = Kelas::whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas])->firstOrFail();

        $waliKelas = WaliKelas::where('kelas_id', $kelasData->id)
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaranFormatted) {
                $query->where('nama_tahun', $tahunAjaranFormatted);
            })
            ->firstOrFail();

        // Ambil daftar mapel dari pembelajaran
        $mapelList = Pembelajaran::with('guru')
            ->where('kelas_id', $waliKelas->kelas_id)
            ->where('tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->get();

        return view('pages.admin.waliKelas.list-mapel-kelas', compact('waliKelas', 'mapelList'));
    }


    public function export($kelas, $tahunAjaran, Request $request)
    {
        $semester = $request->input('semester');

        // Slug to actual format
        $kelasNama = Str::upper(str_replace('-', ' ', $kelas));
        $tahunAjaranFormatted = str_replace('-', '/', $tahunAjaran);

        $kelasData = Kelas::whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas])->firstOrFail();

        $waliKelas = WaliKelas::where('kelas_id', $kelasData->id)
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaranFormatted) {
                $query->where('nama_tahun', $tahunAjaranFormatted);
            })
            ->firstOrFail();

        // filter semester
        $pembelajaranQuery = Pembelajaran::where('kelas_id', $kelasData->id)
            ->where('tahun_ajaran_id', $waliKelas->tahunAjaran->id);

        if ($semester) {
            $pembelajaranQuery->where('semester', $semester);
        }

        $pembelajaran = $pembelajaranQuery->get();

        $slugKelas = Str::slug($waliKelas->kelas->nama_kelas);
        $slugTahunAjaran = str_replace('/', '-', $waliKelas->tahunAjaran->nama_tahun);


        return view('pages.admin.waliKelas.export', compact('waliKelas', 'semester', 'pembelajaran', 'slugKelas', 'slugTahunAjaran'));
    }

    public function export_tugas(Request $request, $kelas, $tahunAjaran)
    {
        $semester = $request->get('semester');

        $kelasNama = Str::slug($kelas, ' ');
        $tahunNama = str_replace('-', '/', $tahunAjaran);

        // Validasi bahwa guru benar-benar wali kelas ini
        $wali = WaliKelas::where('guru_id', Auth::id())
            ->whereHas('kelas', function ($q) use ($kelasNama) {
                $q->whereRaw('LOWER(REPLACE(nama_kelas, " ", "-")) = ?', [Str::slug($kelasNama)]);
            })
            ->whereHas('tahunAjaran', function ($q) use ($tahunNama) {
                $q->where('nama_tahun', $tahunNama);
            })
            ->firstOrFail();

        // Cek apakah ada data pembelajaran
        $cekPembelajaran = Pembelajaran::where('kelas_id', $wali->kelas_id)
            ->where('tahun_ajaran_id', $wali->tahun_ajaran_id)
            ->where('semester', $semester)
            ->exists();

        if (!$cekPembelajaran) {
            return back()->with('error', 'Tidak tugas untuk semester ini.');
        }

        // Ekspor sesuai kelas & tahun ajaran wali yang sah
        return Excel::download(
            new ExportNilaiTugasKelasMultisheet($semester, $wali->kelas_id, $wali->tahun_ajaran_id),
            'rekap_tugas_' . $kelas . '_' . $semester . '_' . $tahunAjaran . '.xlsx'
        );
    }

    public function export_kuis(Request $request, $kelas, $tahunAjaran)
    {
        $semester = $request->get('semester');

        $kelasNama = Str::slug($kelas, ' ');
        $tahunNama = str_replace('-', '/', $tahunAjaran);

        // Validasi bahwa guru benar-benar wali kelas ini
        $wali = WaliKelas::where('guru_id', Auth::id())
            ->whereHas('kelas', function ($q) use ($kelasNama) {
                $q->whereRaw('LOWER(REPLACE(nama_kelas, " ", "-")) = ?', [Str::slug($kelasNama)]);
            })
            ->whereHas('tahunAjaran', function ($q) use ($tahunNama) {
                $q->where('nama_tahun', $tahunNama);
            })
            ->firstOrFail();

        // Cek apakah ada data pembelajaran
        $cekPembelajaran = Pembelajaran::where('kelas_id', $wali->kelas_id)
            ->where('tahun_ajaran_id', $wali->tahun_ajaran_id)
            ->where('semester', $semester)
            ->exists();

        if (!$cekPembelajaran) {
            return back()->with('error', 'Tidak kuis atau ujian untuk semester ini.');
        }

        // Ekspor sesuai kelas & tahun ajaran wali yang sah
        return Excel::download(
            new ExportNilaiKuisKelasMultisheet($semester, $wali->kelas_id, $wali->tahun_ajaran_id),
            'rekap_kuis_dan_ujian' . $kelas . '_' . $semester . '_' . $tahunAjaran . '.xlsx'
        );
    }

    public function export_absensi(Request $request, $kelas, $tahunAjaran)
    {
        $semester = $request->get('semester');

        $kelasNama = Str::slug($kelas, ' ');
        $tahunNama = str_replace('-', '/', $tahunAjaran);

        // Validasi bahwa guru benar-benar wali kelas ini
        $wali = WaliKelas::where('guru_id', Auth::id())
            ->whereHas('kelas', function ($q) use ($kelasNama) {
                $q->whereRaw('LOWER(REPLACE(nama_kelas, " ", "-")) = ?', [Str::slug($kelasNama)]);
            })
            ->whereHas('tahunAjaran', function ($q) use ($tahunNama) {
                $q->where('nama_tahun', $tahunNama);
            })
            ->firstOrFail();

        // Cek apakah ada data pembelajaran
        $cekPembelajaran = Pembelajaran::where('kelas_id', $wali->kelas_id)
            ->where('tahun_ajaran_id', $wali->tahun_ajaran_id)
            ->where('semester', $semester)
            ->exists();

        if (!$cekPembelajaran) {
            return back()->with('error', 'Tidak ada absensi untuk semester ini.');
        }

        // Ekspor sesuai kelas & tahun ajaran wali yang sah
        return Excel::download(
            new ExportAbsensiKelasMultisheet($semester, $wali->kelas_id, $wali->tahun_ajaran_id),
            'rekap_absensi' . $kelas . '_' . $semester . '_' . $tahunAjaran . '.xlsx'
        );
    }
}
