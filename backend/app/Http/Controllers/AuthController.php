<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    // ユーザー新規登録
    public function store(RegisterRequest $request)
    {
        $user = User::create($request->all());
        if($user) {
            return response()->json([
                'user' => $user,
                'message' => 'registration successfully'
            ], 201);
        } else {
            return response()->json([
                'massage' => 'Not found'
            ], 404);
        }
    }

    public function show($id) 
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Not found'
            ], 400);
        } else {
            return response()->json([
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ], 200);
        }
    }
}
