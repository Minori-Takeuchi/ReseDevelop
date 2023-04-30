<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DateTime;

class MypageController extends Controller
{
    public function show($id)
    {
        $user = User::with(['likes.shop.area', 'likes.shop.genre'])->find($id);
        $item = User::with(['reservations.shop.area', 'reservations.shop.genre','reservations.rating'])->find($id);
        if($user) {
                $shopsData = [];
                foreach ($user->likes as $like) {
                    $shopsData[] = [
                        'id' => $like->shop->id,
                        'shop_name' => $like->shop->shop_name,
                        'area' => $like->shop->area->area,
                        'genre' => $like->shop->genre->genre,
                        'img' => $like->shop->img ? $like->shop->img : 'NoImage.jpg',
                        'like_id' => $like->id,
                    ];
            }
                $reservationsData = [];
                foreach ($item->reservations as $reservation) {
                    $datetime = new DateTime($reservation->datetime);
                    $date = $datetime->format('Y-m-d');
                    $time = $datetime->format('H:i');

                    $rating_id = null;
                    $rating = null;
                    $comment = null;
                    if ($reservation->rating) {
                        $rating_id = $reservation->rating->id;
                        $rating = $reservation->rating->rating;
                        $comment = $reservation->rating->comment;
                    }
                    $course = $reservation->course;
                    $shop = $course->shop;
                    $reservationsData[] = [ 
                        'id' => $reservation->id,
                        'shop' => $shop->shop_name,
                        'course_id' =>$reservation->course_id,
                        'course' => $course->course,
                        'price' => $course->price,
                        'date' => $date,
                        'time' => $time,
                        'number' => $reservation->number,
                        'rating_id' => $rating_id,
                        'rating' => $rating,
                        'comment' => $comment,
                    ];
                }
            return response()->json([
                'shops' => $shopsData,
                'reservations' => $reservationsData,
            ], 200);
        } else {
            return response()->json([
                'massage' => 'Not found'
            ], 404);
        }
    }
}

