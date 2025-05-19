<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index() 
    {
        $userid = session('staff_id');
        $role = session('staff_role');
        $mywithdrawals=[];
        if($role == "Merchant")
        {
            $users = DB::table('users')->where('created_by', $userid)->pluck('id');
            $withdrawals = DB::table('withdrawals')->whereIn('userid', $users)->orderBy('created_at', 'asc')->get();
            $mywithdrawals = DB::table('withdrawals')->where('merchant_id', $userid)->orderBy('created_at', 'asc')->get();
            $deposits = $withdrawals->merge($mywithdrawals);
        }
        elseif($role == "Superadmin")
        {
            $deposits = DB::table('withdrawals')
            ->orderBy('created_at', 'asc')
            ->get();
        }
        elseif($role == "User")
        {
            $deposits = DB::table('withdrawals')->where('userid', $userid)->orderBy('created_at', 'asc')->get();
        }
    //     $deposits = DB::table('withdrawals')
    // ->orderBy('created_at', 'asc')
    // ->get();
        return view('admin.withdrawal',compact('deposits','mywithdrawals'));
    }
    public function destroy($id)
{
    // Delete the deposit using DB::table()
    DB::table('withdrawals')->where('id', $id)->delete();

    // Redirect back with a success message
    return redirect()->route('withdrawal')->with('success', 'Withdrawal deleted successfully.');
}
}
