<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PaymentController;

Route::apiResource('/shop', ShopController::class)->only(['index', 'show']);
Route::apiResource('/reserve', ReservationController::class)->only(['store', 'destroy','update']);
Route::apiResource('/mypage', MypageController::class)->only(['show']);
Route::apiResource('/user', AuthController::class)->only(['store','show']);
Route::apiResource('/rating', RatingController::class)->only(['store']);
Route::apiResource('/admin', AdministrationController::class)->only(['index']);
Route::apiResource('/manager', ManagementController::class)->only(['show', 'store']);
Route::apiResource('/like', LikeController::class)->only(['store', 'destroy']);

Route::post('/likes', [LikeController::class, 'search']);
Route::post('/mail', [MailController::class,'sendmail']);
Route::post('/payment', [PaymentController::class, 'charge']);