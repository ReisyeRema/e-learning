<?php

namespace App\Http\Controllers\Admin;

use App\Models\Absensi;
use App\Models\Enrollments;
use Illuminate\Http\Request;
use App\Models\DetailAbsensi;
use App\Exports\ExportAbsensi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DetailAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $absensiId = $request->get('absensi_id');
        $absensi = Absensi::with('pembelajaran')->findOrFail($absensiId);

        // Ambil hanya siswa yang enroll dan sudah di-approve
        $enrolledSiswa = Enrollments::with('siswa')
            ->where('pembelajaran_id', $absensi->pembelajaran_id)
            ->where('status', 'approved') // hanya yang sudah disetujui
            ->get();

        // Ambil data absensi siswa yang sudah terisi
        $detailAbsensi = DetailAbsensi::where('absensi_id', $absensiId)->get()
            ->keyBy('siswa_id');

        return view('pages.admin.detailAbsensi.index', compact('absensi', 'enrolledSiswa', 'detailAbsensi'));
    }

    public function storeOrUpdate(Request $request)
    {
        $absensiId = $request->input('absensi_id');
        $keteranganData = $request->input('keterangan');

        foreach ($keteranganData as $siswaId => $keterangan) {
            DetailAbsensi::updateOrCreate(
                [
                    'absensi_id' => $absensiId,
                    'siswa_id' => $siswaId,
                ],
                [
                    'keterangan' => match ($keterangan) {
                        'H' => 'Hadir',
                        'I' => 'Izin',
                        'S' => 'Sakit',
                        'A' => 'Alfa',
                        default => null,
                    }
                ]
            );
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Tersimpan']);
        }

        return redirect()->back();
    }


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

        $filename = "absensi_{$namaKelasSlug}_{$tahunAjaranSlug}_{$mapelSlug}.xlsx";

        return Excel::download(new ExportAbsensi($pembelajaran->id), $filename);
    }
}
