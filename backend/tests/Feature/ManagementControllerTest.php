<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Course;
use App\Models\Reservation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ManagementControllerTest extends TestCase
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
        $response = $this->getJson("/api/manager/ySbOMTep1fQTTJ60JjImbDkrhPY2");
        $response->assertOk()
            ->assertJson([
                'manager' => [
                    'id' => 'ySbOMTep1fQTTJ60JjImbDkrhPY2',
                    'name' => '店舗代表者',
                ],
                'shops' => [
                    [
                        'id' => $shop->id,
                        'shop_name' => $shop->shop_name,
                        'reservations' => [
                            [
                                'id' => $reservation->id,
                                'date' => '2024-04-29',
                                'time' => '12:00',
                                'number' => $reservation->number,
                                'user_name' => $reservation->user->name,
                                'course' => $course->course,
                            ],
                        ],
                    ],
                ],
                'areas' => [
                    [
                        'id' => $area->id,
                        'area' => $area->area,
                    ],
                ],
                'genres' => [
                    [
                        'id' => $genre->id,
                        'genre' => $genre->genre,
                    ],
                ],
            ]);
    }

    public function testShowWithInvalidId()
    {
        $response = $this->getJson('/api/manager/999999');

        $response->assertNotFound();
    }

    public function testStore()
    {
        Storage::fake('s3');
        $area = Area::create(['area' => '東京都']);
        $genre = Genre::create(['genre' => '寿司']);
        $file = UploadedFile::fake()->create('test.jpg');
        $data = [
                'manager_id' => 'ySbOMTep1fQTTJ60JjImbDkrhPY2',
                'area_id' => $area->id,
                'genre_id' => $genre->id,
                'shop_name' => 'Sushi Place',
                'content' => '寿司屋です',
                'img' => $file
            ];

        $response = $this->postJson('/api/manager', $data);

        $response->assertCreated();

    }
    public function testStoreWithInvalidId()
    {
        $area = Area::create(['area' => '東京都']);
        $genre = Genre::create(['genre' => '寿司']);
        $response = $this->postJson('/api/manager', [
            'manager_id' => 'ySbOMTep1fQTTJ60JjImbDkrhPY2',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'shop_name' => '',
            'content' => '寿司屋です',
            'img' => UploadedFile::fake()->create('test.jpg')
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.'
        ]);
    }
}
