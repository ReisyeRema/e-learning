<?php

namespace App\Http\Controllers\Siswa;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PertemuanKuis;
use App\Models\SiswaKuisSession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SiswaKuisSessionController extends Controller
{
    public function cekToken(Request $request)
    {
        $request->validate([
            'pertemuan_kuis_id' => 'required|exists:pertemuan_kuis,id',
            'token' => 'required|string',
        ]);

        $pertemuan = PertemuanKuis::with(['pembelajaran.kelas', 'pembelajaran.tahunAjaran'])
            ->findOrFail($request->pertemuan_kuis_id);

        // Cek apakah token cocok
        if ($request->token !== $pertemuan->token) {
            return back()->with('token_error', 'Token yang dimasukkan salah.');
        }

        // Cek apakah siswa sudah pernah mulai sesi
        $existingSession = SiswaKuisSession::where('pertemuan_kuis_id', $pertemuan->id)
            ->where('siswa_id', Auth::id())
            ->first();

        if (!$existingSession) {
            // Simpan sesi baru
            SiswaKuisSession::create([
                'pertemuan_kuis_id' => $pertemuan->id,
                'siswa_id' => Auth::id(),
                'jam_mulai' => now()->format('H:i:s'),
                'token' => $request->token,
            ]);
        }

        return redirect()->route('kuis-siswa.action', [
            'mapel' => Str::slug($pertemuan->pembelajaran->nama_mapel),
            'kelas' => Str::slug($pertemuan->pembelajaran->kelas->nama_kelas),
            'tahunAjaran' => str_replace('/', '-', $pertemuan->pembelajaran->tahunAjaran->nama_tahun),
            'judulKuis' => Str::slug($pertemuan->kuis->judul),
        ]);
    }
}
