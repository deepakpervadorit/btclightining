<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';
    
    
    public function reset(Request $request)
    {
        // Validate the reset request
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Check if the email exists in the users table
        $user = DB::table('users')->where('email', $request->email)->first();
        $table = 'users'; // Default to users table

        // If not found in users, check the staff table
        if (!$user) {
            $user = DB::table('staff')->where('email', $request->email)->first();
            $table = 'staff';
        }

        // If user not found in either table, return an error
        if (!$user) {
            return back()->withErrors(['email' => __('We could not find a user with that email address.')]);
        }

        // Validate the reset token
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return back()->withErrors(['email' => __('Invalid or expired password reset token.')]);
        }

        // Reset the password in the correct table
        DB::table($table)->where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the reset token entry
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect($this->redirectTo)->with('status', __('Your password has been reset successfully!'));
    }
}
