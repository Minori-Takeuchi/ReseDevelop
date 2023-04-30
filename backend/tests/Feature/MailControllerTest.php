<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Feature\factory;
use App\Models\User;

class MailControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }
    
    public function testSendMail()
    {
        $data = [
            'email' => 'gest@gest.com',
        ];

        $response = $this->postJson('/api/mail', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Email sent successfully',
        ]);
    }

    public function testSendMailWithInvalid()
    {
        $invalidEmail = 'test@test.com';

        $data = [
            'email' => $invalidEmail,
        ];

        $response = $this->postJson('/api/mail', $data);

        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Not found email',
        ]);
    }
}
