<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Deposit;
use App\Mail\DepositMail;
use Illuminate\Support\Facades\Mail;

class CheckMerchantPaymentStatus extends Command
{
    protected $signature = 'payments:check-merchant-status';
    protected $description = 'Check and update the status of merchant payments';

    public function handle()
    {
        // Fetch all pending transactions
        $pendingTransactions = DB::table('deposit_transactions')
            ->whereIn('status', ['Pending', 'Completed'])
            ->where('is_checked', false) // Only fetch unchecked transactions
            ->get();

        foreach ($pendingTransactions as $transaction) {
            try {
                // Call the API to check payment status
                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode(env('SPEED_SECRET_KEY')),
                    'speed-version' => '2022-04-15',
                ])->get('https://api.tryspeed.com/payments/' . $transaction->transaction_id);

                if ($response->ok()) {
                    $paymentData = $response->json();

                    // Update the payment status in the database
                    if ($paymentData['status'] === 'paid') {
                        echo "PAID";
                        DB::table('deposit_transactions')
                            ->where('transaction_id', $transaction->transaction_id)
                            ->update([
                                'status' => 'Completed',
                                'is_checked' => true, // Mark as checked
                            ]);

                        // Check if the transaction already exists in the deposits table
                        $existingDeposit = Deposit::where('session_id', $transaction->transaction_id)->first();

                        if ($existingDeposit) {
                            // Update the existing deposit record
                            $existingDeposit->update([
                                'server' => $transaction->server,
                                'game_username' => $transaction->game_username,
                                'game_id' => $transaction->game_id,
                                'merchant_id' => $transaction->merchant_id,
                                'amount' => $transaction->amount,
                                'status' => 'Completed',
                                'payment_method' => $transaction->paymentGateway,
                                'currency' => $transaction->currency,
                            ]);

                            Log::info('Existing deposit record updated', ['transaction_id' => $transaction->transaction_id]);
                        } else {
                            // Insert a new deposit record
                            Deposit::create([
                                'server' => $transaction->server,
                                'game_username' => $transaction->game_username,
                                'game_id' => $transaction->game_id,
                                'session_id' => $transaction->transaction_id,
                                'merchant_id' => $transaction->merchant_id,
                                'amount' => $transaction->amount,
                                'status' => 'Completed',
                                'payment_method' => $transaction->paymentGateway,
                                'currency' => $transaction->currency,
                            ]);

                            Log::info('New deposit record created', ['transaction_id' => $transaction->transaction_id]);
                        }

                        // Notify the merchant via email
                        $merchant = DB::table('staff')->where('id', $transaction->merchant_id)->first();
                        Mail::to($merchant->email)->send(new DepositMail($merchant, $transaction->amount));

                        Log::info('Payment updated to Completed', ['transaction_id' => $transaction->transaction_id]);
                    } elseif ($paymentData['status'] === 'failed') {
                        DB::table('deposit_transactions')
                            ->where('transaction_id', $transaction->transaction_id)
                            ->update(['status' => 'Failed']);

                        Log::info('Payment updated to Failed', ['transaction_id' => $transaction->transaction_id]);
                    }
                } else {
                    Log::warning('Failed to fetch payment status', ['transaction_id' => $transaction->transaction_id]);
                }
            } catch (\Exception $e) {
                Log::error('Error checking payment status', [
                    'transaction_id' => $transaction->transaction_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info('Merchant payment statuses updated successfully.');
    }
}
