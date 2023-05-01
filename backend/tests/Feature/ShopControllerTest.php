<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Course;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testIndex()
    {

        $area1 = Area::create(['area' => '東京都']);
        $area2 = Area::create(['area' => '大阪府']);
        $genre1 = Genre::create(['genre' => '寿司']);
        $genre2 = Genre::create(['genre' => 'イタリアン']);
        $shop1 = Shop::create([
            'manager_id' => 'ySbOMTep1fQTTJ60JjImbDkrhPY2',
            'area_id' => $area1->id,
            'genre_id' => $genre1->id,
            'shop_name' => 'Sushi Place',
            'content' => '寿司屋です',
            'img' => 'sushi.jpg'
        ]);
        $shop2 = Shop::create([
            'manager_id' => 'ySbOMTep1fQTTJ60JjImbDkrhPY2',
            'area_id' => $area2->id,
            'genre_id' => $genre2->id,
            'shop_name' => 'Pasta Place',
            'content' => 'パスタ屋です',
            'img' => null
        ]);

        $response = $this->getJson('/api/shop');

        $response->assertOk()
            ->assertJson([
                'shops' => [
                    [
                        'id' => $shop1->id,
                        'area' => '東京都',
                        'genre' => '寿司',
                        'shop_name' => 'Sushi Place',
                        'content' => '寿司屋です',
                        'img' => 'sushi.jpg',
                        'like_id' => null,
                    ],
                    [
                        'id' => $shop2->id,
                        'area' => '大阪府',
                        'genre' => 'イタリアン',
                        'shop_name' => 'Pasta Place',
                        'content' => 'パスタ屋です',
                        'img' => 'NoImage.jpg',
                        'like_id' => null,
                    ]
                ],
                'areas' => [
                    [
                        'id' => $area1->id,
                        'area' => '東京都',
                    ],
                    [
                        'id' => $area2->id,
                        'area' => '大阪府',
                    ]
                ],
                'genres' => [
                    [
                        'id' => $genre1->id,
                        'genre' => '寿司',
                    ],
                    [
                        'id' => $genre2->id,
                        'genre' => 'イタリアン',
                    ]
                ]
            ]);
    }

    public function testShow()
    {
        $area1 = Area::create(['area' => '東京都']);
        $genre1 = Genre::create(['genre' => '寿司']);
        $shop = Shop::create([
            'manager_id' => 'ySbOMTep1fQTTJ60JjImbDkrhPY2',
            'area_id' => $area1->id,
            'genre_id' => $genre1->id,
            'shop_name' => 'Sushi Place',
            'content' => '寿司屋です',
            'img' => 'sushi.jpg'
        ]);
        $course1 = Course::create([
            'shop_id' => $shop->id,
            'course' => 'ランチセット',
            'price' => 1000
        ]);
        $course2 = Course::create([
            'shop_id' => $shop->id,
            'course' => 'ディナーセット',
            'price' => 2000
        ]);

        $response = $this->getJson('/api/shop/' . $shop->id);

        $response->assertOk()
            ->assertJson([
                'shop' => [
                    'id' => $shop->id,
                    'shop_name' => 'Sushi Place',
                    'content' => '寿司屋です',
                    'img' => 'sushi.jpg',
                ],
                'area' => '東京都',
                'genre' => '寿司',
                'courses' => [
                    [
                        'id' => $course1->id,
                        'course' => 'ランチセット',
                        'price' => 1000
                    ],
                    [
                        'id' => $course2->id,
                        'course' => 'ディナーセット',
                        'price' => 2000
                    ]
                ]
            ]);
    }

    public function testShowWithInvalidId()
    {
        $response = $this->getJson('/api/shop/999999');

        $response->assertNotFound();
    }

}
