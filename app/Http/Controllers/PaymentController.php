<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public function createPaymentIntent()
    {
        try {
            // Set your secret key from the .env file
            $stripe = new StripeClient(env('STRIPE_SECRET'));
// print_r($stripe);
// exit();
            $paymentIntent = $stripe->paymentIntents->create([
                'payment_method_types' => ['card'],
                'amount' => 7.50 * 100,
                'currency' => 'eur',
            ]);

            // Handle successful paymentIntent creation
            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            // Handle any errors that occur during the paymentIntent creation
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
