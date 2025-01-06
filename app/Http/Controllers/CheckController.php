<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CheckbookService;

class CheckController extends Controller
{
   protected $checkbookService;

    public function __construct(CheckbookService $checkbookService)
    {
        $this->checkbookService = $checkbookService;
    }
     public function showForm(Request $request)
    {
        $server = $request->input('server');
        $paymentMethod = $request->input('payment_method');
        $username = $request->input('username');
        $amount = $request->input('amount');
        return view('admin.send_check',compact('server','paymentMethod','username','amount'));  // This will render the form view
    }


    public function sendCheck(Request $request)
    {
        $amount = $request->input('amount'); // Amount to charge
        $recipientEmail = $request->input('recipient_email'); // Recipient's email
        $description = $request->input('description'); // Description of the payment

        $result = $this->checkbookService->createPayment($amount, $recipientEmail, $description);

        if ($result['success']) {
            return response()->json(['message' => 'Payment successful!', 'data' => $result]);
        } else {
            return response()->json(['message' => 'Payment failed!', 'error' => $result], 400);
        }
    }
}
