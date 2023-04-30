<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{

    use RefreshDatabase;

    public function testStore()
    {
        $data = [
            'id' => 'ndsaofjadpgwle346pw6epjtuui',
            'name' => '山田　太郎',
            'email' => 'test@test.com',
            'role' => 1
        ];

        $response = $this->postJson('/api/user', $data);

        $response->assertStatus(201)
            ->assertJson([
                'user' => [
                    'id' => 'ndsaofjadpgwle346pw6epjtuui',
                    'name' => '山田　太郎',
                    'email' => 'test@test.com',
                    'role' => 1
                ],
                'message' => 'registration successfully'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => '山田　太郎',
            'email' => 'test@test.com',
            'role' => 1
        ]);
    }

    public function testStoreWithInvalidData()
    {
        $data = [
            'name' => '山田　太郎',
            'email' => 'test@test.com',
            'role' => 1
        ];

        $response = $this->postJson('/api/user', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id'])
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'id' => [
                        'The id field is required.'
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => '山田　太郎',
            'email' => 'test@test.com',
            'role' => 1
        ]);
    }

    public function testShow()
    {
        $data = [
            'id' => 'ndsaofjadpgwle346pw6epjtuui',
            'name' => '山田　太郎',
            'email' => 'test@test.com',
            'role' => 1
        ];
        $user = User::create($data)->first();
        
        $response = $this->getJson("/api/user/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]);
    }

    public function testShowWithInvalidId()
    {
        $invalidId = 'invalid_id';
        $response = $this->getJson("/api/user/{$invalidId}");

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Not found',
            ]);
    }
}
