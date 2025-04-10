<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::get();
        return view('pages.superadmin.role.index', ['roles' => $roles]);
    }

    public function create()
    {
        return view('pages.superadmin.role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name'
            ]
        ]);

        Role::create([
            'name' => $request->name
        ]);

        return redirect()->route('roles.index')->with('success', 'Role Created Successfully');
    }

    public function edit($id)
    {
        $roles = Role::find($id);
        return view('pages.superadmin.role.edit', compact('roles'));
    }

    public function update(Request $request, $id)
    {
        $roles = Role::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name,' . $roles->id
            ]
        ]);

        $roles->update([
            'name' => $request->name
        ]);

        return redirect()->route('roles.index')->with('success', 'Role Update Successfully');
    }

    public function destroy($id)
    {
        $roles  = Role::find($id);


        $roles->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }

    public function addPermissionToRole($id)
    {
        $permissions = Permission::get();
        $role  = Role::findOrFail($id);
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $role->id)->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')->all();
        return view('pages.superadmin.role.add-permissions', ['role' => $role, 'permissions' => $permissions, 'rolePermissions' => $rolePermissions]);
    }

    public function givePermissionToRole(Request $request, $id)
    {
        $request->validate([
            'permission' => 'required'
        ]);

        $role  = Role::findOrFail($id);
        $role->syncPermissions($request->permission);

        return redirect()->back()->with('success', 'Permissions added to role');
    }
}
