<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ShopRequest;
use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Course;
use DateTime;
use Illuminate\Support\Facades\Storage;

class ManagementController extends Controller
{
    public function show($id)
    {
        $areas = Area::all();
        $genres = Genre::all();
        $manager = User::with(['shops.courses.reservations.user'])->find($id);
        if($manager) {
            $managerData = [
                'id' => $manager->id,
                'name' => $manager->name,
            ];
            $shopsData = [];
            foreach ($manager->shops as $shop) {
                foreach($shop->courses as $course) {
                    $reservationsData = [];
                    foreach ($course->reservations as $reservation) {
                        $courseData = Course::find($reservation->course_id);

                        $datetime = new DateTime($reservation->datetime);
                        $date = $datetime->format('Y-m-d');
                        $time = $datetime->format('H:i');
                        $reservationsData[] = [
                            'id' => $reservation->id,
                            'date' => $date,
                            'time' => $time,
                            'number' => $reservation->number,
                            'user_name' => $reservation->user->name,
                            'course' => $courseData->course,
                        ];
                    }
                }
                $shopsData[] = [
                    'id' => $shop->id,
                    'shop_name' => $shop->shop_name,
                    'reservations' => $reservationsData,
                ];
            }
            return response()->json([
                'manager' => $managerData,
                'shops' => $shopsData,
                'areas' => $areas,
                'genres' => $genres,
            ], 200);
        } else {
            return response()->json([
                'massage' => 'Not found'
            ], 404);
        }
    }
    public function store(ShopRequest $request)
    {
        $img = $request->file('img');
        $imgName = null;
        if($img) {
            $imgName = $img->getClientOriginalName();
        }
        $data = Shop::create([
            'shop_name' => $request->shop_name,
            'area_id' => $request->area_id,
            'genre_id' => $request->genre_id,
            'manager_id' => $request->manager_id,
            'content' => $request->content,
            'img' => $imgName ?? null
        ]);
        $course = Course::create([
            'shop_id' => $data->id,
            'course' => '席のみ',
            'price' => 0,
        ]);
        if($data && $course) {
            if($img) {
                $img->storeAs('/uploads', $imgName ,'s3');
            }
            return response()->json([
                'shop' => $data
            ], 201);
        } else {
            return response()->json([
                'massage' => 'Not found'
            ], 422);
        }
    }
}
