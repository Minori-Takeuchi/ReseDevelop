<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RatingRequest;
use App\Models\Rating;

class RatingController extends Controller
{
    public function store(RatingRequest $request)
    {
        $item = Rating::create($request->all());
        $rating = [
            'id' => $item->reservation_id,
            'rating_id' =>$item->id,
            'rating' =>$item->rating,
            'comment' =>$item->comment ? $item->comment : null,
        ];
        return response()->json([
            'rating' => $rating
        ], 201);
    }
}
