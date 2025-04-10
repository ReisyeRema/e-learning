<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
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

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     try {
    //         $request->authenticate(); // Proses autentikasi
    //         $request->session()->regenerate(); // Regenerasi session
    //         return redirect()->intended(route('dashboard')); // Redirect ke dashboard
    //     } catch (ValidationException $e) {
    //         // Jika login gagal
    //         return back()
    //         ->withInput($request->only('email'))
    //         ->withErrors($e->errors());
    //     }
    // }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            $user = Auth::user();

            // Pastikan user memiliki role 'Super Admin'
            if (!$user->roles->contains('name', 'Super Admin') && !$user->roles->contains('name', 'Admin') && !$user->roles->contains('name', 'Guru')) {
                Auth::logout();
                return back()->withErrors(['email' => 'Anda tidak memiliki akses ke halaman ini.']);
            }
    

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
