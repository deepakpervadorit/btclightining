<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class thankyouController extends Controller{
        public function thankyou(Request $request) {
            if($request->resultDescription == "Transaction succeeded")
            {
                    $tansactionID = $request->merchantTransactionId;
                    
                    // Retrieve the deposit transaction details
                    $deposit_transaction = DB::table('deposit_transactions')
                        ->where('transaction_id', $tansactionID)
                        ->orderBy('id', 'desc') // Get the latest row for the transaction_id
                        ->first(); // Retrieve the first record as an object
                    
                    if ($deposit_transaction) {
                        $userId = $deposit_transaction->user_id;
                        $amount = $deposit_transaction->amount;
                        $currency_code = $request->currency;
                    
                      $data = [
        'paymentId' => $request->paymentId,
        'status' => $request->status,
        'transactionStatus' => $request->transactionStatus,
        'paymentBrand' => $request->paymentBrand,
        'paymentMode' => $request->paymentMode,
        'firstName' => $request->firstName,
        'lastName' => $request->lastName,
        'amount' => $request->amount,
        'currency' => $request->currency,
        'descriptor' => $request->descriptor,
        'merchantTransactionId' => $request->merchantTransactionId,
        'remark' => $request->remark,
        'tmpl_amount' => $request->tmpl_amount,
        'tmpl_currency' => $request->tmpl_currency,
        'checksum' => $request->checksum,
        'card' => json_encode($request->card),  // Make sure the 'card' is encoded into JSON
        'eci' => $request->eci,
        'bankReferenceId' => $request->bankReferenceId,
        'terminalId' => $request->terminalId,
        'updated_at' => now(),  // Optional: if you want to manually set updated_at timestamp
    ];

    // Update the record in 'deposit_transactions' where 'user_id' and 'transaction_id' match
    $updated = DB::table('deposit_transactions')
        ->where('user_id', $deposit_transaction->user_id)
        ->where('transaction_id', $deposit_transaction->transaction_id)
        ->update($data);

                
                // $depositTransaction = new DepositTransaction();
                // $depositTransaction->transaction_id = $request->merchantTransactionId;
                // // $depositTransaction->user_id = auth()->user()->id;
                // $depositTransaction->save();
            return view('thank-you', compact('amount','currency_code'));
            }
            }
            else
            {
                return view('errors.errorPayment');
            }
             // This points to 'resources/views/errors/thankyou.blade.php'
        }



}