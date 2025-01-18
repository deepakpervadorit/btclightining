<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Add this line
use App\Models\User;
use App\Services\CheckbookService;
use Illuminate\Support\Facades\Crypt;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function __construct(CheckbookService $checkbookService)
    {
        $this->checkbookService = $checkbookService;
    }

   public function showLoginForm()
    {
        return view('login');
    }


    // Handle login via 'staff' table

/*public function login(Request $request)
{
    // Validate login credentials
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Retrieve the staff member using email
    $staff = DB::table('staff')
        ->where('email', $credentials['email'])
        ->first();

    $users = DB::table('users')
        ->where('email', $credentials['email'])
        ->first();

    // Check if the staff exists and the password matches
    if ($staff && Hash::check($credentials['password'], $staff->password)) {
        // Log the user in manually using session
        Session::put('staff_id', $staff->id);
        Session::put('staff_name', $staff->name);
        Session::put('staff_email', $staff->email);
        // Session::put('staff_role', $staff->role);

        // Regenerate session to avoid session fixation
        $request->session()->regenerate();
        // Redirect to the dashboard or intended URL
        return redirect()->intended('/dashboard')->with('success', 'Logged in successfully.');
    } else if($users && Hash::check($credentials['password'], $users->password)) {
        // Same logic for users
        Session::put('staff_id', $users->id);
        Session::put('staff_name', $users->name);
        Session::put('staff_email', $users->email);
        // Session::put('staff_role', $users->role);

        // Regenerate session to avoid session fixation
        $request->session()->regenerate();
        // Redirect to the dashboard or intended URL
        return redirect()->intended('/dashboard')->with('success', 'Logged in successfully.');
    }

    // If login failed, redirect back with an error message
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->withInput();
}*/

public function login(Request $request)
{
    // Validate login credentials
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Attempt to find the user in the staff table
    $staff = DB::table('staff')
        ->where('email', $credentials['email'])
        ->first();

    // Attempt to find the user in the users table
    $user = DB::table('users')
        ->where('email', $credentials['email'])
        ->first();

        $role = DB::table('staff')->join('roles','roles.id','staff.role_id')->where('email', $credentials['email'])
        ->first();
    // Determine if the credentials are valid for staff
    if ($staff && Hash::check($credentials['password'], $staff->password)) {
        Session::put('staff_id', $staff->id);
        Session::put('staff_name', $staff->name);
        Session::put('staff_email', $staff->email);
        Session::put('staff_role', $role->name); // Add a role indicator
        $request->session()->regenerate();
        // $rolestaff = DB::table('role_staff')->insert([
        //     'staff_id' => $staff->id,
        //     'role_id' => $staff->role_id,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        return redirect()->intended('/merchant/dashboard')->with('success', 'Logged in successfully as staff.');
    }

    // Determine if the credentials are valid for users
    if ($user && Hash::check($credentials['password'], $user->password)) {
        if($user->verify == '1'){
        Session::put('staff_id', $user->id);
        Session::put('staff_name', $user->name);
        Session::put('staff_email', $user->email);
        Session::put('staff_role', 'User'); // Add a role indicator
        $request->session()->regenerate();

        // DB::table('role_staff')->insert([
        //     'staff_id' => $user->id,
        //     'role_id' => '5',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        return redirect()->intended('/dashboard')->with('success', 'Logged in successfully as user.');
        } else {
            return  back()->withErrors([
        'email' => 'Your account is On pending for verification.',
    ])->withInput();
        }
    }

    // If login fails, redirect back with an error message
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->withInput();
}


    public function showRegisterForm($merchantid)
    {
        $merchantid = Crypt::decrypt($merchantid);
        return view('auth.register',compact('merchantid'));
    }
    public function register(Request $request)
    {

        // Validate registration data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Ensure email is unique
            'password' => 'required|confirmed|min:8', // Password confirmation and minimum length
            'merchantid' => 'required',
        ]);

        // $createuser = $this->checkbookService->createUser($validated['name']);

        // if (isset($createuser['error'])) {
        //     // Abort user creation if username is already in use
        //     session()->flash('error', 'Username is already in use. Please choose a different name.');
        //     abort(400, 'Username already in use.');
        // }

        // Create the user with the validated data and hashed password
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Hash the password before saving
            'role' => 'User',
            'created_by' => $validated['merchantid'],
        ]);

        // DB::table('checkbook_users')->insert([
        //     'userid' => $user->id,
        //     'user_id' => $createuser['user_id'],
        //     'checkbook_id' => $createuser['id'],
        //     'api_key' => $createuser['key'],
        //     'api_secret_key' => $createuser['secret'],
        //     'created_at' => now(),
        // ]);

        // DB::table('checkbook_users')->insert([
        //     'userid' => $user->id,
        //     'user_id' => "techdeeppaksingh",
        //     'checkbook_id' => "d1d198496b5b40829cdb2d9072460a4b",
        //     'api_key' => "9ccbe3da99f64b6ebd12d99de2b7737b",
        //     'api_secret_key' => "BDaoPUkAZQbeFXhcJA85Da84XmrUB8",
        //     'created_at' => now(),
        // ]);

        DB::table('role_staff')->insert([
                'staff_id' => $user->id,
                'role_id' => '5',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        Mail::to($user->email)->send(new WelcomeMail($user,$validated['password']));

        // Redirect to the dashboard or intended page with a success message
        return redirect()->route('login')->with('success', 'Registration successful!');
    }

    // Handle logout
    public function logout(Request $request)
    {
        // Clear staff session
        Session::flush();

        // Regenerate session token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login page after logout
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
