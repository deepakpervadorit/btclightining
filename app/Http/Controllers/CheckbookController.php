<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class CheckbookController extends Controller
{
    // Show the form to edit the Checkbook keys
    public function index()
    {
        // Fetch the current values from the .env file
        $checkbookKey = env('CHECKBOOK_API_KEY');
        $checkbookSecret = env('CHECKBOOK_API_SECRET');
        $api_type = env('CHECKBOOK_ENDPOINT');
        return view('admin.checkbook-settings', compact('checkbookKey', 'checkbookSecret','api_type'));
    }

    // Update the Checkbook keys
    public function updateCheckbook(Request $request)
    {
        $request->validate([
            'checkbook_key' => 'required|string',
            'checkbook_secret' => 'required|string',
        ]);
        $apitype = $request->api_type;
        if($apitype == 'sandbox'){
        // Update the .env file with new keys
        $this->updateEnv([
            'CHECKBOOK_API_KEY' => $request->checkbook_key,
            'CHECKBOOK_API_SECRET' => $request->checkbook_secret,
            'CHECKBOOK_ENDPOINT' => 'https://sandbox.checkbook.io/v3/'
        ]);
    } else if ($apitype == 'production') {
        $this->updateEnv([
            'CHECKBOOK_API_KEY' => $request->checkbook_key,
            'CHECKBOOK_API_SECRET' => $request->checkbook_secret,
            'CHECKBOOK_ENDPOINT' => 'https://api.checkbook.io/v3/'
        ]);
    }

        // Refresh config to apply the changes
        Artisan::call('config:clear');

        return back()->with('success', 'Checkbook keys updated successfully!');
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
}