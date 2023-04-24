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

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'jpy',
                'payment_method' => $paymentMethod,
                'confirm' => true,
            ]);

            if($paymentIntent->status === 'succeeded') {
                return response()->json([
                    'success' => true,
                    'massage' => 'Payment successfully',
                ],200);
            } else {
                return response()->json([
                    'success' =>false,
                    'massage' => 'Payment failed',
                ],400);
            }
    }
}
