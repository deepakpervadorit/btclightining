<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\CheckbookService;
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use DateTime;
use DateTimeZone;

class UserController extends Controller
{
    protected $checkbookService;

    public function __construct(CheckbookService $checkbookService)
    {
        $this->checkbookService = $checkbookService;
    }
    public function index()
    {
        // Join 'user' with 'role_user' to fetch the role_id, and then join with 'roles' to fetch the role name
        $user = DB::table('users')->where('role','User')->orderBy('created_at', 'desc')->get();
        
        return view('admin.user.index', compact('user'));
    }
    
    public function checkbook_users()
    {
        $staffId = session('staff_id'); // Retrieves 'staff_id' from the session
        // Join 'user' with 'role_user' to fetch the role_id, and then join with 'roles' to fetch the role name
        $user = DB::table('user_account')->orderBy('created_at', 'desc')->get();
    $usercount = DB::table('user_account')->where('userid',$staffId)->count();
        return view('admin.useraccount.index', compact('user','usercount'));
    }
    public function checkbook_usersbyid()
    {
        $staffId = session('staff_id'); // Retrieves 'staff_id' from the session
        // Join 'user' with 'role_user' to fetch the role_id, and then join with 'roles' to fetch the role name
        $user = DB::table('user_account')->where('userid',$staffId)->orderBy('created_at', 'desc')->get();
        $usercount = DB::table('user_account')->where('userid',$staffId)->count();
        return view('admin.useraccount.index', compact('user','usercount'));
    }
    

    // Show the form for creating a new user member
    public function create()
    {
        $staffEmail = session('staff_email'); // Retrieves 'staff_id' from the session
        $staffName = session('staff_name');
        return view('admin.useraccount.create', compact('staffEmail','staffName'));
    }

    // Store a newly created user member in storage
    public function store(Request $request)
    {

    $staffId = session('staff_id'); // Retrieves 'staff_id' from the session
    $deposit_option = $request->input('deposit_option');
    $name = $request->input('username');
    $email = $request->input('email');

    // Fetch the existing user data
    $existingUser = DB::table('checkbook_users')->where('userid', $staffId)->first();

    // Set the values as if $createuser was populated
    $createuser = [
        'key' => $existingUser->api_key,
        'secret' => $existingUser->api_secret_key,
        'user_id' => $existingUser->user_id,
        'userid' => $existingUser->userid
    ];

    //$user = $this->checkbookService->getUserByUserId($name);
    $address = $request->input('line_1');
    $cardNumber = $request->input('card_number');
    $cvv = $request->input('cvv');
    $expirationDate = $request->input('expiration_date');

    // Check if the user exists already
    /*if (isset($user['error'])) {
        return response()->json(['error' => 'Unable to create or retrieve user', 'details' => $user]);
    }

    dd($user);
    // Check if user already exists in the response
    foreach ($user['users'] as $userData) {
        // Get the user_id from each item in the array
        $userId = $userData['user_id'];

        // If user exists, show a message and stop the process
        if ($userId == $name) {
            return redirect()->back()->with('success', 'Username is Already Used!');
        }
    }*/
    $checkbookUser = DB::table('user_account')->where('userid', $staffId)->where('payment_method',$deposit_option)->first();
    if (!empty($checkbookUser->payment_method) && $deposit_option == $checkbookUser->payment_method) {
    return redirect()->back()->with('danger', $deposit_option . ' Payment Method is Already Used!');
}
    

    // Successfully created user, now get API keys
    $api_key = $createuser['key'];
    $api_secret = $createuser['secret'];
    $checkbook_user_id = $createuser['user_id'];
    // $cardNumber = null; $cvv = null; $expirationDate = null;
    if($deposit_option == 'ZELLE'){
        $zelle = $this->checkbookService->createZelleAccount($email, $api_key, $api_secret);
        dd($zelle);
        $api_id = $zelle['id'];
    } else if($deposit_option == 'CARD'){
        $cardAcc = $this->checkbookService->createCardAccount($address, $cardNumber, $cvv, $expirationDate, $api_key, $api_secret);
        // dd($cardAcc);
        $api_id = $cardAcc['id'];
         // Assign card details
        $cardNumber = $cardAcc['card_number'];
        $cvv = null;
        $expirationDate = null;

    } else if($deposit_option == 'VCC'){
        $vcc = $this->checkbookService->createVCCAccount($email, $api_key, $api_secret);
        $api_id = $vcc['id'];
        // Assign card details from $vcc
        $cardNumber = $vcc['card_number'] ?? null;
        $cvv = $vcc['cvv'] ?? null;
        $expirationDate = $vcc['expiration_date'] ?? null;
    }
    

    // Insert user's payment account into the database
    DB::table('user_account')->insert([
        'userid' => $staffId,
        'payment_method' => $request->input('deposit_option'),
        'api_id' => $api_id,
        'user_id' => $checkbook_user_id,
        'email' => $request->input('email'),
        'line_1' => $request->input('line_1'),
        'city' => $request->input('city'),
        'state' => $request->input('state'),
        'country' => $request->input('country'),
        'zip_code' => $request->input('zip'),
        'card_number' => $cardNumber ?? NULL, // Store card number if available,
        'cvv' => $cvv ?? NULL, // Store CVV if available
        'expiration_date' => $expirationDate ?? NULL, // Store expiration date if available
        'created_at' => now(),
    ]);

    // Redirect with success message
    return redirect()->route('user.checkbook_usersbyid')->with('success', $deposit_option. ' Account created successfully');
}


    // Show the form for editing the specified user member
    public function edit($id)
    {
        // Fetch the user member
        $user = DB::table('user_account')->where('id', $id)->first();
    
        return view('admin.useraccount.edit', compact('user'));
    }
    

    // Update the specified user member in storage
    public function update(Request $request, $id)
    {
        // Fetch the existing user data
        $existingUser = DB::table('checkbook_users')->where('user_id', $request->input('username'))->first();
        $api_key = $existingUser->api_key;
        $api_secret = $existingUser->api_secret_key;
        $address = $request->input('line_1');
        $cardNumber = $request->input('card_number');
        $cvv = $request->input('cvv');
        $expirationDate = $request->input('expiration_date'); 

        if(DB::table('user_account')->where('user_id', $request->input('username'))->where('payment_method','CARD')->exists()){

            $checkbookUser = DB::table('user_account')->where('user_id', $request->input('username'))->where('payment_method','CARD')->first();
            $card_id = $checkbookUser->api_id;
            $this->checkbookService->deletePrevCardAccount($card_id, $api_key, $api_secret);

        }
        

        $cardAcc = $this->checkbookService->createCardAccount($address, $cardNumber, $cvv, $expirationDate, $api_key, $api_secret);
        // Update the user member's information in the 'user' table
        DB::table('user_account')->where('id', $id)->update([
            'user_id' => $request->input('username'),
            'email' => $request->input('email'),
            'line_1' => $request->input('line_1'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'country' => $request->input('country'),
            'zip_code' => $request->input('zip'),
            'card_number' => $request->input('card_number'),
            'cvv' => $request->input('cvv'),
            'expiration_date' => $request->input('expiration_date'),
        ]);
    
        return redirect()->route('user.checkbook_usersbyid')->with('success', 'User updated successfully');
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
