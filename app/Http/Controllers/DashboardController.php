<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        // Initialize deposit and withdrawal totals
        $totalDeposit = DB::table('deposits')->sum('amount');
        $avgDeposit = DB::table('deposits')->avg('amount');
        $countDeposit = DB::table('deposits')->count('amount');
        $topDepositor = DB::table('deposits')
            ->select('username', DB::raw('COUNT(*) as count'))
            ->groupBy('username')
            ->orderByDesc('count')
            ->first();
        $totalWithdraw = DB::table('withdrawals')->sum('amount');
        $avgWithdraw = DB::table('withdrawals')->avg('amount');
        $countWithdraw = DB::table('withdrawals')->count('amount');
        $topWithdraw = DB::table('withdrawals')
            ->select('username', DB::raw('COUNT(*) as count'))
            ->groupBy('username')
            ->orderByDesc('count')
            ->first();

        // Prepare weekly chart labels and data
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Variables to store chart data for deposits and withdrawals
        $chartLabels = [];
        $depositData = [];
        $withdrawData = [];

        // Get the start and end date of the current month
        $startDate = Carbon::createFromDate($currentYear, $currentMonth, 1); // Start from the 1st of the current month
        $endDate = $startDate->copy()->endOfMonth(); // Get the end date of the current month

        while ($startDate->lte($endDate)) {
            $weekEnd = $startDate->copy()->endOfWeek(); // End of the week
            if ($weekEnd->gt($endDate)) {
                $weekEnd = $endDate; // Ensure we don't go past the month's end
            }

            // Format week range for labels
            $chartLabels[] = $startDate->format('M d') . ' - ' . $weekEnd->format('M d');

            // Calculate total deposit for the current week
            $totalDepositForWeek = DB::table('deposits')
                ->whereBetween('created_at', [$startDate, $weekEnd])
                ->sum('amount');
            $depositData[] = $totalDepositForWeek;

            // Calculate total withdrawal for the current week
            $totalWithdrawForWeek = DB::table('withdrawals') // Assuming a `withdrawals` table
                ->whereBetween('created_at', [$startDate, $weekEnd])
                ->sum('amount');
            $withdrawData[] = $totalWithdrawForWeek;

            // Move to the next week
            $startDate = $weekEnd->addDay();
        }
        $userId = session('staff_id');
        $roleId = DB::table('staff')
        ->where('id', $userId)
        ->value('role_id');

        $roleName = DB::table('roles')
            ->where('id', $roleId)
            ->value('name');

        $permissions = DB::table('permissions')
        ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
        ->where('permission_role.role_id', $roleId)
        ->pluck('permissions.name');

        $usddeposit = DB::table('deposits')->where('user_id',$userId)->where('currency','USD')->where('status','Completed')->sum('amount');
        $eurdeposit = DB::table('deposits')->where('user_id',$userId)->where('currency','EUR')->where('status','Y')->sum('amount');
        $usdwithdrawal = DB::table('withdrawals')->where('userid',$userId)->where('currency','USD')->where('status','Paid')->sum('amount');
        $eurwithdrawal = DB::table('withdrawals')->where('userid',$userId)->where('currency','EUR')->where('status','Paid')->sum('amount');
        
        //Donot remove this condition
        // if ($usddeposit > $usdwithdrawal) {
            $usddeposit = round($usddeposit, 2);
            
        // } else {
        //     $usddeposit = 0;
        // }
        // if ($eurdeposit > $eurwithdrawal) {
            $eurdeposit = round($eurdeposit, 2);
        // } else {
        //     $eurdeposit = 0;
        // }
        
        $depositCount = DB::table('deposits')->where('user_id',$userId)->count();
        $withdrawalCount = DB::table('withdrawals')->where('userid',$userId)->count();
        $transactions = $depositCount + $withdrawalCount;
        $moneyout = DB::table('withdrawals')->where('userid',$userId)->where('status','Paid')->sum('amount');
        // Now both $depositData and $withdrawData hold totals for each week
        // Return the view with all the data
        return view('admin.dashboard', compact('chartLabels', 'depositData', 'withdrawData', 'totalDeposit', 'avgDeposit', 'countDeposit', 'topDepositor', 'totalWithdraw', 'avgWithdraw', 'countWithdraw', 'topWithdraw','userId','permissions','roleName','usddeposit','eurdeposit','usdwithdrawal','eurwithdrawal','depositCount','withdrawalCount','transactions','moneyout'));
    }
    
    public function profile()
    {
        $userId = session('staff_id');
        $user = DB::table('staff')->where('id', $userId)->first();
        return view('admin.profile',compact('user'));
    }
    
    
    public function updateprofile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
        
        
        $name = $request->input('name');
        $email = $request->input('email');
        
        $staffData = [
            'name' => $name,
            'email' => $email,
        ];
        if ($request->filled('password')) {
            $staffData['password'] = bcrypt($request->input('password'));
        }
        
        DB::table('staff')
            ->where('id', $id)
            ->update($staffData);
        Session::put('staff_name', $name);
        Session::put('staff_email', $email);
        
        return redirect()->back()->with('success',"Profile Updated Successfully");
    }
    
    
    public function userprofile()
    {
        $userId = session('staff_id');
        $user = DB::table('users')->where('id', $userId)->first();
        return view('admin.profile',compact('user'));
    }
    
    
    public function updateuserprofile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
        
        
        $name = $request->input('name');
        $email = $request->input('email');
        
        $staffData = [
            'name' => $name,
            'email' => $email,
        ];
        if ($request->filled('password')) {
            $staffData['password'] = bcrypt($request->input('password'));
        }
        
        DB::table('users')
            ->where('id', $id)
            ->update($staffData);
            
        Session::put('staff_name', $name);
        Session::put('staff_email', $email);
        
        return redirect()->back()->with('success',"Profile Updated Successfully");
    }


}
