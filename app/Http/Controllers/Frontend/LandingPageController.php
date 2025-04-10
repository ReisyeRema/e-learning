<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProfilSekolah;

class LandingPageController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all(); 
        $profileSekolah = ProfilSekolah::first(); 
        return view('pages.frontend.index', compact('kelas','profileSekolah')); 
    }
}
