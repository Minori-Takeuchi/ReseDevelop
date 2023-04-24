<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Course;

class ShopController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shops = Shop::with(['area', 'genre'])->get();
        $result = [];
        foreach ($shops as $item) {
            $shop = [
                'id' => $item->id,
                'area' => $item->area->area,
                'genre' => $item->genre->genre,
                'shop_name' => $item->shop_name,
                'content' => $item->content,
                'img' => $item->img ? $item->img : 'NoImage.jpg',
                'like_id' => null,
            ];
            $result[] = $shop;
        }
        return response()->json([
            'shops' => $result,
            'areas' => $areas,
            'genres' => $genres,
        ], 200);
    }
    public function show(Shop $shop)
    {
        $item = Shop::with(['area'])
            ->with(['genre'])
            ->with(['courses'])
            ->find($shop->id);
        $shop = [
            'id' => $item->id,
            'shop_name' => $item->shop_name,
            'content' => $item->content,
            'img' =>
            $item->img ? $item->img : 'NoImage.jpg',
        ];
        $area = $item -> area;
        $genre = $item ->genre;
        $areaName = $area ? $area->area : null;
        $genreName = $genre ? $genre->genre : null;
        $courses = $item->courses;
        if ($item) {
            return response()->json([
                'shop' => $shop,
                'area' => $areaName,
                'genre' => $genreName,
                'courses' => $courses
            ], 200);
        } else {
            return response()->json([
                'massage' => 'Not found'
            ], 404);
        }
    }
}
