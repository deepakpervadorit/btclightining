<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionsController extends Controller
{
    // Display a listing of the permissions
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    // Show the form for creating a new permission
    public function create()
    {
        return view('admin.permissions.create');
    }

    // Store a newly created permission in storage
    public function store(Request $request)
    {
        Permission::create($request->only('name'));
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully');
    }

    // Show the form for editing the specified permission
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    // Update the specified permission in storage
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update($request->only('name'));
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }

    // Remove the specified permission from storage
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
    }
}
