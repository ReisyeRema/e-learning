<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kuis;
use App\Models\User;
use App\Models\Kelas;
use App\Models\SoalKuis;
use App\Models\HasilKuis;
use App\Models\Enrollments;
use Illuminate\Support\Str;
use App\Exports\ExportNilai;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\PertemuanKuis;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportNilaiKuisMultiSheet;
use App\Notifications\PertemuanKuisNotification;
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
                $generatedToken = Str::upper(Str::random(6)); 
            } while (PertemuanKuis::where('token', $generatedToken)->exists());

            $validatedData['token'] = $generatedToken;
        }

        // Simpan data tugas
        $pertemuanKuis = PertemuanKuis::create($validatedData);

        // Ambil data pembelajaran berdasarkan ID
        $pembelajaran = Pembelajaran::with(['enrollments.siswa', 'kelas', 'tahunAjaran'])->findOrFail($pembelajaran_id);

        // Kirim notifikasi ke semua siswa yang enroll
        foreach ($pembelajaran->enrollments as $enrollment) {
            if ($enrollment->status === 'approved') {
                $siswa = $enrollment->siswa;
                if ($siswa && $siswa->email) {
                    $siswa->notify(new PertemuanKuisNotification($pertemuanKuis));
                }
            }
        }
        // Buat slug untuk mapel dan kelas
        $mapelSlug = Str::slug($pembelajaran->nama_mapel);
        $kelasSlug = Str::slug($pembelajaran->kelas->nama_kelas);
        $tahunAjaranSlug = str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun);
        $semesterSlug = Str::slug($pembelajaran->semester);


        // Redirect ke route yang sesuai dengan slug
        return redirect()->route('submit-kuis.show', [
            'mapel' => $mapelSlug,
            'kelas' => $kelasSlug,
            'tahunAjaran' => $tahunAjaranSlug,
            'semester' => $semesterSlug
        ])->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(PertemuanKuisRequest $request, $id)
    {

        $data = PertemuanKuis::findOrFail($id);
        $data->pertemuan_id = $request->pertemuan_id;
        $data->kuis_id = $request->kuis_id;
        $data->deadline = $request->deadline;
        $data->save();

        return redirect()->back()->with('success', 'Kuis berhasil diperbarui.');
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


    public function listKuis($mapel, $kelas, $tahunAjaran, $semester, Request $request)
    {
        $mapelNama = Str::title(str_replace('-', ' ', $mapel));
        $kelasNama = Str::upper(str_replace('-', ' ', $kelas));
        $tahunAjaranFormatted = str_replace('-', '/', $tahunAjaran);
        $semesterNama = Str::upper(str_replace('-', ' ', $semester));

        $kelasData = Kelas::whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas])->firstOrFail();

        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->where('kelas_id', $kelasData->id)
            ->where('semester', $semesterNama)
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaranFormatted) {
                $query->where('nama_tahun', $tahunAjaranFormatted);
            })
            ->firstOrFail();

        $pertemuanKuis = $pembelajaran->pertemuanKuis()->with('kuis')->get();
        $kuisList = $pertemuanKuis->pluck('kuis')->unique('id');

        $enrollments = Enrollments::with('siswa')
            ->where('pembelajaran_id', $pembelajaran->id)
            ->where('status', 'approved')
            ->get()
            ->sortBy(fn($enroll) => strtolower($enroll->siswa->name ?? '')) 
            ->values();


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


    public function show($mapel, $kelas, $tahunAjaran, $semester, $kuisId, $siswaId)
    {
        $mapelNama = Str::title(str_replace('-', ' ', $mapel));
        $kelasNama = Str::upper(str_replace('-', ' ', $kelas));
        $tahunAjaranFormatted = str_replace('-', '/', $tahunAjaran);
        $semesterNama = Str::upper(str_replace('-', ' ', $semester));

        $kelasData = Kelas::whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas])->firstOrFail();

        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->where('kelas_id', $kelasData->id)
            ->where('semester', $semesterNama)
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
                $jawaban['is_benar'] = $isBenar; 
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

        $kuisList = PertemuanKuis::whereHas('pembelajaran', function ($query) use ($guruId, $pembelajaranId) {
            $query->where('guru_id', $guruId)
                  ->where('id', $pembelajaranId);
        })
        ->exists();

        if (!$kuisList) {
            return back()->with('error', 'Belum ada data kuis yang tersedia untuk diexport!');
        }

        $namaKelas = $pembelajaran->kelas->nama_kelas ?? 'Kelas';
        $tahunAjaran = $pembelajaran->tahunAjaran->nama_tahun ?? 'TahunAjaran';
        $mapel = $pembelajaran->nama_mapel ?? 'Mapel';
        $semester = $pembelajaran->semester ?? 'Semester';

        $namaKelasSlug = str_replace(' ', '_', $namaKelas);
        $tahunAjaranSlug = str_replace([' ', '/', '\\'], '-', $tahunAjaran);
        $mapelSlug = str_replace(' ', '_', $mapel);
        $semesterSlug = str_replace(' ', '_', $semester);

        $filename = "nilai_kuis_{$namaKelasSlug}_{$tahunAjaranSlug}_{$mapelSlug}_{$semesterSlug}.xlsx";

        return Excel::download(
            new \App\Exports\ExportNilaiKuisMultiSheet($guruId, $pembelajaran->id),
            $filename
        );
    }
}
