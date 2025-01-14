<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Price;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Square\Models\Money;
use Square\Models\CreatePaymentRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Square\SquareClient;
use Square\Models\CreatePaymentLinkRequest;
use Square\Exceptions\ApiException;
use App\Services\CheckbookService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Deposit;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;


class PaymentController extends Controller
{
    protected $checkbookService;

    public function __construct(CheckbookService $checkbookService)
    {
        $this->checkbookService = $checkbookService;
    }
        public function payments()
    {
        $deposits = DB::table('deposits')->get();
        return view('admin.payments', compact('deposits'));
    }

    public function showForm()
    {
        $games = DB::table('games')->get();
            $userId = session('staff_id');
        return view('deposit',compact('games','userId'));
    }

    public function showInvoice($id)
    {
        $invoice = DB::table('invoice_qrcode')->where('id',$id)->first();
        // $svgDataUrl = $request->input('svgDataUrl');
        // $checkoutUrl = $request->input('checkoutUrl');
        // $id = $request->input('id');
        // $invoiceid = $request->input('invoiceid');
        return view('invoice', compact('invoice'));
    }

    public function generateInvoiceQr(Request $request)
    {
        // Assuming you have the Lightning payment request in the response from the external API
        $paymentRequest = $request->input('payment_request'); // This would be passed from your front-end via AJAX or from the API
        $expires_at = $request->input('expires_at');
        $invoiceid = $request->input('invoiceid');
        // Generate the QR Code for the Lightning payment request
        $qrCodepng = QrCode::size(1000)
                    ->format('png')
                    ->generate($paymentRequest); // You can adjust the size as needed
        $tempImagePath = storage_path('app/public/qrcode.png');
        file_put_contents($tempImagePath, $qrCodepng);

        // Optimize the generated QR code image using Spatie's image optimizer
        ImageOptimizer::optimize($tempImagePath);

        // Optionally, encode the image to Base64 for use in your frontend
        $optimizedQrCode = file_get_contents($tempImagePath);
        $imgDataUrl = 'data:image/png;base64,' . base64_encode($optimizedQrCode);

        // Encode the SVG to a data URL
        // $imgDataUrl = 'data:image/png;base64,' . base64_encode($qrCodepng);
        // Return the QR code as a response, or save it as an image
        $invoice = DB::table('invoice_qrcode')->insertGetId([
            'invoice_id' => $invoiceid,
            'checkout_url' => $paymentRequest,
            'expires_at' => $expires_at,
            'qrcode_url' => $imgDataUrl,
            'user_id' => session('staff_id'),
        ]);
        return $invoice;
    }

    public function checkPaymentStatus(Request $request)
    {
        $invoiceId = $request->input('invoice_id');
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('SPEED_SECRET_KEY')),
        ])->get('https://api.tryspeed.com/payments/' . $invoiceId);

        if ($response->ok()) {
            $paymentData = $response->json();

            if ($paymentData['status'] === 'paid') {
                // DB::table('deposit_transactions')->where('transaction_id',$invoiceId)->update([
                //     'status' => 'Completed'
                // ]);
            return response()->json(['status' => "completed"]);
            }
        }
        return response()->json(['status' => $response->json('payment_status')]);
    }


    public function updateDepositTransaction($transactionId) {
        DB::table('deposit_transactions')->where('transaction_id',$transactionId)->update([
            'status' => 'Completed'
        ]);
        $updatedRow = DB::table('deposit_transactions')
        ->where('transaction_id', $transactionId)
        ->first();

        $deposit = Deposit::create([
            'session_id' => $updatedRow->transaction_id,
            'user_id' => $updatedRow->user_id,
            'amount' => $updatedRow->amount,
            'status' => $updatedRow->status,
            'payment_method' => $updatedRow->paymentGateway,
            'currency' => $updatedRow->currency,
        ]);
        if ($deposit) {
            return redirect('/dashboard');
        }

    }
    public function withdrawalForm()
    {
        $games = DB::table('games')->get();
        $uniqueId = Str::random(10);
        return view('withdrawal',compact('games','uniqueId'));
    }

    public function depositLink(Request $request)
    {

        // echo "<pre>";
        // print_r($request->toArray());
        // exit;
        // Extract the inputs
        $server = $request->input('server');
        $paymentMethod = $request->input('payment_method');
        $username = $request->input('username');
        $amount = $request->input('amount');

        // Generate a unique
        $uniqueId = Str::random(10);
        // DB::table('deposits')->insert([
        //     'unique_id' => $uniqueId,
        //     'server' => $server,
        //     'payment_method' => $paymentMethod,
        //     'username' => $username,
        //     'amount' => $amount,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        // Option 1: Redirect to the deposit link directly
        return view('deposit-link', compact('server', 'paymentMethod', 'username', 'amount', 'uniqueId'));
    }
    public function withdrawalLink(Request $request)
    {

        // echo "<pre>";
        // print_r($request->toArray());
        // exit;
        // Extract the inputs
        $server = $request->input('server');
        $paymentMethod = $request->input('payment_method');
        $username = $request->input('username');
        $gameusername = $request->input('game_username');
        $amount = $request->input('amount');

        // Generate a unique
        $uniqueId = Str::random(10);
        // DB::table('deposits')->insert([
        //     'unique_id' => $uniqueId,
        //     'server' => $server,
        //     'payment_method' => $paymentMethod,
        //     'username' => $username,
        //     'amount' => $amount,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        // Option 1: Redirect to the deposit link directly
        return view('withdrawal-link', compact('server', 'paymentMethod', 'username', 'amount', 'uniqueId','gameusername'));
    }

    public function processDeposit(Request $request)
   {
    $stripeSecretKey = env('STRIPE_SECRET');
    \Stripe\Stripe::setApiKey($stripeSecretKey);

    // Get the amount from the request
    $amount = $request->input('amount');

    try {
        // Create a new Price object in Stripe for the specified amount
        $price = \Stripe\Price::create([
            'unit_amount' => $amount * 100, // Amount in cents
            'currency' => 'usd', // Set your desired currency
            'nickname' => 'Deposit Amount',
            'product_data' => [
                'name' => 'Customer Deposit',
            ],
        ]);

        // Create a new Checkout session using the created Price ID
        $checkoutSession = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price' => $price->id, // Use the created price ID
                'quantity' => 1,
            ]],
            'mode' => 'payment', // Payment mode for one-time charges
            'success_url' => route('deposit.stripe.success'),
            'cancel_url' => route('show.deposit.form'),
        ]);
        $sessionId = $checkoutSession->id;
        Session::put('stripe_session', $sessionId);
        Session::put('amount', $amount);
        Session::put('username', $request->input('username'));
        Session::put('server', $request->input('server'));

        $stripeSecretKey = env('STRIPE_SECRET');
        \Stripe\Stripe::setApiKey($stripeSecretKey);

        // Retrieve the payment intent details
        $session = \Stripe\Checkout\Session::retrieve($sessionId);

        return response()->json($session);
        // Get the payment intent
        $paymentIntentId = $session->payment_intent;
        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

        // Check if the card is debit or credit
        $cardDetails = $paymentIntent->charges->data[0]->payment_method_details->card;
        $fundingType = $cardDetails->funding;


            // Return the Checkout session URL to the client
            return response()->json(['url' => $checkoutSession->url], 200);

        } catch (\Exception $e) {
            // Handle any errors that occur during the API request
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
   public function depositStripeSuccess(Request $request)
    {
    $stripeSecretKey = env('STRIPE_SECRET');
    \Stripe\Stripe::setApiKey($stripeSecretKey);

    // Retrieve necessary data from the session
    $sessionId = Session::get('stripe_session');
    $amount = Session::get('amount');
    $username = Session::get('username');
    $server = Session::get('server');

    // Check if a deposit record with the same session ID already exists
    $existingDeposit = DB::table('deposits')->where('sessionId', $sessionId)->first();

    if ($existingDeposit) {
        // If a record already exists, return a response to avoid duplicate insertions
        return view('thank-you')->with('message', 'Your deposit has already been processed. Thank you for your payment!');
    }

    // Insert a new deposit record if it doesn't already exist
    DB::table('deposits')->insert([
        'status' => 'Paid',
        'payment_method' => 'Stripe',
        'amount' => $amount,
        'username' => $username,
        'server' => $server,
        'sessionId' => $sessionId,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // Logic for successful deposit (e.g., notify the user, update records)
    return view('thank-you')->with('message', 'Your deposit was processed successfully. Thank you for your payment!');
}
public function depositStripeCancel()
    {
        // Logic for cancelled deposit
        return view('deposit'); // You can create a cancel.blade.php view to inform the user
    }
public function squareProcessDeposit(Request $request, $id)
    {
        // Get the JSON input and decode it
        $params = $request->json()->all();

        // Validate if 'amount', 'token', and 'payment_method' are provided
        if (empty($params['amount']) || empty($params['token']) || empty($params['payment_method'])) {
            return response()->json(['error' => 'Missing amount, token, or payment method'], 422);
        }

        try {
            // Prepare the request body
            $amount_money = [
                'amount' => (int)($params['amount'] * 100), // Convert to cents
                'currency' => 'USD' // Currency code
            ];

            // Get Square Access Token and Location ID from .env
            $accessToken = env('SQUARE_ACCESS_TOKEN'); // Your Square access token from .env
            $locationId = env('SQUARE_LOCATION_ID'); // Your Square location ID from .env

            // Build the payment request payload
            $body = [
                'source_id' => $params['token'], // Token generated from the frontend
                'amount_money' => $amount_money, // Amount details
                'location_id' => $locationId, // Square location ID
                'idempotency_key' => uniqid() // Unique key to prevent duplicate payments
            ];

            // Send the request to Square's API
            $response = Http::withToken($accessToken)
    ->post('https://connect.squareup.com/v2/payments', $body);

            // Check if the request was successful
            if ($response->successful()) {
            $depositId = DB::table('deposits')->insertGetId([
    'status' => 'UnPaid',
    'payment_method' => 'Square',
    'amount' => $params['amount'],
    'username' => $params['username'],
    'server' => $params['server'],
    'created_at' => now(), // Set the created_at timestamp if the table has this column
    'updated_at' => now()  // Set the updated_at timestamp if the table has this column
]);
                $paymentData = $response->json();

                // Check if the payment status is 'COMPLETED'
                if ($paymentData['payment']['status'] === 'COMPLETED') {
                    DB::table('deposits')->where('id', $depositId)->update([
                        'status' => 'Paid' ]);
                    // Update the deposit status in the database
                    return response()->json(['success' => 'Payment Completed','status' => $paymentData['payment']['status']], 500);

                } else {
                    return response()->json(['error' => 'Payment not completed', 'status' => $paymentData['payment']['status']], 500);
                }
            } else {
                // Handle error from Square API
                return response()->json(['error' => 'Square API Error', 'details' => $response->json()], 500);
            }

        } catch (\Exception $e) {
            // Catch any other exceptions and return a failure response
            return response()->json(['error' => 'Exception', 'message' => $e->getMessage()], 500);
        }
    }

public function makePayment(Request $request)
{
    $staffId = session('staff_id');
    // Get the inputs from the request
    $amount = $request->input('amount') + '0.9';
    $recipientEmail = $request->input('email');
    $description = $request->input('description');
    $username = $request->input('username');
    $name = $request->input('name');
   $email = $recipientEmail;
$address = $request->input('line_1');
    // Check for deposit type and handle accordingly
    if ($request->input('deposit_type') == 'mail_deposit') {
        //  $account = '13287e3a58e34ea9bd75717d20ac237a'; // Wallet Address Hardcoded for now (sandbox)
            $account = '5ce693db390a46f5a1c679c2e39ea696'; // Wallet Address Hardcoded for now (Production)
    $depositOption = $request->input('deposit_option');
            // Call the Checkbook service to send the digital check for mail deposit
            $result = $this->checkbookService->sendDigitalCheck($username, $amount, $recipientEmail, $account, $description);
            // Check if the response is successful and contains the necessary data
            if (isset($result['id'])) {
                // Insert payment details into the database
                $depositId = DB::table('withdrawals')->insertGetId([
                    'userid' => $staffId,
                    'status' => 'Unpaid',
                    'payment_method' => 'Cheque',
                    'amount' => $amount,
                    'username' => $username,
                    'server' => $request->input('server'),
                    'game_username' => $request->input('gameusername'),
                    'created_at' => now(), // Set the created_at timestamp
                    'updated_at' => now()  // Set the updated_at timestamp
                ]);

                // Return the redirect URL after successful payment
                return response()->json([
                    'redirect_url' => route('payment.result', ['deposit_id' => $depositId]), // pass deposit ID if needed
                ]);
            } else {
                // Handle errors or unsuccessful response from Checkbook.io API
                return response()->json([
                    'error' => 'Error sending digital check. Please try again later.',
                    'details' => $result,
                ], 500);
            }
    } else {
            $deposit_option = $request->input('deposit_option');
        $useraccount = DB::table('user_account')->where('userid',$staffId)->where('payment_method',$deposit_option)->first();
        $existingUser = DB::table('checkbook_users')->where('userid', $staffId)->first();
    // Set the values as if $createuser was populated
    $createuser = [
        'key' => $existingUser->api_key,
        'secret' => $existingUser->api_secret_key,
        'user_id' => $existingUser->user_id,
        'userid' => $existingUser->userid
    ];
     $api_key = $createuser['key'];
    $api_secret = $createuser['secret'];
    $checkbook_user_id = $createuser['user_id'];
    $cardNumber = null; $cvv = null; $expirationDate = null; $api_id = null;
          if($deposit_option == 'ZELLE_new'){
              dd($email,$api_key,$api_secret);
        $zelle = $this->checkbookService->createZelleAccount($email, $api_key, $api_secret);
        $api_id = $zelle['id'];
    } else if($deposit_option == 'CARD_new'){
        $cardNumber = $request->input('card_number');
        $cvv = $request->input('cvv');
        $expirationDate = $request->input('expiration_date');
        $cardAcc = $this->checkbookService->createCardAccount($address, $cardNumber, $cvv, $expirationDate, $api_key, $api_secret);
        $api_id = $cardAcc['id'];
    } else if($deposit_option == 'VCC_new'){
        $vcc = $this->checkbookService->createVCCAccount($email, $api_key, $api_secret);
        $api_id = $vcc['id'];
        $cardNumber = $vcc['card_number'] ?? null;
        $cvv = $vcc['cvv'] ?? null;
        $expirationDate = $vcc['expiration_date'] ?? null;
    }

if(!isset($useraccount)){
    // Insert user's payment account into the database
   $useraccount =  DB::table('user_account')->insert([
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
}
    if($useraccount == ''){
        $useraccount = DB::table('useraccount')->where('user_id',$staffId)->orderBy('created_at', 'id')->first();
         $api_id = $request->input('api_id'); // Hardcoded for now
         $api_key = $request->input('api_key');
         $api_secret = $request->input('api_secret');
         $user_id = $request->input('user_id');
         $depositOption = $request->input('deposit_option');
         $user = $this->checkbookService->getUserByUserId($username);
            // Check if the user creation was successful
            if (isset($user['error'])) {
                return response()->json(['error' => 'Unable to create or retrieve user', 'details' => $user]);
            }
            // Check if the response is successful and contains the necessary data
            if (isset($user)) {
                // Insert payment details into the database
                $depositId = DB::table('withdrawals')->insertGetId([
                    'userid' => $staffId,
                    'status' => 'Unpaid',
                    'payment_method' => 'Individual Deposit',
                    'amount' => $amount,
                    'username' => $username,
                    'server' => $request->input('server'),
                    'game_username' => $request->input('gameusername'),
                    'checkbook_type' => $depositOption,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                // Return the redirect URL after successful payment
                return response()->json([
                    'redirect_url' => route('payment.result', ['deposit_id' => $depositId]), // pass deposit ID if needed
                ]);
            }
            } else {
         $api_id = $request->input('api_id'); // Hardcoded for now
         $api_key = $request->input('api_key');
         $api_secret = $request->input('api_secret');
         $user_id = $request->input('user_id');
         $depositOption = $request->input('deposit_option');
         $user = $this->checkbookService->getUserByUserId($username);
            // Check if the user creation was successful
            if (isset($user['error'])) {
                return response()->json(['error' => 'Unable to create or retrieve user', 'details' => $user]);
            }
            // Check if the response is successful and contains the necessary data
            if (isset($user)) {
                // Insert payment details into the database
                $depositId = DB::table('withdrawals')->insertGetId([
                    'userid' => $staffId,
                    'status' => 'Unpaid',
                    'payment_method' => 'Individual Deposit',
                    'amount' => $amount,
                    'username' => $username,
                    'server' => $request->input('server'),
                    'game_username' => $request->input('gameusername'),
                    'checkbook_type' => $depositOption,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                // Return the redirect URL after successful payment
                return response()->json([
                    'redirect_url' => route('payment.result', ['deposit_id' => $depositId]), // pass deposit ID if needed
                ]);
            }
            }
    }
}
public function updateStatus(Request $request, $id)
{
    $staffId =$id;
    // Find the deposit record by ID
    $userwithdrawal = DB::table('withdrawals')->where('id',$staffId)->first();
    $userid = $userwithdrawal->userid;
    $name = $userwithdrawal->username;
    $amount = $userwithdrawal->amount;
    $depositOption = $userwithdrawal->checkbook_type;
    $useraccount = DB::table('user_account')->where('payment_method',$depositOption)->where('userid',$userid)->first();
    $checkbookuser = DB::table('checkbook_users')->where('userid',$userid)->first();
    $recipientEmail = $useraccount->email;
    $description = $userwithdrawal->username;
    $api_id = $useraccount->api_id;
    $api_key = $checkbookuser->api_key;
    $api_secret = $checkbookuser->api_secret_key;
    $user_id = $useraccount->user_id;
    $result = $this->checkbookService->individualDeposit($name, $amount, $recipientEmail, $description, $depositOption,$api_id,$api_key,$api_secret,$user_id);
    // Update the status to 'Paid'
    DB::table('withdrawals')
    ->where('userid', $userid)
    ->where('checkbook_type', $depositOption)
    ->update([
        'status' => 'Paid',
    ]);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Status updated to Paid.');
}
public function rejectStatus($id)
{
    $staffId = $id;
    // Find the deposit record by ID
    $userwithdrawal = DB::table('withdrawals')->where('id',$staffId)->first();
    $userid = $userwithdrawal->userid;
    $name = $userwithdrawal->username;
    $amount = $userwithdrawal->amount;
    $depositOption = $userwithdrawal->checkbook_type;
    $useraccount = DB::table('user_account')->where('payment_method',$depositOption)->where('userid',$userid)->first();
    $checkbookuser = DB::table('checkbook_users')->where('userid',$userid)->first();
    $recipientEmail = $useraccount->email;
    $description = $userwithdrawal->username;
    $api_id = $useraccount->api_id;
    $api_key = $checkbookuser->api_key;
    $api_secret = $checkbookuser->api_secret_key;
    $user_id = $useraccount->user_id;
    // Update the status to 'Paid'
    DB::table('withdrawals')
    ->where('userid', $userid)
    ->where('checkbook_type', $depositOption)
    ->update([
        'status' => 'Reject',
    ]);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Status updated to Paid.');
}


private function generateUniqueUserId($name)
    {
    // Generate a 3-digit random number
    $randomNumbers = rand(100, 999);

    // Concatenate the name with the random number
    $userId = $name . $randomNumbers;
    return $userId;
}
public function paymentResult()
    {
    return view('payment-result');
}

}
