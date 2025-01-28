<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class TwoFactorController extends Controller
{
    // Show 2FA settings page
    public function show()
    {
        return view('admin.2fa');
    }

    // Enable 2FA using DB Query Builder
    public function enable(Request $request)
    {
       $staffId = session('staff_id');
        $userId = session('user_id');
if ($userId) {
    DB::table('users')
            ->where('id', $userId)
            ->update(['two_factor_enabled' => 1]);
} elseif ($staffId) {
    DB::table('staff')
            ->where('id', $staffId)
            ->update(['two_factor_enabled' => 1]);
} else {
    echo "No User or Staff ID found.";
}
        
        return redirect()->back()->with('success', 'Two-Factor Authentication enabled.');
    }

    // Disable 2FA using DB Query Builder
    public function disable()
    {
         $staffId = session('staff_id');
        $userId = session('user_id');
if ($userId) {
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'two_factor_enabled' => 0,
                'two_factor_code' => null,
                'two_factor_expires_at' => null
            ]);
}
elseif($staffId)
{
        DB::table('staff')
            ->where('id', $staffId)
            ->update([
                'two_factor_enabled' => 0,
                'two_factor_code' => null,
                'two_factor_expires_at' => null
            ]);
}

        return redirect()->back()->with('success', 'Two-Factor Authentication disabled.');
    }

    // Show verification page
    public function showVerify()
    {
        if (!session()->has('2fa:user:id')) {
            return redirect('/login');
        }

        return view('auth.2fa-verify');
    }

    // Verify 2FA Code at Login
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $userId = session('2fa:user:id');
// echo $userId;
// echo '<br>';
        // Fetch user using Query Builder
        $user = DB::table('users')->where('id', $userId)->first();
        // echo $user;
        // if (!$user) {
        //     return redirect('/login');
        // }
        
        if(!isset($user->two_factor_code))
        {
            $user = DB::table('staff')->where('id', $userId)->first();
        }
        
        // print_r($user->two_factor_code);
        // print_r($request->code);
        // exit;

        // Check if code is correct and not expired
        if ($user->two_factor_code === $request->code && Carbon::now('UTC')->lte(Carbon::parse($user->two_factor_expires_at)->timezone('UTC'))) {
            // Clear 2FA session and update DB
            session()->forget('2fa:user:id');

            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'two_factor_code' => null,
                    'two_factor_expires_at' => null
                ]);
                
            $role = DB::table('staff')->join('roles','roles.id','staff.role_id')->where('staff.id', $userId)
            ->first();
            
            if(isset($role->name))
            {
            // Auth::loginUsingId($userId);
            Session::put('staff_id', $user->id);
            Session::put('staff_name', $user->name);
            Session::put('staff_email', $user->email);
            Session::put('staff_role', $role->name);
            $request->session()->regenerate();
            return redirect()->intended('/merchant/dashboard')->with('success', 'Logged in successfully as staff.');
            }
            else
            {
                Session::put('staff_id', $user->id);
            Session::put('staff_name', $user->name);
            Session::put('staff_email', $user->email);
            Session::put('staff_role', "User");
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Logged in successfully as staff.');
            
            }
            // Regenerate session to avoid session fixation
            

            // return redirect()->intended('/admin');
        }

        return back()->withErrors(['code' => 'Invalid or expired authentication code.']);
    }
}
