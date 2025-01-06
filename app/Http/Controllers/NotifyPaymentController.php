<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Agent;
use App\Models\Currency;
use App\Models\DeviceToken;
use App\Models\Frontend;
use App\Models\Invoice;
use App\Models\Language;
use App\Models\Merchant;
use App\Models\Page;
use App\Models\Plugin;
use App\Models\QRcode;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\DepositTransaction;
use App\Models\PayoutTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;  // Import the Log facade
use Validator;
use Illuminate\Support\Facades\DB;

class NotifyPaymentController extends Controller{
    
        public function notify(Request $request)
        {
            try {
                $depositTransaction = DepositTransaction::where('transaction_id', $request->merchantTransactionId)->first();
    
                if (!$depositTransaction) {
                    // Log to the custom api_error_log channel if transaction is not found
                    Log::channel('api_error_log')->error('Transaction not found', [
                        'merchantTransactionId' => $request->merchantTransactionId
                    ]);
                    return response()->json([
                        'error' => 'Transaction not found',
                    ], 404);
                }
    
                // Process the deposit transaction
                $depositTransaction->paymentId = $request->paymentId;
                $depositTransaction->status = $request->status;
                $depositTransaction->transactionStatus = $request->transactionStatus;
                $depositTransaction->paymentBrand = $request->paymentBrand;
                $depositTransaction->paymentMode = $request->paymentMode;
                $depositTransaction->firstName = $request->firstName;
                $depositTransaction->lastName = $request->lastName;
                $depositTransaction->amount = $request->amount;
                $depositTransaction->currency = $request->currency;
                $depositTransaction->descriptor = $request->descriptor;
                $depositTransaction->merchantTransactionId = $request->merchantTransactionId;
                $depositTransaction->remark = $request->remark;
                $depositTransaction->tmpl_amount = $request->tmpl_amount;
                $depositTransaction->tmpl_currency = $request->tmpl_currency;
                $depositTransaction->checksum = $request->checksum;
                $depositTransaction->result_code = $request->result['code'];
                $depositTransaction->result_description = $request->result['description'];
                $depositTransaction->customer_email = $request->customer['email'];
                $depositTransaction->customer_id = $request->customer['id'];
                $depositTransaction->card = json_encode($request->card);
                $depositTransaction->timestamp = $request->timestamp;
                $depositTransaction->eci = $request->eci;
                $depositTransaction->bankReferenceId = $request->bankReferenceId;
                $depositTransaction->terminalId = $request->terminalId;
                $depositTransaction->update();
    
                return response()->json([
                    'message' => 'Transaction saved successfully',
                    'data' => $depositTransaction
                ], 200);
    
            } catch (Exception $e) {
                // Log to the custom api_error_log channel if an exception occurs
                Log::channel('api_error_log')->error('Transaction processing failed', [
                    'message' => $e->getMessage(),
                    'stack' => $e->getTraceAsString(),
                    'request' => $request->all(), // Optionally log the request data (be careful with sensitive data)
                ]);
                return response()->json([
                    'error' => 'An error occurred while processing the transaction',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
        
        
        public function PayoutNotify(Request $request)
        {
            try {
                $payoutTransaction = PayoutTransaction::where('transaction_id', $request->merchantTransactionId)->first();
    
                if (!$payoutTransaction) {
                    // Log to the custom api_error_log channel if transaction is not found
                    Log::channel('api_error_log')->error('Transaction not found', [
                        'merchantTransactionId' => $request->merchantTransactionId
                    ]);
                    return response()->json([
                        'error' => 'Transaction not found',
                    ], 404);
                }
    
                // Process the deposit transaction
                $payoutTransaction->paymentId = $request->paymentId;
                $payoutTransaction->status = $request->status;
                $payoutTransaction->transactionStatus = $request->transactionStatus;
                $payoutTransaction->paymentBrand = $request->paymentBrand;
                $payoutTransaction->paymentMode = $request->paymentMode;
                $payoutTransaction->firstName = $request->firstName;
                $payoutTransaction->lastName = $request->lastName;
                $payoutTransaction->amount = $request->amount;
                $payoutTransaction->currency = $request->currency;
                $payoutTransaction->descriptor = $request->descriptor;
                $payoutTransaction->merchantTransactionId = $request->merchantTransactionId;
                $payoutTransaction->remark = $request->remark;
                $payoutTransaction->tmpl_amount = $request->tmpl_amount;
                $payoutTransaction->tmpl_currency = $request->tmpl_currency;
                $payoutTransaction->checksum = $request->checksum;
                $payoutTransaction->result_code = $request->result['code'];
                $payoutTransaction->result_description = $request->result['description'];
                $payoutTransaction->customer_email = $request->customer['email'];
                $payoutTransaction->customer_id = $request->customer['id'];
                $payoutTransaction->card = json_encode($request->card);
                $payoutTransaction->timestamp = $request->timestamp;
                $payoutTransaction->eci = $request->eci;
                $payoutTransaction->bankReferenceId = $request->bankReferenceId;
                $payoutTransaction->terminalId = $request->terminalId;
                $payoutTransaction->update();
    
                return response()->json([
                    'message' => 'Transaction saved successfully',
                    'data' => $depositTransaction
                ], 200);
    
            } catch (Exception $e) {
                // Log to the custom api_error_log channel if an exception occurs
                Log::channel('api_error_log')->error('Transaction processing failed', [
                    'message' => $e->getMessage(),
                    'stack' => $e->getTraceAsString(),
                    'request' => $request->all(), // Optionally log the request data (be careful with sensitive data)
                ]);
                return response()->json([
                    'error' => 'An error occurred while processing the transaction',
                    'message' => $e->getMessage()
                ], 500);
            }
        }


}