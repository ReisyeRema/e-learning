<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::get();
        return view('pages.superadmin.permission.index', ['permissions' => $permissions]);
    }

    public function create()
    {
        return view('pages.superadmin.permission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ]);

        Permission::create([
            'name' => $request ->name
        ]);

        return redirect()->route('permissions.index')->with('success','Permission Created Successfully');
    }

    public function edit($id)
    {
        $permissions = Permission::find($id);
        return view('pages.superadmin.permission.edit', compact('permissions'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name,'. $permission->id
            ]
        ]);

        $permission->update([
            'name' => $request ->name
        ]);

        return redirect()->route('permissions.index')->with('success','Permission Update Successfully');
    }

    public function destroy($id)
    {
        $permission  = Permission::find($id);
        
        
        $permission ->delete();
        return redirect()->route('permissions.index')->with('success', 'Berita deleted successfully');
    }
}
