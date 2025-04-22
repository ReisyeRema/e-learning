<?php

namespace App\Http\Controllers\Admin;

use App\Models\TahunAjaran;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\TahunAjaranRequest;

class TahunAjaranController extends Controller
{
    public function index()
    {

        // Log aktivitas
        $this->logActivity('Mengakses Data Tahun Ajaran', 'User membuka halaman data tahun ajaran');

        $tahun_ajaran = TahunAjaran::all();
        return view('pages.admin.tahunAjaran.index', compact('tahun_ajaran'));
    }


    public function store(TahunAjaranRequest $request)
    {

        TahunAjaran::create([
            'nama_tahun' => $request->nama_tahun,
        ]);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun Ajaran Berhasil Ditambah');
    }


    public function update(TahunAjaranRequest $request, $id)
    {
        $tahun_ajaran = TahunAjaran::find($id);

        $tahun_ajaran->update([
            'nama_tahun' => $request->nama_tahun,
        ]);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun Ajaran Berhasil Diupdate');
    }

    public function destroy($id)
    {
        $tahun_ajaran = TahunAjaran::find($id);

        $tahun_ajaran->delete();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun Ajaran Berhasil Dihapus');
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
