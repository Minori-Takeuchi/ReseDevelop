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
use App\Models\Rating;
use App\Models\Like;
use DateTime;

class MypageControllerTest extends TestCase
{

    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testShow()
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
        $rating = Rating::create([
            'reservation_id' => $reservation->id,
            'rating' => 4,
            'comment' => 'Great experience!',
        ]);
        $like = Like::create([
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'shop_id' => $shop->id,
            ]);

        $response = $this->get('api/mypage/pFlEMCwGJmNI8RvpBTLgFbOiTic2');

        $response->assertOk()->assertJson([
            'shops' => [
                [
                    'id' => $shop->id,
                    'shop_name' => $shop->shop_name,
                    'area' => $area->area,
                    'genre' => $genre->genre,
                    'img' => $shop->img,
                    'like_id' => $like->id,
                ]
            ],
            'reservations' => [
                [
                    'id' => $reservation->id,
                    'shop' => $shop->shop_name,
                    'course_id' => $course->id,
                    'course' => $course->course,
                    'price' => $course->price,
                    'date' => '2024-04-29',
                    'time' => '12:00',
                    'number' => $reservation->number,
                    'rating_id' => $rating->id,
                    'rating' => $rating->rating,
                    'comment' => $rating->comment,
                ]
            ]
        ]);
    }
    public function testShowWithInvalidId()
    {
        $invalidId = 99999;
        $response = $this->getJson('/api/mypage/' . $invalidId);
        $response->assertNotFound();
    }
}
