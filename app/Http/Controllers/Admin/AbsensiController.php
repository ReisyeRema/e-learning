<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Pertemuan;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AbsensiRequest;

class AbsensiController extends Controller
{
    public function show($mapel, $kelas, $tahunAjaran)
    {
        // Ubah slug kembali ke format nama asli
        $mapelNama = Str::title(str_replace('-', ' ', $mapel));
        $kelasNama = Str::upper(str_replace('-', ' ', $kelas));

        // Ubah "2023-2024" kembali menjadi "2023/2024" agar cocok dengan database
        $tahunAjaranFormatted = str_replace('-', '/', $tahunAjaran);

        // Cari kelas berdasarkan nama
        $kelasData = Kelas::whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas])->firstOrFail();

        // Cari pembelajaran yang sesuai
        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->where('kelas_id', $kelasData->id)
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaranFormatted) {
                $query->where('nama_tahun', $tahunAjaranFormatted);
            })
            ->firstOrFail();

        // $pertemuanSemua = Pertemuan::all();

        // Ambil semua absensi dengan pembelajaran yang sama
        $absensi = Absensi::with('pertemuan')
            ->where('pembelajaran_id', $pembelajaran->id)
            ->orderBy('tanggal', 'asc')
            ->get();
        return view('pages.admin.absensi.show', compact('pembelajaran', 'kelasData', 'absensi'));
    }


    public function store(AbsensiRequest $request, $pembelajaran_id)
    {
        $data = $request->validated();

        $data['pembelajaran_id'] = $pembelajaran_id;
        $data['is_multisession'] = $request->boolean('is_multisession');

        // Set default null untuk field opsional
        $data['ulangi_pada']   = $data['ulangi_pada'] ?? null;
        $data['ulangi_sampai'] = $data['ulangi_sampai'] ?? null;

        // Simpan absensi pertama dan simpan id pertemuan yang digunakan
        $lastPertemuanId = null;
        $lastPertemuanId = $this->buatAbsensiDenganPertemuan($data, $data['tanggal'], $lastPertemuanId);

        // Jika multisesi, proses pengulangan otomatis berdasarkan "Ulangi Sampai Tanggal"
        if ($data['is_multisession'] && is_array($data['ulangi_pada']) && $data['ulangi_sampai']) {
            $tanggalMulai   = \Carbon\Carbon::parse($data['tanggal']);
            $tanggalSampai  = \Carbon\Carbon::parse($data['ulangi_sampai']);

            $hariMap = [
                'Senin' => 1,
                'Selasa' => 2,
                'Rabu' => 3,
                'Kamis' => 4,
                'Jumat' => 5,
            ];

            $current = $tanggalMulai->copy()->addWeek();

            while ($current->lte($tanggalSampai)) {
                foreach ($data['ulangi_pada'] as $hari) {
                    if (!isset($hariMap[$hari])) continue;

                    $target = $current->copy()->startOfWeek(Carbon::MONDAY)->addDays($hariMap[$hari] - 1);

                    if ($target->gte($tanggalMulai) && $target->lte($tanggalSampai)) {
                        $lastPertemuanId = $this->buatAbsensiDenganPertemuan($data, $target->format('Y-m-d'), $lastPertemuanId);
                    }
                }

                $current->addWeek();
            }
        }

        $pembelajaran = Pembelajaran::with(['kelas', 'tahunAjaran'])->findOrFail($pembelajaran_id);

        return redirect()
            ->route('absensi.show', [
                'mapel'       => Str::slug($pembelajaran->nama_mapel),
                'kelas'       => Str::slug($pembelajaran->kelas->nama_kelas),
                'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
            ])
            ->with('success', 'Absensi berhasil disimpan.');
    }


    // Fungsi bantu membuat absensi + pertemuan baru
    protected function buatAbsensiDenganPertemuan(array $data, $tanggal, $lastPertemuanId = null)
    {
        // Jika belum ada $lastPertemuanId, cari pertemuan terakhir dari absensi sebelumnya
        if (!$lastPertemuanId) {
            $lastAbsensi = Absensi::where('pembelajaran_id', $data['pembelajaran_id'])
                ->orderByDesc('pertemuan_id')
                ->first();

            if ($lastAbsensi) {
                $lastPertemuanId = $lastAbsensi->pertemuan_id;
            }
        }

        // Ambil pertemuan selanjutnya dari yang terakhir (atau pertama jika null)
        $pertemuan = Pertemuan::when($lastPertemuanId, function ($query) use ($lastPertemuanId) {
            return $query->where('id', '>', $lastPertemuanId);
        })
            ->orderBy('id')
            ->first();

        // Jika tidak ada pertemuan yang ditemukan, return yang terakhir
        if (!$pertemuan) {
            return $lastPertemuanId;
        }

        Absensi::create([
            'pertemuan_id'    => $pertemuan->id,
            'pembelajaran_id' => $data['pembelajaran_id'],
            'tanggal'         => $tanggal,
            'jam_mulai'       => $data['jam_mulai'],
            'jam_selesai'     => $data['jam_selesai'],
            'is_multisession' => $data['is_multisession'],
            'ulangi_pada'     => $data['ulangi_pada'],
            'ulangi_sampai'   => $data['ulangi_sampai'],
        ]);

        return $pertemuan->id;
    }


    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        $input = $request->only(['tanggal', 'jam_mulai', 'jam_selesai']);

        // Gunakan nilai lama jika field kosong
        $input['tanggal'] = $input['tanggal'] ?: $absensi->tanggal;
        $input['jam_mulai'] = $input['jam_mulai'] ?: $absensi->jam_mulai;
        $input['jam_selesai'] = $input['jam_selesai'] ?: $absensi->jam_selesai;

        $absensi->update($input);

        return redirect()->back()->with('success', 'Absensi berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);

        $pembelajaran = $absensi->pembelajaran;

        $absensi->delete();

        return redirect()->route('absensi.show', [
            'mapel' => Str::slug($pembelajaran->nama_mapel),
            'kelas' => Str::slug($pembelajaran->kelas->nama_kelas),
            'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
        ])->with('success', 'Absensi berhasil dihapus.');
    }
}
