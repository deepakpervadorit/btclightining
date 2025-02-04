<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Models\User;
use App\Models\Staff;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    
    public function sendResetLinkEmail(Request $request)
    {
        // Validate the email input
        $request->validate(['email' => 'required|email']);

        // Check if the email exists in the users table
        $user = DB::table('users')->where('email', $request->email)->first();
        $broker = 'users'; // Default password broker

        // If not found in users, check the staff table
        if (!$user) {
            $user = DB::table('staff')->where('email', $request->email)->first();
            $broker = 'staff'; // Use the 'staff' password broker
        }

        // If the email is not found in either table, return an error
        if (!$user) {
            return back()->withErrors(['email' => __('We could not find a user with that email address.')]);
        }

        // Send the reset link using the correct password broker
        $status = Password::broker($broker)->sendResetLink(['email' => $request->email]);

        // Respond based on the status
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
