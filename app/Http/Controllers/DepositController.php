<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    public function index()
    {
        $deposits = DB::table('deposit_transactions')
    ->orderBy('created_at', 'asc')
    ->get();
        return view('admin.deposit',compact('deposits'));
    }
    public function storeDepositAmount(Request $request){
         $data = [
                  'transaction_id' => $request->transaction_id,
                  'user_id' => $request->user_id,
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
        'created_at' => now(), // Optional: if you want to manually set created_at timestamp
        'updated_at' => now(), // Optional: if you want to manually set updated_at timestamp
    ];

    // Insert the data into the 'deposit_transactions' table
    $deposit_transaction = DB::table('deposit_transactions')->insert($data);
    }
    public function destroy($id)
{
    // Delete the deposit using DB::table()
    DB::table('deposits')->where('id', $id)->delete();

    // Redirect back with a success message
    return redirect()->route('deposit')->with('success', 'Deposit deleted successfully.');
}
}
