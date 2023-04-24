<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LikeRequest;
use App\Models\Like;

class LikeController extends Controller
{
    public function store(LikeRequest $request)
    {
        $item = Like::create($request->all());
        return response()->json([
            'data' => $item
        ], 201);
    }

    public function destroy(Like $like)
    {
        $item = Like::where('id', $like->id)->delete();
        if ($item) {
            return response()->json([
                'message' => 'Deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }
    }

    public function search(Request $request)
    {
        $userId = $request->user_id;
        $likes = Like::where('user_id',$userId)
                    ->get();
        return response()->json([
            'likes' => $likes
        ],200);
    }
}
