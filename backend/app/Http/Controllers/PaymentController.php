<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function charge(Request $request)
    {
        $amount = $request->amount;
        $paymentMethod = $request->payment_method_id;

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'jpy',
                'payment_method' => $paymentMethod,
                'confirm' => true,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Payment successfully',
            ],
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ],
                400
            );
        }
    }
}
