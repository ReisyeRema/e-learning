<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;
use App\Models\Kontak;
use Illuminate\Support\Facades\Mail;

class LandingPageController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        $profileSekolah = ProfilSekolah::first();

        $jumlahGuru = Guru::count();
        $jumlahSiswa = Siswa::count();
        $jumlahKelas = Kelas::count();
        $jumlahMapel = Pembelajaran::count();
        return view('pages.frontend.index', compact('kelas', 'profileSekolah', 'jumlahGuru', 'jumlahSiswa', 'jumlahKelas', 'jumlahMapel'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'nama'    => 'required',
            'email'   => 'required|email',
            'subjek' => 'required',
            'pesan' => 'required',
        ]);

        // Simpan ke database
        $kontak = Kontak::create([
            'nama'   => $request->nama,
            'email'  => $request->email,
            'subjek' => $request->subjek,
            'pesan'  => $request->pesan,
        ]);

        // Kirim Email
        Mail::send('emails.kontak', ['data' => $kontak], function ($message) use ($kontak) {
            $message->from(env('MAIL_FROM_ADDRESS'), 'SMAN2KERINCIKANAN');
            $message->to('reisyerh1906@gmail.com', 'Sekolah') // Tujuan
                ->subject($kontak->subjek);
        });

        return back()->with('success', 'Pesan berhasil dikirim!');
    }
}
