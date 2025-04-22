<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use App\Models\UserActivity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\KelasAdminRequest;

class KelasAdminController extends Controller
{
    public function index()
    {
        // Log aktivitas
        $this->logActivity('Mengakses Data Kelas', 'User membuka halaman data kelas');

        $kelas = Kelas::all();
        return view('pages.admin.kelas.index', compact('kelas'));
    }


    public function store(KelasAdminRequest $request)
    {

        Kelas::create([
            'nama_kelas' => $request->nama_kelas
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas Berhasil Ditambah');
    }


    public function update(KelasAdminRequest $request, $id)
    {
        $kelas = Kelas::find($id);

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas Berhasil Diupdate');
    }

    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas Berhasil Dihapus');
    }


    // Menambahkan log aktivitas
    protected function logActivity($activity, $details = '')
    {
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'details' => $details,
        ]);
    }
}
