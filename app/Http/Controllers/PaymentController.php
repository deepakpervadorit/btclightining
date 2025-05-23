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
use App\Models\User;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Mail\WithdrawMail;
use App\Mail\DepositMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Events\PaymentSuccess;


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
        $merchant_id = DB::table('users')->where('id',$userId)->pluck('created_by');
        $merchant_game = DB::table('store_details')->where('store_id',$merchant_id)->value('game_providers');
        $merchant_game = explode(',',$merchant_game);
        $merchant_details = DB::table('store_details')->where('store_id',$merchant_id)->first();
        $speed_scret_key = env('SPEED_SECRET_KEY');

        return view('deposit',compact('games','userId','merchant_details','merchant_game','speed_scret_key'));
    }

    public function showMerchantDepositForm($merchantid)
    {
        $games = DB::table('games')->get();
        $merchant_id = DB::table('staff')->where('merchant_id', $merchantid)->value('id');
            $userId = $merchant_id;
        // $merchant_id = $userId;
        $merchant_game = DB::table('store_details')->where('store_id',$merchant_id)->value('game_providers');
        $merchant_game = explode(',',$merchant_game);
        $merchant_details = DB::table('store_details')->where('store_id',$merchant_id)->first();
        $speed_scret_key = env('SPEED_SECRET_KEY');

        return view('merchant-deposit',compact('games','userId','merchant_details','merchant_game','speed_scret_key','merchantid'));
    }

    public function showMerchantDepositstep2($merchantid,Request $request)
    {
        $redirect_url = $request->redirect_url;
        
        // $games = DB::table('games')->get();
        // $merchant_id = DB::table('staff')->where('merchant_id', $merchantid)->value('id');
        //     $userId = $merchant_id;
        // // $merchant_id = $userId;
        // $merchant_game = DB::table('store_details')->where('store_id',$merchant_id)->value('game_providers');
        // $merchant_game = explode(',',$merchant_game);
        // $merchant_details = DB::table('store_details')->where('store_id',$merchant_id)->first();
        // $speed_scret_key = env('SPEED_SECRET_KEY');
        if($redirect_url)
        {
            return view('merchant-deposit-step2',compact('merchantid','redirect_url'));
        }
        else
        {
            return view('merchant-deposit-step2',compact('merchantid'));
        }
    }

    public function showMerchantDepositstep3($merchantid,Request $request)
    {
        // $games = DB::table('games')->get();
        $merchant_id = DB::table('staff')->where('merchant_id', $merchantid)->value('id');
        $userId = $merchant_id;
        $gameid = $request->gameid;
        
        $redirect_url = $request->redirect_url;
        
        // // $merchant_id = $userId;
        // $merchant_game = DB::table('store_details')->where('store_id',$merchant_id)->value('game_providers');
        // $merchant_game = explode(',',$merchant_game);
        // $merchant_details = DB::table('store_details')->where('store_id',$merchant_id)->first();
        $speed_scret_key = env('SPEED_SECRET_KEY');
        if($redirect_url)
        {
            return view('merchant-deposit-step3',compact('userId','speed_scret_key','gameid','redirect_url'));
        }
        else
        {
            return view('merchant-deposit-step3',compact('userId','speed_scret_key','gameid'));
        }
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

    public function showMerchantInvoice($id)
    {
        $invoice = DB::table('invoice_qrcode')->where('id',$id)->first();
        // $svgDataUrl = $request->input('svgDataUrl');
        // $checkoutUrl = $request->input('checkoutUrl');
        // $id = $request->input('id');
        // $invoiceid = $request->input('invoiceid');
        return view('merchant-invoice', compact('invoice'));
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

    public function MerchantgenerateInvoiceQr(Request $request)
    {
        // Assuming you have the Lightning payment request in the response from the external API
        $paymentRequest = $request->input('payment_request'); // This would be passed from your front-end via AJAX or from the API
        $expires_at = $request->input('expires_at');
        $invoiceid = $request->input('invoiceid');
        $user_id = $request->input('user_id');
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
            'merchant_id' => $user_id,
        ]);
        return $invoice;
    }

    public function checkPaymentStatus(Request $request)
    {
        $invoiceId = $request->input('invoice_id');
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('SPEED_SECRET_KEY')),
        ])->get('https://api.tryspeed.com/payments/' . $invoiceId);
         // Log the incoming data
        Log::info('Payment-check start', ['data' => $response->json()]);
        if ($response->ok()) {
            $paymentData = $response->json();

            if ($paymentData['status'] === 'paid') {
                Log::info('Payment-check success', ['data' => $paymentData]);
                DB::table('deposit_transactions')->where('transaction_id',$invoiceId)->update([
                    'status' => 'Completed'
                ]);
            return response()->json(['status' => "completed"]);
            }
        }
        return response()->json(['status' => $response->json('payment_status')]);
    }

    public function handleWebhook(Request $request)
    {
        // Decode the JSON payload to an associative array
        $data = $request->json()->all();

        // Log the incoming data
        Log::info('Paymenthook received', ['data' => $data]);

        if (!isset($data['data']['object'])) {
            Log::error('Invalid data format received in paymenthook', ['data' => $data]);
            return response()->json(['status' => 'error', 'message' => 'Invalid data format'], 400);
        }

        // Extract the relevant data from the received JSON
        $eventData = $data['data']['object'];

        // Log successful data extraction
        Log::info('Data successfully extracted from paymenthook', ['eventData' => $eventData]);

        DB::table('deposit_transactions')->where('transaction_id',$eventData['id'])->update([
            'tmpl_amount' => $eventData['target_amount'],
            'tmpl_currency' => $eventData['target_currency'],
            'transactionStatus' => $eventData['status'],
            ]);

         if ($eventData['status'] === 'paid') {
            // Broadcast event
            event(new PaymentSuccess('Payment received successfully! Redirecting...'));
        }
        // Insert into the database using Eloquent
        // $paymentEvent = PaymentEvent::create([
        //     'object' => $data['object'],
        //     'livemode' => $data['livemode'],
        //     'event_type' => $data['event_type'],
        //     'checkout_session_id' => $eventData['checkout_session_id'],
        //     'payment_id' => $eventData['payment_id'],
        //     'target_amount' => $eventData['target_amount'],
        //     'amount_paid' => $eventData['amount_paid'],
        //     'exchange_rate' => $eventData['exchange_rate'],
        //     'expires_at' => $eventData['expires_at'],
        //     'payment_status' => $eventData['payment_status'],
        //     'target_amount_paid' => $eventData['target_amount_paid'],
        //     'target_currency' => $eventData['target_currency'],
        // ]);

        // Log database insert
        // Log::info('Payment event created in the database', ['paymentEvent' => $paymentEvent]);

        return response()->json($eventData);
    }


    public function updateDepositTransaction($transactionId) {
        DB::table('deposit_transactions')->where('transaction_id',$transactionId)->update([
            'status' => 'Completed'
        ]);
        $updatedRow = DB::table('deposit_transactions')
        ->where('transaction_id', $transactionId)
        ->first();

        $deposit = Deposit::create([
            'server' => $updatedRow->server,
            'game_username' => $updatedRow->game_username,
            'session_id' => $updatedRow->transaction_id,
            'user_id' => $updatedRow->user_id,
            'amount' => $updatedRow->amount,
            'status' => $updatedRow->status,
            'payment_method' => $updatedRow->paymentGateway,
            'currency' => $updatedRow->currency,
        ]);
        $member = DB::table('users')->where('id', $updatedRow->user_id)->first();

        Mail::to($member->email)->send(new DepositMail($member,$updatedRow->amount));

        if ($deposit) {
            return redirect('/dashboard');
        }

    }

    public function updateMerchantDepositTransaction($transactionId) {
        DB::table('deposit_transactions')->where('transaction_id',$transactionId)->update([
            'status' => 'Completed'
        ]);
        $updatedRow = DB::table('deposit_transactions')
        ->where('transaction_id', $transactionId)
        ->first();

        $deposit = Deposit::create([
            'server' => $updatedRow->server,
            'game_username' => $updatedRow->game_username,
            'game_id' => $updatedRow->game_id,
            'session_id' => $updatedRow->transaction_id,
            'merchant_id' => $updatedRow->merchant_id,
            'amount' => $updatedRow->amount,
            'status' => $updatedRow->status,
            'payment_method' => $updatedRow->paymentGateway,
            'currency' => $updatedRow->currency,
        ]);
            $member = DB::table('staff')->where('id', $updatedRow->merchant_id)->first();
            Mail::to($member->email)->send(new DepositMail($member,$updatedRow->amount));

        if ($deposit) {
            return redirect('/');
        }

    }
    public function withdrawalForm()
    {
        $userId = session('staff_id');
        $usddeposit = DB::table('deposits')->where('user_id',$userId)->where('currency','USD')->where('status','Completed')->sum('amount');
        $eurdeposit = DB::table('deposits')->where('user_id',$userId)->where('currency','EUR')->where('status','Y')->sum('amount');
        $usdwithdrawal = DB::table('withdrawals')->where('userid',$userId)->where('currency','USD')->where('status','Paid')->sum('amount');
        $eurwithdrawal = DB::table('withdrawals')->where('userid',$userId)->where('currency','EUR')->where('status','Paid')->sum('amount');
        if ($usddeposit > $usdwithdrawal) {
            $usddeposit = round($usddeposit - $usdwithdrawal, 2);
        } else {
            $usddeposit = round($usdwithdrawal - $usddeposit, 2);
        }
        if ($eurdeposit > $eurwithdrawal) {
            $eurdeposit = round($eurdeposit - $eurwithdrawal, 2);
        } else {
            $eurdeposit = round($eurwithdrawal - $eurdeposit, 2);
        }
        $merchant_id = DB::table('users')->where('id',$userId)->pluck('created_by');
        $merchant_game = DB::table('store_details')->where('store_id',$merchant_id)->value('game_providers');
        $merchant_game = explode(',',$merchant_game);
        $games = DB::table('games')->get();
        $uniqueId = Str::random(10);

        $merchant_details = DB::table('store_details')->where('store_id',$merchant_id)->first();
        return view('withdrawal',compact('games','uniqueId','usddeposit','eurdeposit','merchant_details','merchant_game'));
    }

    public function MerchantwithdrawalForm($merchantid)
    {
        $merchant_id = DB::table('staff')->where('merchant_id', $merchantid)->value('id');
        $merchant_game = DB::table('store_details')->where('store_id',$merchant_id)->value('game_providers');
        $merchant_game = explode(',',$merchant_game);
        $games = DB::table('games')->get();
        $uniqueId = Str::random(10);

        $merchant_details = DB::table('store_details')->where('store_id',$merchant_id)->first();
        return view('merchant-withdrawal',compact('games','uniqueId','merchant_details','merchant_game','merchant_id'));
    }

    public function withdraw(Request $request)
    {

        // Check if the user exists
        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
        // Proceed to insert the withdrawal request
        try {
            $depositId = DB::table('withdrawals')->insert([
                'userid' => $user->id,
                'status' => 'Unpaid',
                'payment_method' => $request->payment_method,
                'currency' => $request->currency,
                'amount' => $request->amount,
                'username' => $request->username,
                'email' => $request->email,
                'server' => $request->gameserver,
                'game_username' => $request->gameusername,
                'invoice' => $request->invoice,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your payment request will be approved by admin.'
            ]);

        } catch (\Exception $e) {
            // Log the exception and return a response

            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ]);
        }
    }


    public function merchantwithdraw(Request $request)
    {

        // Check if the user exists
        $user = DB::table('staff')->where('merchant_id', $request->merchant_id)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
        // Proceed to insert the withdrawal request
        try {
            $depositId = DB::table('withdrawals')->insert([
                'merchant_id' => $user->id,
                'status' => 'Unpaid',
                'payment_method' => $request->payment_method,
                'currency' => $request->currency,
                'amount' => $request->amount,
                'username' => $user->name,
                'email' => $user->email,
                'game_id' => $request->gameid,
                'invoice' => $request->invoice,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your payment request will be approved by admin.'
            ]);

        } catch (\Exception $e) {
            // Log the exception and return a response

            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ]);
        }
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
    try {
        $staffId = session('staff_id');
        // Get the inputs from the request
        // $amount = $request->input('amount') + '0.9';
        $amount = $request->input('amount');
        $recipientEmail = $request->input('email');
        $description = $request->input('description');
        $username = $request->input('username');
        $name = $request->input('name');
        $email = $recipientEmail;
        $address = $request->input('line_1');

        // Check for deposit type and handle accordingly
        if ($request->input('deposit_type') == 'mail_deposit') {
            $account = '5ce693db390a46f5a1c679c2e39ea696'; // Wallet Address Hardcoded for now (Production)
            $depositOption = $request->input('deposit_option');

            // Call the Checkbook service to send the digital check for mail deposit
            $result = $this->checkbookService->sendDigitalCheck($username, $amount, $recipientEmail, $account, $description);

            if (isset($result['id'])) {
                // Insert payment details into the database
                $depositId = DB::table('withdrawals')->insertGetId([
                    'userid' => $staffId,
                    'status' => 'Unpaid',
                    'payment_method' => 'Cheque',
                    'amount' => $amount,
                    'username' => $username,
                    'email' => $email,
                    'server' => implode(',',$request->input('server')),
                    'game_username' => $request->input('gameusername'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                return response()->json([
                    'redirect_url' => route('payment.result', ['deposit_id' => $depositId]),
                ]);
            } else {
                return response()->json([
                    'error' => 'Error sending digital check. Please try again later.',
                    'details' => $result,
                ], 500);
            }
        } else {
            $depositOption = $request->input('deposit_option');
            $userAccount = DB::table('user_account')->where('userid', $staffId)->where('payment_method', $depositOption)->first();
            $existingUser = DB::table('checkbook_users')->where('userid', $staffId)->first();

            if (!$existingUser) {
                throw new \Exception('User account not found.');
            }

            $createuser = [
                'key' => $existingUser->api_key,
                'secret' => $existingUser->api_secret_key,
                'user_id' => $existingUser->user_id,
                'userid' => $existingUser->userid
            ];

            $api_key = $createuser['key'];
            $api_secret = $createuser['secret'];
            $checkbook_user_id = $createuser['user_id'];
            $api_id = null;

            if ($depositOption == 'ZELLE_new') {
                $zelle = $this->checkbookService->createZelleAccount($email, $api_key, $api_secret);
                $api_id = $zelle['id'];
            } elseif ($depositOption == 'CARD_new') {
                $cardNumber = $request->input('card_number');
                $cvv = $request->input('cvv');
                $expirationDate = $request->input('expiration_date');
                $cardAcc = $this->checkbookService->createCardAccount($address, $cardNumber, $cvv, $expirationDate, $api_key, $api_secret);
                $api_id = $cardAcc['id'];
            } elseif ($depositOption == 'VCC_new') {
                $vcc = $this->checkbookService->createVCCAccount($email, $api_key, $api_secret);
                $api_id = $vcc['id'];
            }

            if (!$userAccount) {
                DB::table('user_account')->insert([
                    'userid' => $staffId,
                    'payment_method' => $depositOption,
                    'api_id' => $api_id,
                    'user_id' => $checkbook_user_id,
                    'email' => $request->input('email'),
                    'line_1' => $request->input('line_1'),
                    'city' => $request->input('city'),
                    'state' => $request->input('state'),
                    'country' => $request->input('country'),
                    'zip_code' => $request->input('zip'),
                    'card_number' => $cardNumber ?? null,
                    'cvv' => $cvv ?? null,
                    'expiration_date' => $expirationDate ?? null,
                    'created_at' => now(),
                ]);
            }

            $depositId = DB::table('withdrawals')->insertGetId([
                'userid' => $staffId,
                'status' => 'Unpaid',
                'payment_method' => 'Individual Deposit',
                'currency' => 'USD',
                'amount' => $amount,
                'username' => $username,
                'email' => $request->input('email'),
                'server' => $request->input('server'),
                'game_username' => $request->input('gameusername'),
                'checkbook_type' => $depositOption,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'redirect_url' => route('payment.result', ['deposit_id' => $depositId]),
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'An error occurred while processing the payment.',
            'details' => $e->getMessage(),
        ], 500);
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
    $description = $userwithdrawal->username;
    if($userwithdrawal->payment_method == "Try Speed")
    {
        $speedSecretKey = env('SPEED_SECRET_KEY');

        // Base64 encode the secret key
        $encodedKey = base64_encode($speedSecretKey);

        // Try Speed API URL
        $apiUrl = "https://api.tryspeed.com/send";  // Replace with the actual Try Speed API endpoint

        // Create a new Guzzle client instance
        $client = new Client();
        $headers = [
                'Authorization' => 'Basic ' . $encodedKey,
                'Content-Type' => 'application/json',
            ];
        $requestData = [
                "amount" => $amount,
                "currency" => "USD",
                "withdraw_method" => "lightning",
                "target_currency" => "SATS",
                "withdraw_request" => "".$userwithdrawal->invoice."",
            ];

            // Make the POST request using Guzzle
            $response = $client->post($apiUrl, [
                'headers' => $headers,
                'json' => $requestData
            ]);

            // Get the response body
            $responseBody = json_decode($response->getBody()->getContents(), true);
            $responseCode = $response->getStatusCode();
            DB::table('api_logs')->insert([
                'request_method' => 'POST',
                'request_url' => $apiUrl,
                'request_headers' => json_encode($headers),
                'request_body' => json_encode($requestData),
                'response_code' => $responseCode,
                'response_body' => json_encode($responseBody),
                'created_at' => now(),
            ]);
            // Check if the response indicates success (e.g., based on a "status" field)
            if (isset($responseBody['status']) && $responseBody['status'] == 'unpaid') {
                // If the payment was successfully processed, update the withdrawal status
                DB::table('withdrawals')
                    ->where('id', $id)
                    ->update([
                        'status' => 'Paid',
                    ]);
                $member = DB::table('users')->where('id', $userid)->first();
                Mail::to($member->email)->send(new WithdrawMail($member,$amount));
                return redirect()->back()->with('success', 'Payment Success');
                // Log success or handle response as needed
            } else {
                // Handle failure or log error
                return redirect()->back()->with('error', 'Payment request failed with Cashapp Crypto.');
            }
    }
    elseif($userwithdrawal->payment_method == "Fortune Finex")
    {
            $withdraw = DB::table('withdrawals')->where('id', $id)->first();
            $authToken = $this->getAuthToken();
            // Generate merchant transaction ID and checksum
            $merchantTransactionId = bin2hex(random_bytes(6));

            $checksum = $this->generateChecksum($withdraw, $merchantTransactionId);

            // Determine terminal ID based on currency
            $terminal = $this->getTerminalId($withdraw->currency);
            $header = [
                'AuthToken' => $authToken
                ];

            // Prepare request data
            $data = [
                'bankAccount.transferType' => 'cards',
                'authentication.checksum' => $checksum,
                'currency' => $withdraw->currency,
                'authentication.memberId' => env('MEMBER_ID'),
                'authentication.terminalId' => $terminal,
                'merchantTransactionId' => $merchantTransactionId,
                'amount' => $withdraw->amount,
                'cardNumber' => 4242424242424242,
                'cardExpMonth' => 12,
                'cardExpYear' => 2026,
                'cardHolderName' => 'test',
                'notificationUrl' => 'https://luckypay.app/payout-notify-payment',
            ];

            // Make the payout request
            $response = $this->makePayoutRequest($data, $authToken);
            $responseCode = $response->getStatusCode();
            DB::table('api_logs')->insert([
                'request_method' => 'POST',
                'request_url' => env('FORTUNEFINEX_BASE_URL').'transactionServices/REST/v1/payout', $data,
                'request_headers' => json_encode($header),
                'request_body' => json_encode($data),
                'response_code' => $responseCode,
                'response_body' => json_encode($response),
                'created_at' => now(),
            ]);
            // Check if the response indicates success (e.g., based on a "status" field)
            if (isset($response['status']) && $response['status'] == 'P') {
                // If the payment was successfully processed, update the withdrawal status
                DB::table('withdrawals')
                    ->where('id', $id)
                    ->update([
                        'status' => 'Paid',
                    ]);
                $member = DB::table('users')->where('id', $userid)->first();
                Mail::to($member->email)->send(new WithdrawMail($member,$amount));
                return redirect()->back()->with('success', 'Payment Success');
                // Log success or handle response as needed
            } else {
                // Handle failure or log error
                return redirect()->back()->with('error', 'Payment request failed with Fortune Finex.');
            }
    }
    else
    {
        $depositOption = $userwithdrawal->checkbook_type;
        $useraccount = DB::table('user_account')->where('payment_method',$depositOption)->where('userid',$userid)->first();
        if($depositOption == "ZELLE")
        {
            // $user = DB::table('users')->where('id',$staffId)->first();
           /* $useraccount = DB::table('user_account')->where('payment_method','ZELLE')->where('email',$useraccount->email)->first();
            if(!$useraccount)
            {
                $user = User::where('email',$userwithdrawal->email)->first();
                // if(!$user)
                // {
                //     $user = User::create([
                //         'name' => $userwithdrawal->game_username,
                //         'email' => $userwithdrawal->email,
                //         'password' => Hash::make("Kuchnahi021!"), // Hash the password before saving
                //         'role' => 'User',
                //         'created_by' => $userid,
                //     ]);


                //     DB::table('role_staff')->insert([
                //         'staff_id' => $user->id,
                //         'role_id' => '5',
                //         'created_at' => now(),
                //         'updated_at' => now(),
                //     ]);
                // }
                $createuser = $this->checkbookService->createUser($user->name);
                // dd($createuser);
                if(!isset($createuser['user_id']))
                {
                    // DB::table('role_staff')->where('staff_id',$user->id)->delete();
                    // DB::table('users')->where('id',$user->id)->delete();
                    return redirect()->back()->with('error', 'Zelle account already created with this username try with different one.');
                }
                DB::table('checkbook_users')->insert([
                    'userid' => $user->id,
                    'user_id' => $createuser['user_id'],
                    'checkbook_id' => $createuser['id'],
                    'api_key' => $createuser['key'],
                    'api_secret_key' => $createuser['secret'],
                    'created_at' => now(),
                ]);
                $zelle = $this->checkbookService->createZelleAccount($user->email, $createuser['key'], $createuser['secret']);
                $api_id = $zelle['id'];
                    DB::table('user_account')->insert([
                    'userid' => $user->id,
                    'payment_method' => $depositOption,
                    'api_id' => $api_id,
                    'user_id' => $createuser['user_id'],
                    'email' => $userwithdrawal->email,
                    'created_at' => now(),
                ]);

                $api_id = $api_id;
                $api_key = $createuser['key'];
                $api_secret = $createuser['secret'];
                $user_id = $createuser['user_id'];
                $result = $this->checkbookService->individualDeposit($name, $amount, $userwithdrawal->email, $description, $depositOption,$api_id,$api_key,$api_secret,$user_id);
                dd($result);
                exit;
                if(!isset($result['error']))
                {
                        // Update the status to 'Paid'
                        DB::table('withdrawals')
                        ->where('id',$staffId)
                        ->where('userid', $userid)
                        ->where('checkbook_type', $depositOption)
                        ->update([
                            'status' => 'Paid',
                        ]);
                }

            }*/
        }
        $checkbookuser = DB::table('checkbook_users')->where('userid',$userid)->first();
        $recipientEmail = $useraccount->email;

        $api_id = $useraccount->api_id;
        $api_key = $checkbookuser->api_key;
        $api_secret = $checkbookuser->api_secret_key;
        $user_id = $useraccount->user_id;
        $authHeader = $api_key . ':' . $api_secret;
        $header = [
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            ];
        $requestData = [
            'deposit_options' => [$depositOption],
            'amount' => $amount,
            'recipient' => $user_id,
            'name' => $user_id,
            'account' => '5ce693db390a46f5a1c679c2e39ea696',
            'description' => $description,
        ];
        $result = $this->checkbookService->individualDeposit($name, $amount, $recipientEmail, $description, $depositOption,$api_id,$api_key,$api_secret,$user_id);

        DB::table('api_logs')->insert([
                'request_method' => 'POST',
                'request_url' => "checkbook API",
                'request_headers' => json_encode($header),
                'request_body' => json_encode($requestData),
                'response_body' => json_encode($result),
                'created_at' => now(),
            ]);

        if(!isset($result['error']))
        {
            // Update the status to 'Paid'
            DB::table('withdrawals')
            ->where('id',$staffId)
            ->where('userid', $userid)
            ->where('checkbook_type', $depositOption)
            ->update([
                'status' => 'Paid',
            ]);
        }

    }
    $member = DB::table('users')->where('id', $userid)->first();
    Mail::to($member->email)->send(new WithdrawMail($member,$amount));
    // Redirect back with a success message
    return redirect()->back()->with('success', 'Status updated to Paid.');
}

public function MerchantupdateStatus(Request $request, $id)
{
    $staffId =$id;
    // Find the deposit record by ID
    $userwithdrawal = DB::table('withdrawals')->where('id',$staffId)->first();
    $userid = $userwithdrawal->merchant_id;
    $name = $userwithdrawal->username;
    $amount = $userwithdrawal->amount;
    $description = $userwithdrawal->username;
    if($userwithdrawal->payment_method == "Try Speed")
    {
        $speedSecretKey = env('SPEED_SECRET_KEY');

        // Base64 encode the secret key
        $encodedKey = base64_encode($speedSecretKey);

        // Try Speed API URL
        $apiUrl = "https://api.tryspeed.com/send";  // Replace with the actual Try Speed API endpoint

        // Create a new Guzzle client instance
        $client = new Client();
        $headers = [
                'Authorization' => 'Basic ' . $encodedKey,
                'Content-Type' => 'application/json',
            ];
        $requestData = [
                "amount" => $amount,
                "currency" => "USD",
                "withdraw_method" => "lightning",
                "target_currency" => "SATS",
                "withdraw_request" => "".$userwithdrawal->invoice."",
            ];

            // Make the POST request using Guzzle
            try {
                $response = $client->post($apiUrl, [
                    'headers' => $headers,
                    'json' => $requestData
                ]);
            
                $responseBody = json_decode($response->getBody()->getContents(), true);
                $responseCode = $response->getStatusCode();
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                // This catches 400-level errors
                $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
                $responseCode = $e->getResponse()->getStatusCode();
            
                // Log to DB
                DB::table('api_logs')->insert([
                    'request_method' => 'POST',
                    'request_url' => $apiUrl,
                    'request_headers' => json_encode($headers),
                    'request_body' => json_encode($requestData),
                    'response_code' => $responseCode,
                    'response_body' => json_encode($responseBody),
                    'created_at' => now(),
                ]);
            
                // Return with specific error
                return redirect()->back()->with('error', $responseBody['errors'][0]['message'] ?? 'Try Speed request failed');
            } catch (\GuzzleHttp\Exception\ServerException $e) {
                // This catches 500-level errors
                $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
                $responseCode = $e->getResponse()->getStatusCode();
            
                DB::table('api_logs')->insert([
                    'request_method' => 'POST',
                    'request_url' => $apiUrl,
                    'request_headers' => json_encode($headers),
                    'request_body' => json_encode($requestData),
                    'response_code' => $responseCode,
                    'response_body' => json_encode($responseBody),
                    'created_at' => now(),
                ]);
            
                return redirect()->back()->with('error', 'Try Speed server error occurred.');
            } catch (\Exception $e) {
                // Catch all other exceptions
                DB::table('api_logs')->insert([
                    'request_method' => 'POST',
                    'request_url' => $apiUrl,
                    'request_headers' => json_encode($headers),
                    'request_body' => json_encode($requestData),
                    'response_code' => 0,
                    'response_body' => $e->getMessage(),
                    'created_at' => now(),
                ]);
            
                return redirect()->back()->with('error', 'Unexpected error: ' . $e->getMessage());
            }
            DB::table('api_logs')->insert([
                'request_method' => 'POST',
                'request_url' => $apiUrl,
                'request_headers' => json_encode($headers),
                'request_body' => json_encode($requestData),
                'response_code' => $responseCode,
                'response_body' => json_encode($responseBody),
                'created_at' => now(),
            ]);
            // Check if the response indicates success (e.g., based on a "status" field)
            if (isset($responseBody['status']) && $responseBody['status'] == 'unpaid') {
                // If the payment was successfully processed, update the withdrawal status
                DB::table('withdrawals')
                    ->where('id', $id)
                    ->update([
                        'status' => 'Paid',
                    ]);
                $member = DB::table('staff')->where('id', $userid)->first();
                Mail::to($member->email)->send(new WithdrawMail($member,$amount));
                return redirect()->back()->with('success', 'Payment Success');
                // Log success or handle response as needed
            } else {
                // Handle failure or log error
                return redirect()->back()->with('error', 'Payment request failed with Cashapp Crypto.');
            }
    }

    $member = DB::table('users')->where('id', $userid)->first();
    Mail::to($member->email)->send(new WithdrawMail($member,$amount));
    // Redirect back with a success message
    return redirect()->back()->with('success', 'Status updated to Paid.');
}


    // private function logApiResponse($userid, $apiUrl, $requestData, $responseData, $status = '')
    // {
    //     DB::table('api_logs')->insert([
    //         'user_id' => $userid,
    //         'api_url' => $apiUrl,
    //         'request_data' => $requestData,
    //         'response_data' => $responseData,
    //         'status' => $status,
    //         'created_at' => now(),
    //     ]);
    // }
    private function getAuthToken()
    {
        $response = Http::asForm()->post(env('FORTUNEFINEX_BASE_URL').'transactionServices/REST/v1/authToken', [
            'authentication.partnerId' => env('PARTNER_ID'),
            'merchant.username' => 'Testfortunefinex',
            'authentication.sKey' => env('SECRET_KEY'),
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to get AuthToken'], 500);
        }

        return $response->json()['AuthToken'];
    }

    private function generateChecksum($withdraw, $merchantTransactionId)
    {
        $values = implode('|', [
            env('MEMBER_ID'),
            $merchantTransactionId,
            $withdraw->amount,
            env('SECRET_KEY')
        ]);

        return md5($values);
    }

    private function getTerminalId($currency)
    {
        return $currency == 'EUR' ? '7609' : ($currency == 'USD' ? '7883' : null);
    }
    private function makePayoutRequest($data, $authToken)
    {
        $response = Http::withHeaders([
            'AuthToken' => $authToken
        ])->asForm()->post(env('FORTUNEFINEX_BASE_URL').'transactionServices/REST/v1/payout', $data);

        if ($response->failed()) {
            return response()->json(['error' => 'Payout request failed'], 500);
        }

        return $response->json();
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
    // $recipientEmail = $useraccount->email;
    // $description = $userwithdrawal->username;
    // $api_id = $useraccount->api_id;
    // $api_key = $checkbookuser->api_key;
    // $api_secret = $checkbookuser->api_secret_key;
    // $user_id = $useraccount->user_id;
    // Update the status to 'Paid'
    DB::table('withdrawals')
    ->where('id', $id)
    ->where('userid', $userid)
    ->where('checkbook_type', $depositOption)
    ->update([
        'status' => 'Reject',
    ]);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Status updated to Rejected.');
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
