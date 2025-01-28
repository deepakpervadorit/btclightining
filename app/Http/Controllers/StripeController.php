<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use App\Models\PaymentGateway;

class StripeController extends Controller
{
    // Show the form to edit the Stripe keys
    public function index()
    {
        // Fetch the current values from the .env file
        $stripeKey = env('STRIPE_KEY');
        $stripeSecret = env('STRIPE_SECRET');
        $status = PaymentGateway::where('name', 'stripe')->value('status');

        return view('admin.stripe-settings', compact('stripeKey', 'stripeSecret', 'status'));
    }

    // Update the Stripe keys
    public function updateStripe(Request $request)
    {
        $request->validate([
            'stripe_key' => 'required|string',
            'stripe_secret' => 'required|string',
        ]);

        // Update the .env file with new keys
        $this->updateEnv([
            'STRIPE_KEY' => $request->stripe_key,
            'STRIPE_SECRET' => $request->stripe_secret,
        ]);

        // Refresh config to apply the changes
        Artisan::call('config:clear');

        return back()->with('success', 'Stripe keys updated successfully!');
    }

    // Helper function to update .env
    protected function updateEnv(array $data)
    {
        $envPath = base_path('.env');

        if (File::exists($envPath)) {
            foreach ($data as $key => $value) {
                file_put_contents(
                    $envPath,
                    preg_replace(
                        "/^{$key}=.*/m",
                        "{$key}={$value}",
                        file_get_contents($envPath)
                    )
                );
            }
        }
    }
    
    public function update(Request $request)
    {
        $status = $request->has('switch_button') ? 1 : 0;
    
        // Use the PaymentGateway model to find or create the record
        $payment_gateway = PaymentGateway::firstOrCreate(['name' => 'stripe']);
        $payment_gateway->update(['status' => $status]);
    
        return back()->with('success', 'Stripe status updated successfully!');
    }
}