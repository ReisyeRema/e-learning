<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\ExportOperator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Super Admin', 'Admin']);
        })->get();

        return view('pages.superadmin.user.index', compact('users'));
    }

    public function create()
    {
        // Hanya menampilkan role Super Admin dan Admin
        $roles = Role::whereIn('name', ['Super Admin', 'Admin'])->pluck('name', 'name')->all();
        return view('pages.superadmin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'required',
        ], [
            'name.required' => 'Nama diperlukan.',
            'username.required' => 'Username diperlukan.',
            'email.required' => 'Email diperlukan.',
            'password.required' => 'Password diperlukan.',
            'foto.image' => 'File harus berupa gambar.',
            'roles.required' => 'Role diperlukan.',
        ]);

        $newImage = null; // Default value jika tidak ada foto

        // Simpan foto jika ada
        if ($request->hasFile('foto')) {
            $extension = $request->file('foto')->getClientOriginalExtension();
            $newImage = $request->name . '_' . now()->timestamp . '.' . $extension;
            $request->file('foto')->storeAs('foto_user', $newImage, 'public');
        }

        $password = $request->password;

        // Simpan data user terlebih dahulu
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($password),
            'password_plain' => $password,
            'foto' => $newImage
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }


    public function edit($id)
    {
        // Hanya menampilkan role Super Admin dan Admin
        $roles = Role::whereIn('name', ['Super Admin', 'Admin'])->pluck('name', 'name')->all();
        $user = User::find($id);
        $userRoles = $user->roles()->pluck('name')->all();
        return view('pages.superadmin.user.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'required',
        ], [
            'name.required' => 'Nama diperlukan.',
            'username.required' => 'Username diperlukan.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'foto.image' => 'File harus berupa gambar.',
            'roles.required' => 'Role diperlukan.',
        ]);

        // Data default tanpa foto
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ];

        // Periksa apakah password diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password); 
            $data['password_plain'] = $request->password; 
        }        


        // Update foto jika ada file baru
        if ($request->hasFile('foto')) {
            $extension = $request->file('foto')->getClientOriginalExtension();
            $newImage = $request->name . '_' . now()->timestamp . '.' . $extension;

            // Simpan foto baru
            $request->file('foto')->storeAs('foto_user', $newImage, 'public');

            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete('foto_user/' . $user->foto);
            }

            $data['foto'] = $newImage; 
        }

        // Update data pengguna
        $user->update($data);

        // Sinkronisasi role
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }


    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->foto) {
            Storage::delete('foto_user/' . $user->foto);
        }
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    function export_excel()
    {
        return Excel::download(new ExportOperator, "operator.xlsx");
    }
}
