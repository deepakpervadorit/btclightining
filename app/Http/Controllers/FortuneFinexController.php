<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class FortuneFinexController extends Controller
{
    // Show the form to edit the Checkbook keys
    public function index()
    {
        // Fetch the current values from the .env file
        $member_id = env('MEMBER_ID');
        $language = env('LANGUAGE');
        $secret_key = env('SECRET_KEY');
        $to_type = env('TOTYPE');
        $redirect_url = env('MERCHANT_REDIRECT_URL');
        $notification_url = env('NOTIFICATION_URL');
        $terminal_id = env('TERMINAL_ID');
        $partner_id = env('PARTNER_ID');
        $api_type = env('URL');
        return view('admin.fortunefinex-settings', compact('member_id', 'language', 'secret_key', 'to_type', 'redirect_url', 'notification_url', 'terminal_id', 'partner_id', 'api_type'));
    }

    public function updateKeys(Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'language' => 'required',
            'secret_key' => 'required',
            'to_type' => 'required',
            'redirect_url' => 'required',
            'notification_url' => 'required',
            'terminal_id' => 'required',
            'partner_id' => 'required',
            'api_type' => 'required',
        ]);
        $apitype = $request->api_type;
        if($apitype == 'sandbox'){
        // Update the .env file with new keys
        $this->updateEnv([
            'MEMBER_ID' => $request->member_id,
            'LANGUAGE' => $request->language,
            'SECRET_KEY' => $request->secret_key,
            'TOTYPE' => $request->to_type,
            'MERCHANT_REDIRECT_URL' => $request->redirect_url,
            'NOTIFICATION_URL' => $request->notification_url,
            'TERMINAL_ID' => $request->terminal_id,
            'PARTNER_ID' => $request->partner_id,
            'URL' => "https://sandbox.fortunefinex.com/transaction/Checkout",
        ]);
    } else if ($apitype == 'production') {
        $this->updateEnv([
            'MEMBER_ID' => $request->member_id,
            'LANGUAGE' => $request->language,
            'SECRET_KEY' => $request->secret_key,
            'TOTYPE' => $request->to_type,
            'MERCHANT_REDIRECT_URL' => $request->redirect_url,
            'NOTIFICATION_URL' => $request->notification_url,
            'TERMINAL_ID' => $request->terminal_id,
            'PARTNER_ID' => $request->partner_id,
            'URL' => "https://secure.fortunefinex.com/transaction/Checkout",
        ]);
    }

        // Refresh config to apply the changes
        Artisan::call('config:clear');

        return back()->with('success', 'FortuneFinex keys updated successfully!');
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
