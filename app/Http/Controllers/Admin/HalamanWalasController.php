<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        return view('pages.admin.waliKelas.export', compact('waliKelas', 'semester', 'pembelajaran'));
    }
}
