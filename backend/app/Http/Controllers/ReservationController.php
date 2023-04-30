<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;

class ReservationController extends Controller
{

    public function store(ReservationRequest $request)
    {
        $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

        if (Reservation::where('user_id', $request->user_id)
        ->where('course_id', $request->course_id)
        ->where('datetime', $datetime)
        ->exists()) {
            return response()->json([
                'message' => 'Duplicate reservation',
            ],400);
        }

        $item = Reservation::create([
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
            'datetime' => $datetime,
            'number' => $request->number,
        ]);
        return response()->json([
            'data' => $item
        ],201);
    }
    public function destroy($id)
    {
        $item = Reservation::where('id', $id)->delete();
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
    public function update(ReservationRequest $request)
    {
        $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);
        $form =  [
            'datetime' => $datetime,
            'number' => $request->number
        ];
        $item = Reservation::where('id',$request->id)->update($form);
        if ($item) {
            return response()->json([
                'message' => 'updated successfully'
            ],200);
        } else {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }
    }
}
