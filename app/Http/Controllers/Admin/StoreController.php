<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\CheckbookService;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use DateTime;
use DateTimeZone;
use App\Mail\WelcomeMailMerchant;
use Illuminate\Support\Facades\Mail;
use App\Models\PaymentGateway;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    protected $checkbookService;

    public function __construct(CheckbookService $checkbookService)
    {
        $this->checkbookService = $checkbookService;
    }
    public function index()
    {
        // Join 'user' with 'role_user' to fetch the role_id, and then join with 'roles' to fetch the role name
        $user = DB::table('staff')->where('staff.role_id','9')
                    ->join('role_staff', 'staff.id', '=', 'role_staff.staff_id')  // Join staff with role_staff
                    ->join('roles', 'role_staff.role_id', '=', 'roles.id')       // Join role_staff with roles to get role name
                    ->select('staff.*', 'roles.name as role_name')
                    ->where('roles.name', 'Merchant')
                    ->groupBy('staff.id')
                    ->orderBy('created_at', 'desc')->get();

        return view('admin.store.index', compact('user'));
    }

    public function deposits($userid)
    {
        // Join 'user' with 'role_user' to fetch the role_id, and then join with 'roles' to fetch the role name
        $userids = DB::table('users')->where('created_by',$userid)->pluck('id')->toArray();
        $deposits = DB::table('deposits')->whereIn('user_id',$userids)->orderBy('created_at', 'desc')->get();

        return view('admin.deposit', compact('deposits'));
    }

    public function withdrawals($userid){
        $userids = DB::table('users')->where('created_by',$userid)->pluck('id')->toArray();
        $deposits = DB::table('withdrawals')->whereIn('userid',$userids)->orderBy('created_at', 'desc')->get();
        return view('admin.withdrawal', compact('deposits'));
    }

    public function checkbook_users()
    {
        $staffId = session('staff_id'); // Retrieves 'staff_id' from the session
        // Join 'user' with 'role_user' to fetch the role_id, and then join with 'roles' to fetch the role name
        $user = DB::table('user_account')->orderBy('created_at', 'desc')->get();

        return view('admin.useraccount.index', compact('user'));
    }
    public function checkbook_usersbyid()
    {
        $staffId = session('staff_id'); // Retrieves 'staff_id' from the session
        // Join 'user' with 'role_user' to fetch the role_id, and then join with 'roles' to fetch the role name
        $user = DB::table('user_account')->where('userid',$staffId)->orderBy('created_at', 'desc')->get();
        return view('admin.useraccount.index', compact('user'));
    }


    // Show the form for creating a new user member
    public function create()
    {
        $payment_gateways = PaymentGateway::all();
        $games = DB::table('games')->get();
        return view('admin.store.create', compact('payment_gateways','games'));
    }

    // Store a newly created user member in storage
    public function store(Request $request)
    {

    $name = $request->input('name');
    $email = $request->input('email');
    $password = bcrypt($request->input('password'));
    $gateways = implode(",",$request->input('gateways'));
    $game_provider = implode(',',$request->input('game_provider'));
    $deposit_fees = $request->input('deposit_fees');
    $withdraw_fees = $request->input('withdraw_fees');
    $randomString = Str::random(6);

    // Insert user's payment account into the database
    $id = DB::table('staff')->insertGetId([
        'merchant_id' => $randomString,
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'role_id' => 9,
    ]);

    $staff = DB::table('staff')->where('id',$id)->first();

    DB::table('role_staff')->insert([
        'staff_id' => $id,
        'role_id' => 9,
    ]);

    DB::table('store_details')->insert([
        'store_id' => $id,
        'gateways' => $gateways,
        'transacxtion_fees' => $deposit_fees,
        'withdraw_fees' => $withdraw_fees,
        'game_providers' => $game_provider
    ]);


    Mail::to($staff->email)->send(new WelcomeMailMerchant($staff,$request->input('password')));
    // Redirect with success message
    return redirect()->route('admin.merchant.list')->with('success',' Account created successfully');
}


    // Show the form for editing the specified user member
    public function edit($id)
    {
        // Fetch the user member
        $user = DB::table('staff')->where('id', $id)->first();
        $user_details = DB::table('store_details')->where('store_id', $id)->first();
        $gateways = $user_details ? explode(',', $user_details->gateways) : [];
        $game_providers = $user_details ? explode(',', $user_details->game_providers) : [];
        $games = DB::table('games')->get();
        $payment_gateways = PaymentGateway::all();

        return view('admin.store.edit', compact('user','user_details','gateways','game_providers','games','payment_gateways'));
    }


    // Update the specified user member in storage
    public function update(Request $request, $id)
    {
        // Validate the request data if needed
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'gateways' => 'nullable|array', // Ensure gateways is an array
            'game_provider' => 'nullable|array', // Ensure game_provider is an array
            'deposit_fees' => 'nullable|numeric',
            'withdraw_fees' => 'nullable|numeric',
        ]);
    
        // Prepare the data for update
        $name = $request->input('name');
        $email = $request->input('email');
        $gateways = implode(',', $request->input('gateways', [])); // Default to empty array if gateways is null
        $game_provider = implode(',', $request->input('game_provider', [])); // Default to empty array if game_provider is null
        $deposit_fees = $request->input('deposit_fees');
        $withdraw_fees = $request->input('withdraw_fees');
    
        // Update the staff table
        $staffData = [
            'name' => $name,
            'email' => $email,
        ];
    
        // Only update the password if a new one is provided
        if ($request->filled('password')) {
            $staffData['password'] = bcrypt($request->input('password'));
        }
    
        DB::table('staff')
            ->where('id', $id)
            ->update($staffData);
    
        // Update the store_details table
        DB::table('store_details')
            ->where('store_id', $id)
            ->update([
                'gateways' => $gateways,
                'transacxtion_fees' => $deposit_fees,
                'withdraw_fees' => $withdraw_fees,
                'game_providers' => $game_provider,
            ]);
    
        // Redirect with success message
        return redirect()->route('admin.merchant.list')->with('success', 'Account updated successfully');
    }


    // Remove the specified user member from storage
    public function destroy(Request $request, $id)
{
        $payment_method = $request->input('payment_method');
        $userid = $request->input('userid');
        $api_id = $request->input('api_id');
        $checkbookuser = DB::table('checkbook_users')->where('userid',$userid)->first();
        $api_key = $checkbookuser->api_key;
        $api_secret_key = $checkbookuser->api_secret_key;
        DB::table('user_account')->where('id', $id)->where('payment_method',$payment_method)->delete();
    if($payment_method == 'ZELLE'){
        $zelle_id = $api_id;
        $zelle = $this->checkbookService->deleteZelleAccount($api_key, $api_secret_key, $zelle_id);
    } else if($payment_method == 'CARD'){
        $card_id = $api_id;
        $l = $this->checkbookService->deleteCard($api_key, $api_secret_key, $card_id);
    } else if($payment_method == 'VCC'){
        $card_id = $api_id;
        $vcc = $this->checkbookService->deleteVirtualCard($api_key, $api_secret_key, $card_id);
    }
        return redirect()->back()->with('success', 'Payment Method Deleted successfully');
}
   public function toggleVerify($id)
    {

        // Retrieve the member by ID
        $member = DB::table('users')->where('id', $id)->first();
        if (!$member) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Toggle the 'verify' field (if it's 1, change to 0, otherwise change to 1)
        // Update the verification status in the database
        DB::table('users')->where('id', $id)->update(['verify' => '1']);

        // Redirect back to the previous page or a specific page with a success message
        return redirect()->back()->with('success', 'Verification status updated successfully.');
    }

    public function virtualcard(Request $request)
    {
        // Get staff ID from the session
        $staffId = session('staff_id');

        // Fetch API credentials from the database
        $vcc = DB::table('checkbook_users')->where('userid', $staffId)->first();

        if (!$vcc) {
            // Handle the case where user details are not found
            return redirect()->back()->with('error', 'User details not found.');
        }

        // Use provided API key and secret key
        $api_key = $vcc->api_key; // Public key: 'a622858b6474407b88e9eff0d9dc525a'
        $api_secret_key = $vcc->api_secret_key; // Secret key: 'lI6U9055NGs1cUkXYLiD68BDk0Lpbp'
        // dd($api_key,$api_secret_key);
        // Fetch virtual card details using CheckbookService
        $vccDetails = $this->checkbookService->virtualcard($api_key, $api_secret_key);
        if (empty($vccDetails['vccs'][0])) {
            // Handle the case where no virtual card is found
            return redirect()->back()->with('error', 'No virtual card found.');
        }

        $vccid = $vccDetails['vccs'][0]['id'];
        $expiration_date = $vccDetails['vccs'][0]['expiration_date'];
        $balance = $vccDetails['vccs'][0]['balance'];
        $vccCard = $this->checkbookService->virtualcardDetails($api_key, $api_secret_key, $vccid);
        $transactions = $vccCard['transactions'];
        // Convert expiration date to a UNIX timestamp
        $date = new DateTime($expiration_date, new DateTimeZone('UTC'));
        $timestamp = $date->getTimestamp();

        // Create JWT Header and Payload
        $header = json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT',
            'kid' => $api_key // Public key
        ]);

        $payload = json_encode([
            'id' => $vccid,
            'exp' => $timestamp
        ]);

        // Encode Header and Payload as Base64
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Generate Signature using the Secret Key
        $signature = hash_hmac(
            'sha256',
            $base64UrlHeader . "." . $base64UrlPayload,
            $api_secret_key, // Use the secret key for signing
            true
        );

        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Complete JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        // Log JWT for debugging purposes
        // dd($header, $payload, $jwt);

        // Return the virtual card view with the necessary parameters
        return view('admin.user.virtualcard', compact('vccid', 'jwt','balance','transactions'));
    }
    public function virtualcard_callback(Request $request){
        dd($request);
    }


    /*public function virtualcard(Request $request){
        $staffId = session('staff_id');
        $vcc = DB::table('checkbook_users')->where('userid',$staffId)->first();
        $api_key = $vcc->api_key;
        $api_secret_key = $vcc->api_secret_key;
         $vcc = $this->checkbookService->virtualcard($api_key,$api_secret_key);
         $vccid = $vcc['vccs'][0]['id'];
         $expiration_date = $vcc['vccs'][0]['expiration_date'];
         $date = new DateTime($expiration_date, new DateTimeZone('UTC'));
        $timestamp = $date->getTimestamp();
                 $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        // Payload
        $payload = json_encode(['id' => $vccid, 'exp' => $timestamp]);

        // Secret Key
        $secretKey = $api_secret_key;

        // Encode Header and Payload as Base64
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Signature (using SHA-256)
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
                    // User found, return user details
        return view('admin.user.virtualcard',compact('vccid','jwt'));
    }*/
// public function toggleVerify($id)
// {

//     // Retrieve the member by ID
//     $member = DB::table('users')->where('id', $id)->first();
//     if (!$member) {
//         return redirect()->back()->with('error', 'User not found.');
//     }

//     // Toggle the 'verify' field (if it's 1, change to 0, otherwise change to 1)
//     // Update the verification status in the database
//     DB::table('users')->where('id', $id)->update(['status' => '1']);

//     // Redirect back to the previous page or a specific page with a success message
//     return redirect()->back()->with('success', 'Verification status updated successfully.');
// }
}
