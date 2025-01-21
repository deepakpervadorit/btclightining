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
use Carbon\Carbon;

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
        if ($staff->two_factor_enabled) {
            // Generate a random 6-digit code
            $code = mt_rand(100000, 999999);

            // Store it in the database with an expiration time
            DB::table('staff')
                ->where('id', $staff->id)
                ->update([
                    'two_factor_code' => $code,
                    'two_factor_expires_at' => Carbon::now()->addMinutes(10),
                ]);

            // Send the 2FA code via email
            Mail::raw("Your 2FA Code is: $code", function ($message) use ($staff) {
                $message->to($staff->email)
                        ->subject('Your Two-Factor Authentication Code');
            });

            // Store user ID in session and log out
            session(['2fa:user:id' => $staff->id]);
            Auth::logout();
            return redirect('/2fa/verify');
        }

        // Store session variables
        Session::put('staff_id', $staff->id);
        Session::put('staff_name', $staff->name);
        Session::put('staff_email', $staff->email);
        Session::put('staff_role', $role->name);
        // Regenerate session to avoid session fixation
        $request->session()->regenerate();

        return redirect()->intended('/merchant/dashboard')->with('success', 'Logged in successfully as staff.');
    }

    // Determine if the credentials are valid for users
    if ($user && Hash::check($credentials['password'], $user->password)) {
        if ($user->two_factor_enabled) {
            // Generate a random 6-digit code
            $code = mt_rand(100000, 999999);

            // Store it in the database with an expiration time
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'two_factor_code' => $code,
                    'two_factor_expires_at' => Carbon::now()->addMinutes(10),
                ]);

            // Send the 2FA code via email
            Mail::raw("Your 2FA Code is: $code", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your Two-Factor Authentication Code');
            });

            // Store user ID in session and log out
            session(['2fa:user:id' => $user->id]);
            Auth::logout();

            return redirect('/2fa/verify');
        }

        // Store session variables
        if ($user->verify == '1') {
            Session::put('staff_id', $user->id);
            Session::put('staff_name', $user->name);
            Session::put('staff_email', $user->email);
            Session::put('staff_role', "User");

            // Regenerate session to avoid session fixation
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')->with('success', 'Logged in successfully as user.');
        } else {
            return back()->withErrors([
                'email' => 'Your account is pending verification.',
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
