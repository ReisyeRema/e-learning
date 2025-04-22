<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kurikulum;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\KurikulumRequest;

class KurikulumAdminController extends Controller
{
    public function index()
    {
        // Log aktivitas
        $this->logActivity('Mengakses Data Kurikulum', 'User membuka halaman data kurikulum');

        $kurikulum = Kurikulum::all();
        return view('pages.admin.kurikulum.index', compact('kurikulum'));
    }

    public function store(KurikulumRequest $request)
    {
        $newImage = '';

        if ($request->file('icon')) {
            $extension = $request->file('icon')->getClientOriginalExtension();
            $newImage = $request->nama_kurikulum . '.' . now()->timestamp . '.' . $extension;

            // Simpan ke disk public/icon_kurikulum
            $request->file('icon')->storeAs('icon_kurikulum', $newImage, 'public');
        }

        Kurikulum::create([
            'nama_kurikulum' => $request->nama_kurikulum,
            'deskripsi' => $request->deskripsi,
            'icon' => $newImage
        ]);

        return redirect()->route('kurikulum.index')->with('success', 'Kurikulum Berhasil Ditambah');
    }

    public function update(KurikulumRequest $request, $id)
    {
        $kurikulum = Kurikulum::findOrFail($id); // Fail jika data tidak ditemukan

        $updateData = [
            'nama_kurikulum' => $request->nama_kurikulum,
            'deskripsi' => $request->deskripsi,
        ];

        // Update ikon jika ada file baru
        if ($request->hasFile('icon')) {
            $extension = $request->file('icon')->getClientOriginalExtension();
            $newImage = $request->nama_kurikulum . '.' . now()->timestamp . '.' . $extension;

            // Simpan ikon baru ke disk public/icon_kurikulum
            $request->file('icon')->storeAs('icon_kurikulum', $newImage, 'public');

            // Hapus ikon lama jika ada
            if ($kurikulum->icon) {
                Storage::disk('public')->delete('icon_kurikulum/' . $kurikulum->icon);
            }

            $updateData['icon'] = $newImage;
        }

        $kurikulum->update($updateData);

        return redirect()->route('kurikulum.index')->with('success', 'Kurikulum Berhasil Diupdate');
    }

    public function destroy($id)
    {
        $kurikulum = Kurikulum::findOrFail($id);

        // Hapus ikon dari disk jika ada
        if ($kurikulum->icon) {
            Storage::disk('public')->delete('icon_kurikulum/' . $kurikulum->icon);
        }

        $kurikulum->delete();
        return redirect()->route('kurikulum.index')->with('success', 'Kurikulum Berhasil Dihapus');
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
