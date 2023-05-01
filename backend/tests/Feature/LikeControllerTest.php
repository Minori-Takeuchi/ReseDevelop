<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Like;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;

class LikeControllerTest extends TestCase
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
        $data = [
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'shop_id' => $shop->id,
        ];

        $response = $this->postJson('/api/like', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('likes', [
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'shop_id' => $shop->id,
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
        $like = Like::create([
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'shop_id' => $shop->id,
        ]);
        $data = ['id' => $like->id];

        $response = $this->deleteJson('/api/like/' . $like->id, $data);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('likes', [
            'id' => $like->id,
        ]);
    }

    public function testDestroyWithInvalidId()
    {
        $invalidId = 99999;
        $response = $this->deleteJson('/api/like/' . $invalidId);
        $response->assertStatus(404);
    }

    public function testSearch()
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
        $like = Like::create([
            'user_id' => 'pFlEMCwGJmNI8RvpBTLgFbOiTic2',
            'shop_id' => $shop->id,
        ]);
        $data = ['user_id' => $like->user_id];

        $response = $this->postJson('/api/likes', $data);

        $response->assertStatus(200)
        ->assertJsonFragment([
            'likes' => [
                [
                    'id' => $like->id,
                    'user_id' => $like->user_id,
                    'shop_id' => $like->shop_id,
                ],
            ],
        ]);
    }
}
