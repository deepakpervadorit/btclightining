<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Set your Stripe secret key
        $stripeSecretKey = env('STRIPE_SECRET');
        Stripe::setApiKey($stripeSecretKey);

        // Retrieve the webhook payload
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET'); // Your webhook secret

        try {
            // Verify the webhook signature
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            // Handle the event
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object; // Contains a \Stripe\PaymentIntent
                    // Handle successful payment here
                    break;
                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object; // Contains a \Stripe\PaymentIntent
                    // Handle failed payment here
                    break;
                // Handle other event types as needed
                default:
                    // Unexpected event type
                    return response()->json(['status' => 'ignored'], 200);
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }
}
?>