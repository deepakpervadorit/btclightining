<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use App\Models\PaymentGateway;

class SquareController extends Controller
{
    // Show the form to edit the Square keys
    public function index()
    {
        // Fetch the current values from the .env file
        $squareAccessToken = env('SQUARE_ACCESS_TOKEN');
        $squareLocationId = env('SQUARE_LOCATION_ID');
        $squareApplicationId = env('SQUARE_APPLICATION_ID');
        $status = PaymentGateway::where('name', 'square')->value('status');

        return view('admin.square-settings', compact('squareAccessToken', 'squareLocationId','squareApplicationId', 'status'));
    }

    // Update the Square keys
    public function updateSquare(Request $request)
    {
        $request->validate([
            'square_token' => 'required|string',
            'square_location_id' => 'required|string',
            'square_application_id' => 'required|string',
        ]);

        // Update the .env file with new keys
        $this->updateEnv([
            'SQUARE_ACCESS_TOKEN' => $request->square_token,
            'SQUARE_LOCATION_ID' => $request->square_location_id,
            'SQUARE_APPLICATION_ID' => $request->square_application_id,
        ]);

        // Refresh config to apply the changes
        Artisan::call('config:clear');

        return back()->with('success', 'Square keys updated successfully!');
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
        $payment_gateway = PaymentGateway::firstOrCreate(['name' => 'square']);
        $payment_gateway->update(['status' => $status]);
    
        return back()->with('success', 'Square status updated successfully!');
    }
}