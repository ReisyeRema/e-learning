<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RoleSelectController extends Controller
{
    public function show()
    {
        $roles = Auth::user()->roles;
        return view('auth.choose-role', compact('roles'));
    }

    public function select(Request $request)
    {
        $userRoles = Auth::user()->roles->pluck('name');

        $request->validate([
            'role' => ['required', function ($attribute, $value, $fail) use ($userRoles) {
                if (!$userRoles->contains($value)) {
                    $fail('Role tidak valid.');
                }
            }],
        ]);

        session(['active_role' => $request->role]);

        return redirect()->route('dashboard');
    }
}
