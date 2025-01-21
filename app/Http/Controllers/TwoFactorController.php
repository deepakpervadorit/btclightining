<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

        DB::table('users')
            ->where('id', $userId)
            ->update([
                'two_factor_enabled' => 0,
                'two_factor_code' => null,
                'two_factor_expires_at' => null
            ]);

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
echo $userId;
echo '<br>';
        // Fetch user using Query Builder
        $user = DB::table('users')->where('id', $userId)->first();
        echo $user;
        if (!$user) {
            return redirect('/login');
        }

        // Check if code is correct and not expired
        if ($user->two_factor_code === $request->code && Carbon::now()->lte(Carbon::parse($user->two_factor_expires_at))) {
            // Clear 2FA session and update DB
            session()->forget('2fa:user:id');

            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'two_factor_code' => null,
                    'two_factor_expires_at' => null
                ]);

            Auth::loginUsingId($userId);

            return redirect()->intended('/admin');
exit;
        }

        return back()->withErrors(['code' => 'Invalid or expired authentication code.']);
    }
}
