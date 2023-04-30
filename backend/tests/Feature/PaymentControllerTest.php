<?php

namespace Tests\Feature;

use App\Http\Controllers\PaymentController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;


class PaymentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function setUp(): void
    {
        parent::setUp();
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    public function testCharge()
    {
        $request = new Request([
            'amount' => 2000,
            'payment_method_id' => 'pm_card_visa',
        ]);

        $controller = new PaymentController();
        $response = $controller->charge($request);

        $this->assertEquals(200, $response->status());
        $this->assertEquals(true, $response->original['success']);
        $this->assertEquals('Payment successfully', $response->original['message']);

    }

    public function testChargeError()
    {
        $request = new Request([
            'amount' => 0,
            'payment_method_id' => 'pm_card_visa',
        ]);

        $controller = new PaymentController();
        $response = $controller->charge($request);

        $this->assertEquals(400, $response->status());
        $this->assertEquals(false, $response->original['success']);
        $this->assertEquals('Payment failed: This value must be greater than or equal to 1.', $response->original['message']);

    }
}
