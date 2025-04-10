<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotSiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login-siswa'); // Jika belum login, redirect ke login siswa
        }

        $user = Auth::user();

        // Jika bukan siswa, arahkan ke dashboard umum
        if (!$user->roles->contains('name', 'Siswa')) {
            return redirect()->route('dashboard');
        }

        // Jika user sudah siswa dan sedang mengakses dashboard siswa, lanjutkan akses
        return $next($request);
    }
}
