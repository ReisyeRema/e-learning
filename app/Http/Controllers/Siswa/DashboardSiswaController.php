<?php

namespace App\Http\Controllers\Siswa;

use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;

class DashboardSiswaController extends Controller
{
    public function index()
    {
        $profileSekolah = ProfilSekolah::first(); 
        return view('pages.siswa.dashboard.index', compact('profileSekolah'));
        
    }
}
