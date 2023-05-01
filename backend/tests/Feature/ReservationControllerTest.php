<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Course;
use App\Models\Reservation;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testStore()
    {
        $area = Area::create(['area' => '東京都']);
        $genre = Genre::create(['genre' => '寿司']);
        $shop = Shop::create([
            'manager_id' => 'ySbOMTep1fQTTJ60JjImbDkrhPY2',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'shop_name' => 'Sushi Place',
            'content' => '寿司屋です',
            'img' => 'sushi.jpg'
        ]);
        $course = Course::create([
            'shop_id' => $shop->id,
            'course' => 'ランチセット',
            'price' => 1000
        ]);
        $data = [
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'course_id' => $course->id,
            'date' => '2024-04-29',
            'time' => '12:00',
            'number' => 5,
        ];
        $response = $this->postJson('/api/reserve', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reservations', [
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'course_id' => $course->id,
            'datetime' => '2024-04-29 12:00:00',
            'number' => 5,
        ]);
    }

    public function testStoreWithInvalidData()
    {
        $data = [
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'date' => '2024-04-29',
            'time' => '12:00',
            'number' => 5,
        ];

        $response = $this->postJson('/api/reserve', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['course_id'])
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                'course_id' => [
                    'The course id field is required.'
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('reservations', [
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'datetime' => '2024-04-29 12:00:00',
            'number' => 5,
        ]);
    }

    public function testDestroy()
    {
        $area = Area::create(['area' => '東京都']);
        $genre = Genre::create(['genre' => '寿司']);
        $shop = Shop::create([
            'manager_id' => 'ySbOMTep1fQTTJ60JjImbDkrhPY2',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'shop_name' => 'Sushi Place',
            'content' => '寿司屋です',
            'img' => 'sushi.jpg'
        ]);
        $course = Course::create([
            'shop_id' => $shop->id,
            'course' => 'ランチセット',
            'price' => 1000
        ]);
        $reservation = Reservation::create([
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'course_id' => $course->id,
            'datetime' => '2024-04-29 12:00:00',
            'number' => 5,
        ]);
        $data = ['id' => $reservation->id];

        $response = $this->deleteJson('/api/reserve/' . $reservation->id, $data);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('reservations', [
            'id' => $reservation->id,
        ]);
    }

    public function testDestroyWithInvalidId()
    {
        $invalidId = 99999;
        $response = $this->deleteJson('/api/reserve/' . $invalidId);
        $response->assertStatus(404);
    }

    public function testUpdate()
    {
        $area = Area::create(['area' => '東京都']);
        $genre = Genre::create(['genre' => '寿司']);
        $shop = Shop::create([
            'manager_id' => 'ySbOMTep1fQTTJ60JjImbDkrhPY2',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'shop_name' => 'Sushi Place',
            'content' => '寿司屋です',
            'img' => 'sushi.jpg'
        ]);
        $course = Course::create([
            'shop_id' => $shop->id,
            'course' => 'ランチセット',
            'price' => 1000
        ]);
        $reservation = Reservation::create([
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'course_id' => $course->id,
            'datetime' => '2024-04-29 12:00:00',
            'number' => 5,
        ]);
        $data = [
            'id' => $reservation->id,
            'date' => '2024-04-30',
            'time' => '13:00',
            'number' => 3,
        ];
        $response = $this->putJson('/api/reserve/'. $reservation->id, $data);

        $response->assertStatus(200)
        ->assertJson([
            'message' => 'updated successfully',
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'course_id' => $course->id,
            'datetime' => '2024-04-30 13:00:00',
            'number' => 3,
        ]);
    }

    public function testUpdateWithInvalidId()
    {
        $invalidId = 99999;
        $data = [
                'id' => $invalidId,
                'date' => '2024-04-30',
                'time' => '13:00',
                'number' => 3,
            ];
        $response = $this->putJson('/api/reserve/'. $invalidId, $data);
        $response->assertStatus(404)
        ->assertJson([
            'message' => 'Not found',
        ]);

        $this->assertDatabaseMissing('reservations', [
            'id' => $invalidId,
        ]);
    }

}
