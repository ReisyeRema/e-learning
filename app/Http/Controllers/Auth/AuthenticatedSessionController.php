<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function create_siswa(): View
    {
        return view('auth.login-siswa');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            DB::table('login_activities')->insert([
                'user_id' => Auth::id(),
                'login_at' => now(),
            ]);

            $user = Auth::user();

            // Role check (boleh disesuaikan kalau kamu pakai permission dari Spatie)
            $allowedRoles = ['Super Admin', 'Admin', 'Guru', 'Wali Kelas'];
            if (!$user->roles->pluck('name')->intersect($allowedRoles)->count()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Anda tidak memiliki akses ke halaman ini.']);
            }

            // Jika user memiliki lebih dari 1 role
            if ($user->roles->count() > 1) {
                return redirect()->route('choose-role');
            }

            // Jika hanya punya satu role, langsung arahkan ke dashboard
            session(['active_role' => $user->roles->first()->name]);
            return redirect()->intended(route('dashboard'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }


    public function store_siswa(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->roles->contains('name', 'Siswa')) {
                Auth::logout();
                return back()->withErrors(['email' => 'Anda tidak memiliki akses ke halaman ini.']);
            }

            // Cek apakah siswa sudah diverifikasi
            if (!$user->is_verified) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda belum diverifikasi oleh admin.']);
            }

            return redirect()->route('dashboard-siswa.index');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Jika user memiliki role 'Siswa', arahkan ke login-siswa, jika tidak, ke login umum
        if ($user && $user->roles->contains('name', 'Siswa')) {
            return redirect()->route('login-siswa');
        }

        return redirect()->route('login');
    }
}
