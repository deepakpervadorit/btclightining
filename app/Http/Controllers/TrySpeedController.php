<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use App\Models\PaymentGateway;

class TrySpeedController extends Controller
{
    // Show the form to edit the Checkbook keys
    public function index()
    {
        // Fetch the current values from the .env file
        $secret_key = env('SPEED_SECRET_KEY');
        $status = PaymentGateway::where('name', 'trySpeed')->value('status');
        
        return view('admin.tryspeed-settings', compact('secret_key', 'status'));
    }

    public function updateKeys(Request $request)
    {
        $request->validate([
            'secret_key' => 'required',
        ]);
        // Update the .env file with new keys
        $this->updateEnv([
            'SPEED_SECRET_KEY' => $request->secret_key,
        ]);

        // Refresh config to apply the changes
        Artisan::call('config:clear');

        return back()->with('success', 'TrySpeed keys updated successfully!');
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
        $payment_gateway = PaymentGateway::firstOrCreate(['name' => 'trySpeed']);
        $payment_gateway->update(['status' => $status]);
    
        return back()->with('success', 'Cashapp Crypto status updated successfully!');
    }
}
