<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelas;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KelasAdminRequest;

class KelasAdminController extends Controller
{
    public function index()
    {
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
}
