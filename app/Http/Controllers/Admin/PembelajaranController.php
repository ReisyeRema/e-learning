<?php

namespace App\Http\Controllers\Admin;

use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Kurikulum;
use App\Models\TahunAjaran;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\PembelajaranAdminRequest;

class PembelajaranController extends Controller
{
    public function index()
    {
        $pembelajaran = Pembelajaran::all();
        return view('pages.admin.pembelajaran.index', compact('pembelajaran'));
    }

    public function create()
    {
        $tahunAjaran = TahunAjaran::all();
        $kelas = Kelas::all();
        $guru = User::getGuru();
        $kurikulum = Kurikulum::all();
        return view('pages.admin.pembelajaran.create', compact('tahunAjaran', 'kelas', 'guru', 'kurikulum'));
    }

    public function store(PembelajaranAdminRequest $request)
    {
        $data = $request->validated();

        if ($request->file('cover')) {
            $extension = $request->file('cover')->getClientOriginalExtension();
            $newImageName = 'cover_pembelajaran_' . now()->timestamp . '.' . $extension;

            $request->file('cover')->storeAs('covers', $newImageName, 'public');

            $data['cover'] = $newImageName;
        }

        Pembelajaran::create($data);

        return redirect()->route('pembelajaran.index')->with('success', 'Data pembelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pembelajaran = Pembelajaran::find($id);
        $tahunAjaran = TahunAjaran::all();
        $kelas = Kelas::all();
        $guru = User::getGuru();
        $kurikulum = Kurikulum::all();
        return view('pages.admin.pembelajaran.edit', compact('pembelajaran', 'tahunAjaran', 'kelas', 'guru', 'kurikulum'));
    }

    public function update(PembelajaranAdminRequest $request, $id)
    {
        $pembelajaran = Pembelajaran::findOrFail($id);
        $data = $request->validated();

        // Update cover jika ada file baru
        if ($request->hasFile('cover')) {
            $extension = $request->file('cover')->getClientOriginalExtension();
            $newImageName = 'cover_pembelajaran_' . now()->timestamp . '.' . $extension;

            // Simpan cover baru ke disk public/covers
            $request->file('cover')->storeAs('covers', $newImageName, 'public');

            // Hapus cover lama jika ada
            if ($pembelajaran->cover) {
                Storage::disk('public')->delete('covers/' . $pembelajaran->cover);
            }

            $data['cover'] = $newImageName;
        }

        // Update data pembelajaran
        $pembelajaran->update($data);

        return redirect()->route('pembelajaran.index')->with('success', 'Data pembelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembelajaran = Pembelajaran::find($id);

        if ($pembelajaran->cover) {
            Storage::disk('public')->delete('covers/' . $pembelajaran->cover);
        }

        $pembelajaran->delete();

        return redirect()->route('pembelajaran.index')->with('success', 'Data Pembelajaran berhasil dihapus.');
    }
}
