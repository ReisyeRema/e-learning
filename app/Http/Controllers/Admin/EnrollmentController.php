<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Enrollments;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EnrollmentRequest;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enroll_siswa = Enrollments::with(['siswa', 'pembelajaran'])->get();
        $siswa = User::getSiswa(); 
        $pembelajaran = Pembelajaran::all();
        return view('pages.admin.enrollSiswa.index', compact('enroll_siswa','siswa','pembelajaran'));
    }


    public function store(EnrollmentRequest $request)
    {

        Enrollments::create([
            'siswa_id' => $request->siswa_id,
            'pembelajaran_id' => $request->pembelajaran_id
        ]);

        return redirect()->route('enroll-siswa.index')->with('success', 'Kelas Berhasil Ditambah');
    }


    public function update(EnrollmentRequest $request, $id)
    {
        $enrollment = Enrollments::find($id);

        $enrollment->update([
            'siswa_id' => $request->siswa_id,
            'pembelajaran_id' => $request->pembelajaran_id
        ]);

        return redirect()->route('enroll-siswa.index')->with('success', 'Kelas Berhasil Diupdate');

    }

    public function destroy($id)
    {
        $enrollment = Enrollments::find($id);

        $enrollment->delete();
        return redirect()->route('enroll-siswa.index')->with('success', 'Kelas Berhasil Dihapus');

    }
}
