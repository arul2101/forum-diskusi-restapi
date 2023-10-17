<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post("/users", [UserController::class, "register"]);
Route::post("/users/login", [UserController::class, "login"]);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
  Route::get("/users/current", [UserController::class, "get"]);
  Route::patch("/users/current", [UserController::class, "update"]);
  Route::delete("/users/logout", [UserController::class, "logout"]);

  Route::post("/posts", [PostController::class, "create"]);
  Route::get("/posts/{id}", [PostController::class, "get"])->where("id", "[0-9]+");
  Route::get("/posts", [PostController::class, "search"]);
  Route::put("/posts/{id}", [PostController::class, "update"])->where("id", "[0-9]+");
  Route::delete("/posts/{id}", [PostController::class, "delete"])->where("id", "[0-9]+");

  Route::post("/posts/{idPost}/comments", [CommentController::class, "create"])->where("idPost", "[0-9]+");
  Route::get("/posts/{idPost}/comments/{idComment}", [CommentController::class, "get"])
    ->where("idPost", "[0-9]+")
    ->where("idComment", "[0-9]+");
  Route::patch("/posts/{idPost}/comments/{idComment}", [CommentController::class, "update"])
    ->where("idPost", "[0-9]+")
    ->where("idComment", "[0-9]+");
  Route::delete("/posts/{idPost}/comments/{idComment}", [CommentController::class, "delete"])
  ->where("idPost", "[0-9]+")
  ->where("idComment", "[0-9]+");
  Route::get("/posts/{idPost}/comments", [CommentController::class, "search"])
  ->where("idPost", "[0-9]+");
});
