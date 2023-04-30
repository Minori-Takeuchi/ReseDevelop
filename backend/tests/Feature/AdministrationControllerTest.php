<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Like;

class AdministrationControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }


    public function testIndex()
    {
        $area = Area::create(['area' => '東京都']);
        $genre = Genre::create(['genre' => '寿司']);
        $shop = Shop::create([
            'manager_id' => '15O7exnOGNTe4BcxuZsadKcXBGk1',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'shop_name' => 'Sushi Place',
            'content' => '寿司屋です',
            'img' => 'sushi.jpg'
        ]);
        $like = Like::create([
            'user_id' => 'OHDQrOCGleXcGIaTKhLKSQzfNQG3',
            'shop_id' => $shop->id,
        ]);

        $response = $this->get('/api/admin');
        $response->assertStatus(200)
            ->assertJson([
                'shops' => [
                    [
                        'id' => $shop->id,
                        'shop_name' => $shop->shop_name,
                        'area' => $area->area,
                        'manager_id' => $shop->manager_id,
                        'manager_name' => $shop->manager->name,
                        'manager_email' => $shop->manager->email,
                    ]
                ],
                'managers' => [
                    [
                        'id' => '15O7exnOGNTe4BcxuZsadKcXBGk1',
                        'name' => '店舗代表者',
                        'email' => 'manager@gest.com',
                        'shops' => [
                            [
                                'id' => $shop->id,
                                'shop_name' => $shop->shop_name,
                                'manager_id' => $shop->manager_id,
                            ]
                        ],
                    ]
                ],
                'users' => [
                    [
                        'id' => 'OHDQrOCGleXcGIaTKhLKSQzfNQG3',
                        'name' => '利用者1',
                        'email' => 'gest@gest.com',
                        'likes' => [
                            [
                                'id' => $like->id,
                                'user_id' => $like->user_id,
                                'shop_id' => $like->shop_id,
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
