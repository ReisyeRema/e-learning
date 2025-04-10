<?php

namespace App\Http\Controllers\Admin;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TahunAjaranRequest;

class TahunAjaranController extends Controller
{
    public function index()
    {
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
}
