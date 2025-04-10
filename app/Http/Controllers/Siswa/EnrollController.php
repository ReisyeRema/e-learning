<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Enrollments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EnrollController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        // Pastikan user adalah siswa
        if (!$user || !$user->roles->contains('name', 'Siswa')) {
            return response()->json(['message' => 'Hanya siswa yang bisa mendaftar.'], 403);
        }

        $pembelajaranId = $request->input('pembelajaran_id');

        // Cek apakah siswa sudah terdaftar
        $existingEnrollment = Enrollments::where('siswa_id', $user->id)
            ->where('pembelajaran_id', $pembelajaranId)
            ->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'Anda sudah terdaftar di mata pelajaran ini.'], 400);
        }

        // Simpan pendaftaran siswa
        Enrollments::create([
            'siswa_id' => $user->id,
            'pembelajaran_id' => $pembelajaranId,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Berhasil mengajukan pendaftaran, silahkan tunggu persetujuan!'], 200);
    }


    public function approve($id, Request $request)
    {
        $pembelajaranId = $request->input('pembelajaran_id'); // Ambil pembelajaran_id dari request

        $enrollment = Enrollments::where('siswa_id', $id)
            ->where('pembelajaran_id', $pembelajaranId)
            ->firstOrFail();

        $enrollment->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Pendaftaran siswa telah disetujui.');
    }

    public function reject($id, Request $request)
    {
        $pembelajaranId = $request->input('pembelajaran_id');

        $enrollment = Enrollments::where('siswa_id', $id)
            ->where('pembelajaran_id', $pembelajaranId)
            ->firstOrFail();

        $enrollment->update(['status' => 'rejected']);

        return redirect()->back()->with('error', 'Pendaftaran siswa telah ditolak.');
    }

    public function destroy($id, Request $request)
    {
        $pembelajaranId = $request->input('pembelajaran_id');

        $enrollment = Enrollments::where('siswa_id', $id)
            ->where('pembelajaran_id', $pembelajaranId)
            ->firstOrFail();

        $enrollment->delete();

        return redirect()->back()->with('success', 'Data pendaftaran berhasil dihapus.');
    }


    public function batchUpdate(Request $request)
    {
        $pembelajaranId = $request->input('pembelajaran_id');
        $siswaIds = $request->input('siswa_ids', []);
        $action = $request->input('action');

        if (empty($siswaIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu siswa untuk diproses.');
        }

        $status = $action === 'approve' ? 'approved' : 'rejected';

        // Update status untuk siswa yang dipilih
        Enrollments::whereIn('siswa_id', $siswaIds)
            ->where('pembelajaran_id', $pembelajaranId)
            ->update(['status' => $status]);

        return redirect()->back()->with('success', 'Status siswa berhasil diperbarui.');
    }

    public function batchDelete(Request $request)
    {
        $pembelajaranId = $request->input('pembelajaran_id');
        $siswaIds = $request->input('siswa_ids', []); // Ambil array langsung

        if (empty($siswaIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu siswa untuk dihapus.');
        }

        // Hapus data enrollments berdasarkan siswa_id dan pembelajaran_id
        Enrollments::whereIn('siswa_id', $siswaIds)
            ->where('pembelajaran_id', $pembelajaranId)
            ->delete();

        return redirect()->back()->with('success', 'Data pendaftaran berhasil dihapus.');
    }
}
