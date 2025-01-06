<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        // Join 'staff' with 'role_staff' to fetch the role_id, and then join with 'roles' to fetch the role name
        $staff = DB::table('staff')
                    ->join('role_staff', 'staff.id', '=', 'role_staff.staff_id')  // Join staff with role_staff
                    ->join('roles', 'role_staff.role_id', '=', 'roles.id')       // Join role_staff with roles to get role name
                    ->select('staff.*', 'roles.name as role_name')               // Select staff data and role name
                    ->get();
    
        // Fetch all roles for dropdown when adding/editing staff members
        $roles = DB::table('roles')->get();
    
        return view('admin.staff.index', compact('staff', 'roles'));
    }
    
    
    

    // Show the form for creating a new staff member
    public function create()
    {
        $roles = DB::table('roles')->get();  // Fetch all roles
        return view('admin.staff.create', compact('roles'));
    }

    // Store a newly created staff member in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:staff',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);
        // Insert staff member into the database
        DB::table('staff')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully');
    }

    // Show the form for editing the specified staff member
    public function edit($id)
    {
        // Fetch the staff member
        $staff = DB::table('staff')->where('id', $id)->first();
    
        // Fetch the staff member's role from the role_staff table
        $staffRole = DB::table('role_staff')->where('staff_id', $id)->pluck('role_id')->first();  // Fetch role ID
    
        // Fetch all roles for the dropdown
        $roles = DB::table('roles')->get();
    
        return view('admin.staff.edit', compact('staff', 'roles', 'staffRole'));
    }
    

    // Update the specified staff member in storage
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:staff,email,' . $id,
            'role_id' => 'required|exists:roles,id',
        ]);
    
        // Update the staff member's information in the 'staff' table
        DB::table('staff')->where('id', $id)->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'updated_at' => now(),
        ]);
    
        // Update the staff member's role in the 'role_staff' table
        DB::table('role_staff')->where('staff_id', $id)->update([
            'role_id' => $validated['role_id'],   // Update the role in role_staff
            'updated_at' => now(),
        ]);
    
        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully');
    }
    

    // Remove the specified staff member from storage
    public function destroy($id)
    {
        DB::table('staff')->where('id', $id)->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully');
    }
}
