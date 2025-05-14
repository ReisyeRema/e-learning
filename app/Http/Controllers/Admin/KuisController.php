<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kuis;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Pertemuan;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\KuisRequest;
use App\Models\PertemuanKuis;

class KuisController extends Controller
{
    public function index()
    {
        $kuis = Kuis::with('materi')->where('user_id', Auth::id())->get();
        $materi = Materi::where('user_id', Auth::id())->get();
        return view('pages.admin.kuis.index', compact('kuis', 'materi'));
    }

    public function store(KuisRequest $request)
    {

        Kuis::create([
            'user_id' => Auth::id(),
            'materi_id' => $request->materi_id,
            'judul' => $request->judul,
            'kategori' => $request->kategori
        ]);

        return redirect()->route('kuis.index')->with('success', 'Kuis Berhasil Ditambah');
    }


    public function update(KuisRequest $request, $id)
    {
        $kuis = Kuis::find($id);

        // Cek apakah yang mengakses adalah pemilik materi
        if (Auth::id() !== $kuis->user_id) {
            return redirect()->back()->withErrors(['unauthorized' => 'Anda tidak memiliki izin untuk mengedit materi ini.']);
        }

        $kuis->update([
            'materi_id' => $request->materi_id,
            'judul' => $request->judul,
            'kategori' => $request->kategori
        ]);

        return redirect()->route('kuis.index')->with('success', 'Kuis Berhasil Diupdate');
    }

    public function destroy($id)
    {
        $kuis = Kuis::find($id);

        // Pastikan hanya pemilik materi yang bisa menghapus
        if (Auth::id() !== $kuis->user_id) {
            return redirect()->back()->withErrors(['unauthorized' => 'Anda tidak memiliki izin untuk menghapus materi ini.']);
        }

        $kuis->delete();
        return redirect()->route('kuis.index')->with('success', 'Kuis Berhasil Dihapus');
    }

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

        // menampilakn select tambah materi di pertemuan materi
        $pertemuanSemua = Pertemuan::all();
        $kuis = Kuis::where('user_id', Auth::id())->get();

        //Ambil hanya pertemuan yang ada di tabel pertemuan_materi
        $pertemuanIds = PertemuanKuis::where('pembelajaran_id', $pembelajaran->id)
            ->pluck('pertemuan_id')
            ->unique(); // Hindari duplikasi

        //Ambil detail pertemuan yang sesuai, lalu urutkan berdasarkan id
        $pertemuan = Pertemuan::whereIn('id', $pertemuanIds)
            ->orderBy('id', 'asc')
            ->get();

        return view('pages.admin.kuis.show', compact('pembelajaran', 'kelasData', 'kuis', 'pertemuanSemua', 'pertemuan'));
    }

    // memanggila kuis di setiap pertemuan
    public function getKuisByPertemuan($pertemuan_id)
    {
        $pembelajaran_id = request()->query('pembelajaran_id');

        $kuis = PertemuanKuis::where('pertemuan_id', $pertemuan_id)
            ->where('pembelajaran_id', $pembelajaran_id)
            ->with('kuis') 
            ->get();

        return response()->json($kuis);
    }
}
