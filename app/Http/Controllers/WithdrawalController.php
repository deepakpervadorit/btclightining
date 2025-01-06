<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index()
    {
        $deposits = DB::table('withdrawals')
    ->orderBy('created_at', 'asc')
    ->get();
        return view('admin.withdrawal',compact('deposits'));
    }
    public function destroy($id)
{
    // Delete the deposit using DB::table()
    DB::table('withdrawals')->where('id', $id)->delete();

    // Redirect back with a success message
    return redirect()->route('withdrawal')->with('success', 'Withdrawal deleted successfully.');
}
}
