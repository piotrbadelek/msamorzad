<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view("index");
})->middleware("auth");

Route::get('/skladki', [\App\Http\Controllers\PaymentController::class, "view"])->middleware("auth");
Route::get('/skladki/new', [\App\Http\Controllers\PaymentController::class, "createForm"])->middleware("auth");
Route::get("/skladki/{payment:id}", [\App\Http\Controllers\PaymentController::class, "details"])->middleware("auth");
Route::get("/skladki/{payment:id}/{id}", [\App\Http\Controllers\PaymentController::class, "pay"])->middleware("auth");
Route::post("/skladki/new", [\App\Http\Controllers\PaymentController::class, "create"])->middleware("auth");

Route::get("/messages", [\App\Http\Controllers\MessageController::class, "list"])->middleware("auth");
Route::post("/messages", [\App\Http\Controllers\MessageController::class, "create"])->middleware("auth");
Route::get("/messages/{message:id}", [\App\Http\Controllers\MessageController::class, "show"])->middleware("auth");
Route::patch("/messages/{message:id}", [\App\Http\Controllers\MessageController::class, "update"])->middleware("auth");

Route::get("/login", [\App\Http\Controllers\SessionController::class, "login"])->name("login");
Route::post("/login", [\App\Http\Controllers\SessionController::class, "authenticate"]);
