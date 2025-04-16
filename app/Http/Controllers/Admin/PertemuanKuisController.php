<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kuis;
use App\Models\User;
use App\Models\Kelas;
use App\Models\SoalKuis;
use App\Models\HasilKuis;
use App\Models\Enrollments;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\PertemuanKuis;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PertemuanKuisRequest;

class PertemuanKuisController extends Controller
{
    public function store(PertemuanKuisRequest $request, $pembelajaran_id)
    {
        $data['deadline'] = \Carbon\Carbon::parse($request->deadline)->format('Y-m-d H:i:s');

        $validatedData = $request->validated();
        $validatedData['pembelajaran_id'] = $pembelajaran_id;

        // Generate unique token kalau kosong
        if (empty($validatedData['token'])) {
            do {
                $generatedToken = Str::upper(Str::random(6)); // contoh: YG3K8Z
            } while (PertemuanKuis::where('token', $generatedToken)->exists());

            $validatedData['token'] = $generatedToken;
        }

        // Ambil data pembelajaran berdasarkan ID
        $pembelajaran = Pembelajaran::with(['kelas', 'tahunAjaran'])->findOrFail($pembelajaran_id);

        // Buat slug untuk mapel dan kelas
        $mapelSlug = Str::slug($pembelajaran->nama_mapel);
        $kelasSlug = Str::slug($pembelajaran->kelas->nama_kelas);
        $tahunAjaranSlug = str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun);


        // Simpan data ke database
        PertemuanKuis::create($validatedData);

        // Redirect ke route yang sesuai dengan slug
        return redirect()->route('submit-kuis.show', [
            'mapel' => $mapelSlug,
            'kelas' => $kelasSlug,
            'tahunAjaran' => $tahunAjaranSlug
        ])->with('success', 'Data berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        try {
            $pertemuanKuis = PertemuanKuis::findOrFail($id);
            $pertemuanKuis->delete();

            return response()->json(['success' => true, 'message' => 'Materi berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus materi.'], 500);
        }
    }


    public function listKuis($mapel, $kelas, $tahunAjaran, Request $request)
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

        $pertemuanKuis = $pembelajaran->pertemuanKuis()->with('kuis')->get();
        $kuisList = $pertemuanKuis->pluck('kuis')->unique('id');

        $enrollments = Enrollments::with('siswa')
            ->where('pembelajaran_id', $pembelajaran->id)
            ->get();

        $kuisIdAktif = $request->query('kuis_id');

        // Cuma ambil submit untuk tugas yang dipilih
        $hasilKuis = HasilKuis::with('siswa')
            ->where('kuis_id', $kuisIdAktif)
            ->get()
            ->groupBy(function ($item) {
                return $item->kuis_id . '-' . $item->siswa_id;
            });

        return view('pages.admin.kuis.list-kuis', compact(
            'kuisList',
            'pembelajaran',
            'kelasData',
            'enrollments',
            'hasilKuis',
            'kuisIdAktif'
        ));
    }


    public function show($mapel, $kelas, $tahunAjaran, $kuisId, $siswaId)
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

        $kuis = Kuis::findOrFail($kuisId);
        $siswa = User::findOrFail($siswaId);
        $hasil = HasilKuis::where('kuis_id', $kuisId)->where('siswa_id', $siswaId)->firstOrFail();

        // Decode jawaban_user JSON
        $jawabanUser = json_decode($hasil->jawaban_user, true) ?? [];

        // Ambil semua soal berdasarkan ID yang dijawab
        $soalList = SoalKuis::whereIn('id', array_keys($jawabanUser))->get()->keyBy('id');

        return view('pages.admin.kuis.hasil-kuis', compact(
            'pembelajaran',
            'kelasData',
            'kuis',
            'siswa',
            'hasil',
            'jawabanUser',
            'soalList'
        ));
    }

    public function updateEssay(Request $request, $kuisId, $siswaId)
    {
        $hasil = HasilKuis::where('kuis_id', $kuisId)
            ->where('siswa_id', $siswaId)
            ->firstOrFail();

        $jawabanUser = json_decode($hasil->jawaban_user, true);

        $soalIds = array_keys($jawabanUser);
        $soalList = SoalKuis::whereIn('id', $soalIds)->get()->keyBy('id');

        $jumlahBenar = 0;
        $jumlahSoal = count($soalList);

        foreach ($jawabanUser as $soalId => &$jawaban) {
            $soal = $soalList[$soalId] ?? null;
            if (!$soal) continue;

            // Set default false
            $isBenar = false;

            if ($soal->type_soal === 'Essay') {
                // Update dari input guru
                $isBenar = isset($request->jawaban_benar[$soalId]) && $request->jawaban_benar[$soalId] == 1;
                $jawaban['is_benar'] = $isBenar;
            } elseif ($soal->type_soal === 'Objective' || $soal->type_soal === 'TrueFalse') {
                $isBenar = ($jawaban['jawaban'] ?? '') == $soal->jawaban_benar;
                $jawaban['is_benar'] = $isBenar; // Optional, sync ulang
            }

            if ($isBenar) {
                $jumlahBenar++;
            }
        }

        $skorTotal = $jumlahSoal > 0 ? round(($jumlahBenar / $jumlahSoal) * 100) : 0;

        $hasil->update([
            'jawaban_user' => json_encode($jawabanUser),
            'skor_total' => $skorTotal
        ]);

        return back()->with('success', 'Penilaian berhasil diperbarui.');
    }
}
