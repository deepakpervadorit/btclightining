<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use App\Models\PaymentGateway;

class CheckbookController extends Controller
{
    // Show the form to edit the Checkbook keys
    public function index()
    { 
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);
        // Fetch the current values from the .env file
        $checkbook_production_key = env('CHECKBOOK_API_KEY');
        $checkbook_production_secret = env('CHECKBOOK_API_SECRET');
        
        $checkbook_sandbox_key = env('CHECKBOOK_SANDBOX_API_KEY');
        $checkbook_sandbox_secret = env('CHECKBOOK_SANDBOX_API_SECRET');
        
        $commented_production_key = $this->getCommentedValue($envContent, 'CHECKBOOK_API_KEY');
        $commented_production_secret = $this->getCommentedValue($envContent, 'CHECKBOOK_API_SECRET');
        $commented_sandbox_key = $this->getCommentedValue($envContent, 'CHECKBOOK_SANDBOX_API_KEY');
        $commented_sandbox_secret = $this->getCommentedValue($envContent, 'CHECKBOOK_SANDBOX_API_SECRET');

        $status = PaymentGateway::where('name', 'checkbook')->value('status');
        
        return view('admin.checkbook-settings', compact('checkbook_production_key', 'checkbook_production_secret', 'checkbook_sandbox_key', 'checkbook_sandbox_secret','commented_production_key','commented_production_secret','commented_sandbox_key','commented_sandbox_secret','status'));
    }
    
    private function getCommentedValue($envContent, $key)
    {
        preg_match("/#\s*{$key}=(.*)/", $envContent, $matches);
        return $matches[1] ?? null;
    }

    // Update the Checkbook keys
    public function updateCheckbook(Request $request)
    {
        $request->validate([
            'api_type' => 'required|in:sandbox,production'
        ]);
    
        $apitype = $request->api_type;
        $envUpdates = [];
    
        if ($apitype === 'sandbox') {
            // Enable sandbox, comment out production
            $envUpdates = [
                'CHECKBOOK_SANDBOX_API_KEY' => $request->checkbook_sandbox_key,
                'CHECKBOOK_SANDBOX_API_SECRET' => $request->checkbook_sandbox_secret,
                'CHECKBOOK_SANDBOX_ENDPOINT' => 'https://sandbox.checkbook.io/v3/',
    
                'CHECKBOOK_API_KEY' => '#' . env('CHECKBOOK_API_KEY'),
                'CHECKBOOK_API_SECRET' => '#' . env('CHECKBOOK_API_SECRET'),
                'CHECKBOOK_ENDPOINT' => '#' . env('CHECKBOOK_ENDPOINT'),
            ];
        } else if ($apitype === 'production') {
            // Enable production, comment out sandbox
            $envUpdates = [
                'CHECKBOOK_API_KEY' => $request->checkbook_production_key,
                'CHECKBOOK_API_SECRET' => $request->checkbook_production_secret,
                'CHECKBOOK_ENDPOINT' => 'https://api.checkbook.io/v3/',
    
                'CHECKBOOK_SANDBOX_API_KEY' => '#' . env('CHECKBOOK_SANDBOX_API_KEY'),
                'CHECKBOOK_SANDBOX_API_SECRET' => '#' . env('CHECKBOOK_SANDBOX_API_SECRET'),
                'CHECKBOOK_SANDBOX_ENDPOINT' => '#' . env('CHECKBOOK_SANDBOX_ENDPOINT'),
            ];
        }
    
        // Update the .env file
        $this->updateEnv($envUpdates);
    
        // Refresh config to apply the changes
        Artisan::call('config:clear');
    
        return back()->with('success', 'Checkbook keys updated successfully!');
    }

    // Helper function to update .env
    protected function updateEnv(array $data)
    {
        $envPath = base_path('.env');
    
        if (File::exists($envPath)) {
            $envContent = file_get_contents($envPath);
    
            foreach ($data as $key => $value) {
                $pattern = "/^#?{$key}=.*/m";
    
                if (strpos($value, '#') === 0) {
                    // Comment out the line
                    $replacement = "#{$key}=" . ltrim($value, '#');
                } else {
                    // Uncomment and update the line
                    $replacement = "{$key}={$value}";
                }
    
                if (preg_match($pattern, $envContent)) {
                    // Replace the existing line
                    $envContent = preg_replace($pattern, $replacement, $envContent);
                } else {
                    // Append if the key doesn't exist
                    $envContent .= PHP_EOL . $replacement;
                }
            }
    
            file_put_contents($envPath, $envContent);
        }
    }
    
    public function update(Request $request)
    {
        $status = $request->has('switch_button') ? 1 : 0;
    
        // Use the PaymentGateway model to find or create the record
        $payment_gateway = PaymentGateway::firstOrCreate(['name' => 'checkbook']);
        $payment_gateway->update(['status' => $status]);
    
        return back()->with('success', 'Checkbook status updated successfully!');
    }
}