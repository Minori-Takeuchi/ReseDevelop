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


class RatingControllerTest extends TestCase
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
        $reservation = Reservation::create([
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'course_id' => $course->id,
            'datetime' => '2024-04-29 12:00:00',
            'number' => 5,
        ]);
        $data = [
            'reservation_id' => $reservation->id,
            'rating' => 4,
            'comment' => 'Great experience!',
        ];

        $response = $this->postJson('/api/rating', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('ratings', [
            'reservation_id' => $reservation->id,
            'rating' => 4,
            'comment' => 'Great experience!',
        ]);
    }
}
