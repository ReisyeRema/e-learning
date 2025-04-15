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
    // public function cekToken(Request $request)
    // {
    //     $request->validate([
    //         'pertemuan_kuis_id' => 'required|exists:pertemuan_kuis,id',
    //         'token' => 'required|string',
    //     ]);

    //     $pertemuan = PertemuanKuis::with(['pembelajaran.kelas', 'pembelajaran.tahunAjaran'])
    //         ->findOrFail($request->pertemuan_kuis_id);

    //     // Cek apakah token cocok
    //     if ($request->token !== $pertemuan->token) {
    //         return back()->with('token_error', 'Token yang dimasukkan salah.');
    //     }

    //     // Cek apakah siswa sudah pernah mulai sesi
    //     $existingSession = SiswaKuisSession::where('pertemuan_kuis_id', $pertemuan->id)
    //         ->where('siswa_id', Auth::id())
    //         ->first();

    //     if (!$existingSession) {
    //         // Simpan sesi baru
    //         SiswaKuisSession::create([
    //             'pertemuan_kuis_id' => $pertemuan->id,
    //             'siswa_id' => Auth::id(),
    //             'jam_mulai' => now()->format('H:i:s'),
    //             'token' => $request->token,
    //         ]);
    //     }

    //     return redirect()->route('kuis-siswa.action', [
    //         'mapel' => Str::slug($pertemuan->pembelajaran->nama_mapel),
    //         'kelas' => Str::slug($pertemuan->pembelajaran->kelas->nama_kelas),
    //         'tahunAjaran' => str_replace('/', '-', $pertemuan->pembelajaran->tahunAjaran->nama_tahun),
    //         'judulKuis' => Str::slug($pertemuan->kuis->judul),
    //     ]);
    // }


    public function cekToken(Request $request)
    {
        $request->validate([
            'pertemuan_kuis_id' => 'required|exists:pertemuan_kuis,id',
            'token' => 'required|string',
        ]);

        $pertemuan = PertemuanKuis::with(['pembelajaran.kelas', 'pembelajaran.tahunAjaran', 'kuis'])
            ->findOrFail($request->pertemuan_kuis_id);

        $kuis = $pertemuan->kuis;
        $kategori = $kuis->kategori;
        $siswaId = Auth::id();

        // Tentukan batas percobaan berdasarkan kategori
        $maxAttempt = ($kategori === 'Kuis') ? 3 : 1;

        // Ambil sesi kuis
        $session = SiswaKuisSession::firstOrNew([
            'pertemuan_kuis_id' => $pertemuan->id,
            'siswa_id' => $siswaId,
        ]);

        // Jika sudah mencapai batas dan token sebelumnya sudah benar
        if ($session->token && $session->token_attempts >= $maxAttempt) {
            return back()->with('token_error', 'Token tidak bisa digunakan lagi. Batas percobaan sudah habis.');
        }

        // Jika token salah, jangan tambahkan attempt
        if ($request->token !== $pertemuan->token) {
            return back()->with('token_error', 'Token yang dimasukkan salah.');
        }

        // Jika token benar dan belum pernah disimpan
        if (!$session->exists || !$session->token) {
            $session->token = $request->token;
            $session->jam_mulai = now()->format('H:i:s');
            $session->token_attempts = 1; // mulai dari 1 karena ini pertama kali token benar
        } else {
            // Token sudah pernah benar sebelumnya, tambahkan attempt
            $session->token_attempts += 1;
        }

        $session->save();

        return redirect()->route('kuis-siswa.action', [
            'mapel' => Str::slug($pertemuan->pembelajaran->nama_mapel),
            'kelas' => Str::slug($pertemuan->pembelajaran->kelas->nama_kelas),
            'tahunAjaran' => str_replace('/', '-', $pertemuan->pembelajaran->tahunAjaran->nama_tahun),
            'judulKuis' => Str::slug($kuis->judul),
        ]);
    }
}
