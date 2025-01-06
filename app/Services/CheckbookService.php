<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class CheckbookService
{
    private $apiKey;
    private $apiSecret;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('CHECKBOOK_API_KEY');
        $this->apiSecret = env('CHECKBOOK_API_SECRET');
        $this->baseUrl = env('CHECKBOOK_ENDPOINT');

        // $this->apiKey = env('CHECKBOOK_SANDBOX_API_KEY');
        // $this->apiSecret = env('CHECKBOOK_SANDBOX_API_SECRET');
        // $this->baseUrl = env('CHECKBOOK_SANDBOX_ENDPOINT');
    }

    /**
     * Send a digital check.
     */
    public function sendDigitalCheck($name, $amount, $recipientEmail, $account, $description)
    {
        $url = $this->baseUrl . 'check/digital';
        
        // Prepare the authorization header with the API key and secret (no Base64 encoding)
        $authHeader = $this->apiKey . ':' . $this->apiSecret;
        
        // Make the POST request to Checkbook API
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($url, [
            'deposit_options' => ['ZELLE', 'CARD', 'VCC', 'BANK'],
            'amount' => $amount,
            'recipient' => $recipientEmail,
            'name' => $name,
            'account' => $account,
            'description' => $description,  // Add description if needed
        ]);

        // Check if the request was successful
        if ($response->successful()) {
            return $response->json(); // Return the JSON response
        } else {
            return ['error' => $response->status(), 'message' => $response->body()];
        }
    }

    public function getUserByUserId($name)
    {
        // The API endpoint to list users
        $url = $this->baseUrl . 'user';
        
        // Prepare the authorization header with the API key and secret
        $authHeader = $this->apiKey . ':' . $this->apiSecret;
        // Make the GET request to Checkbook API to fetch the user by user_id
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->get($url, [
            'user_id' => $name,  // Passing user_id as query parameter
        ]);
        // Check if the request was successful
        if ($response->successful()) {
            // User found, return user details
            return $response->json();
        }
    }

    public function removeUser($userId)
    {
        // dd($userId);
        // The API endpoint to delete a user
        $url = $this->baseUrl.$userId;
        
        // Prepare the authorization header with the API key and secret
        $authHeader = $this->apiKey . ':' . $this->apiSecret;

        // Make the DELETE request to Checkbook API with the user ID
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->delete($url);
        // dd($response->json());
        // Check if the request was successful
        if ($response->successful()) {

            return [
                'status' => 'success',
                'message' => 'User deleted successfully.',
                'data' => $response->json()
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to delete user.',
                'error' => $response->body()
            ];
        }
    }


    /**
     * Create a new user on Checkbook.
     */
    public function createUser($name)
    {
        $url = $this->baseUrl . 'user';
        // Prepare the authorization header with the API key and secret
        $authHeader = $this->apiKey . ':' . $this->apiSecret;
        
        // Make the POST request to Checkbook API to create a new user
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($url, [
            'user_id' => $name,
            'name' => $name,
        ]);
        // dd($authHeader, $response->json());
        // Return the response, this would be the newly created user
        if ($response->successful()) {
            return $response->json();
        } else {
            return ['error' => 'Unable to create user', 'details' => $response->body()];
        }
    }
    public function createZelleAccount($email, $api_key, $api_secret)
    {
       $url = $this->baseUrl . 'account/zelle';
            $authHeader = $api_key . ':' . $api_secret;
            $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
            ])->post($url, [
                'username' => $email
            ]);

           // Return response as an array or as needed
           return $response->json();
        
    }

    // Method for the Card Account creation
    public function createCardAccount($address, $cardNumber, $cvv, $expirationDate, $api_key, $api_secret)
    {
        $url = $this->baseUrl . 'account/card';
        $authHeader = $api_key . ':' . $api_secret;
        // dd($authHeader, $address, $cardNumber, $cvv, $expirationDate);
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post($url, [
            'line_1' => $address,
            'card_number' => $cardNumber,
            'cvv' => $cvv,
            'expiration_date' => $expirationDate
        ]);
        $cardAcc = $response->json();
        $api_id = $cardAcc['id'];
        $this->updateCurrentCardAccount($api_id, $api_key, $api_secret);

        // Return response as an array or as needed
        return $response->json();
    }

    public function updateCurrentCardAccount($card_id, $api_key, $api_secret){

        $url = $this->baseUrl . 'account/card/'.$card_id;
        $authHeader = $api_key . ':' . $api_secret;

        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->put($url, [
            'default' => true
        ]);
        
       return $response->json();
    }

    //Method for prev Card Deletion
    public function deletePrevCardAccount($card_id, $api_key, $api_secret){

        $url = $this->baseUrl . 'account/card/'.$card_id;
        $authHeader = $api_key . ':' . $api_secret;
        // dd($authHeader, $address, $cardNumber, $cvv, $expirationDate);
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->delete($url);

        // dd($response);
        // dd($card_id, $api_key, $api_secret);
        return $response->json();
    }

    // Method for the VCC Account creation
    public function createVCCAccount($email, $api_key, $api_secret)
    {
        $url = $this->baseUrl . 'account/vcc';
        $authHeader = $api_key . ':' . $api_secret;
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post($url, [
            'email' => $email
        ]);

        // Return response as an array or as needed
        return $response->json();
    }

    /**
     * Handle the individual deposit.
     */
    public function individualDeposit($name, $amount, $recipientEmail, $description, $depositOption,$api_id,$api_key,$api_secret,$user_id)
    {
        $url = $this->baseUrl . 'check/digital';
        
        // Prepare the authorization header with the API key and secret
        $authHeader = $this->apiKey . ':' . $this->apiSecret;
        // Make the POST request to Checkbook API
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($url, [
            'deposit_options' => [$depositOption],
            'amount' => $amount,
            'recipient' => $user_id,
            'name' => $user_id,
            'account' => '5ce693db390a46f5a1c679c2e39ea696',
            'description' => $description,
        ]);
        
        // Check if the request was successful
        if ($response->successful()) {
            $responseData = $response->json();
            $checkId = $responseData['id'] ?? null;  // Get the check ID from the response

            if ($checkId) {
                
                // Prepare for the second POST request to deposit the check
                $depositUrl = $this->baseUrl . "check/deposit/{$checkId}";
                
                // Authorization for the second request
                $depositAuthHeader = $api_key. ':' .$api_secret;
                
                $depositResponse = Http::withHeaders([
                    'Authorization' => $depositAuthHeader,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post($depositUrl, [
                    'account' => $api_id,
                ]);
                // Check if the second request was successful
                if ($depositResponse->successful()) {
                    return $depositResponse->json(); // Return the deposit response
                } else {
                    return ['error' => $depositResponse->status(), 'message' => $depositResponse->body()];
                }
            } else {
                return ['error' => 'No check ID received from the first request'];
            }
        } else {
            return ['error' => $response->status(), 'message' => $response->body()];
        }
    }
    public function virtualcard($api_key,$api_secret_key){
        $url = $this->baseUrl . 'account/vcc';
        
        // Prepare the authorization header with the API key and secret
        $authHeader = $api_key . ':' . $api_secret_key;
        
        // Make the GET request to Checkbook API to fetch the user by user_id
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->get($url);
        // Check if the request was successful
        if ($response->successful()) {
            
            return $response->json();
        }
    }
  public function virtualcardDetails($api_key, $api_secret_key, $vccid)
{    
    $url = $this->baseUrl . 'account/vcc/' . $vccid . '/transaction';
        // Prepare the authorization header with the API key and secret
        $authHeader = $api_key . ':' . $api_secret_key;
        
        // Make the GET request to Checkbook API to fetch the user by user_id
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->get($url);
        // Check if the request was successful
        if ($response->successful()) {
            
            return $response->json();
        } else {
                        return $response->json();

        }
}
    public function deleteVirtualCard($api_key, $api_secret_key, $card_id)
{
    // Define the base URL and specific endpoint for the DELETE request
    $url = $this->baseUrl . 'account/vcc/' . $card_id;

    // Prepare the authorization header with the API key and secret
    $authHeader = base64_encode($api_key . ':' . $api_secret_key);

    // Make the DELETE request to the API
    $response = Http::withHeaders([
        'Authorization' => 'Basic ' . $authHeader,
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->delete($url);

    // Check if the request was successful
    if ($response->successful()) {
        return $response->json(); // Return the response JSON if successful
    } else {
        // Return an error message or status code
        return [
            'status' => $response->status(),
            'error' => $response->body()
        ];
    }
}
public function deleteCard($api_key, $api_secret_key, $card_id)
{
    // Define the endpoint URL for the DELETE request
    $url = 'https://demo.checkbook.io/v3/account/card/' . $card_id;

    // Prepare the Authorization header using API key and secret
    $authHeader = base64_encode($api_key . ':' . $api_secret_key);

    // Make the DELETE request to the API
    $response = Http::withHeaders([
        'Authorization' => 'Basic ' . $authHeader,
        'Accept' => 'application/json',
    ])->delete($url);

    // Check if the request was successful
    if ($response->successful()) {
        return $response->json(); // Return the successful response as JSON
    } else {
        // Return error details if the request fails
        return [
            'status' => $response->status(),
            'error' => $response->body()
        ];
    }
}
public function deleteZelleAccount($api_key, $api_secret_key, $zelle_id)
{
    // Define the endpoint URL for the DELETE request
    $url = 'https://demo.checkbook.io/v3/account/zelle/' . $zelle_id;

    // Construct the Authorization header with API key and secret
    $authHeader = $api_key . ':' . $api_secret_key;

    // Make the DELETE request to the API
    $response = Http::withHeaders([
        'Authorization' => $authHeader,
        'Accept' => 'application/json',
    ])->delete($url);

    // Check if the request was successful
    if ($response->successful()) {
        return $response->json(); // Return the successful response as JSON
    } else {
        // Return error details if the request fails
        return [
            'status' => $response->status(),
            'error' => $response->body()
        ];
    }
}
}
