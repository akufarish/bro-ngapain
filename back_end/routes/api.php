<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ValidationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route untuk auth
Route::controller(AuthController::class)->group(function () {
    Route::post("/v1/auth/register", "doRegister");
    Route::post("/v1/auth/login", "doLogin");
});

// Route untuk Validation
Route::controller(ValidationController::class)->group(function () {
    Route::post("/v1/validation", "makeValidation");
    Route::get("/v1/validation", "getValidation");
});

// Route untuk postingan threads
Route::controller(PostController::class)->group(function () {
    Route::post("/v1/post", "makePost");
    Route::get("/v1/post", "getPost");
    Route::get("/v1/post/{post:id}", "getPostById");
});