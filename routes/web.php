<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------y------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function (\Illuminate\Http\Request $request) {
	if ($request->user()->hasNotChangedPassword) {
		return redirect("/change-password?changingForFirstTime=true");
	}
    return view("index", [
		"user" => $request->user()
	]);
})->middleware("auth");
Route::get("/future", function () {
	return redirect("/future_ad.mp4");
});

Route::get('/skladki', [\App\Http\Controllers\PaymentController::class, "view"])->middleware("auth");
Route::get('/skladki/new', [\App\Http\Controllers\PaymentController::class, "createForm"])->middleware("auth");
Route::get("/skladki/{payment:id}", [\App\Http\Controllers\PaymentController::class, "details"])->middleware("auth");
Route::get("/skladki/{payment:id}/delete", [\App\Http\Controllers\PaymentController::class, "deleteForm"])->middleware("auth");
Route::get("/skladki/{payment:id}/{id}", [\App\Http\Controllers\PaymentController::class, "pay"])->middleware("auth");
Route::post("/skladki/new", [\App\Http\Controllers\PaymentController::class, "create"])->middleware("auth");
Route::delete("/skladki/{payment:id}", [\App\Http\Controllers\PaymentController::class, "delete"])->middleware("auth");

Route::get("/messages", [\App\Http\Controllers\MessageController::class, "list"])->middleware("auth");
Route::post("/messages", [\App\Http\Controllers\MessageController::class, "create"])->middleware("auth");
Route::get("/messages/{message:id}", [\App\Http\Controllers\MessageController::class, "show"])->middleware("auth");
Route::patch("/messages/{message:id}", [\App\Http\Controllers\MessageController::class, "update"])->middleware("auth");
Route::get("/messages/{message:id}/delete", [\App\Http\Controllers\MessageController::class, "deleteForm"])->middleware("auth");
Route::delete("/messages/{message:id}", [\App\Http\Controllers\MessageController::class, "delete"])->middleware("auth");

Route::get("/contests", [\App\Http\Controllers\ContestController::class, "list"])->middleware("auth");
Route::get("/contests/new", [\App\Http\Controllers\ContestController::class, "createForm"])->middleware("auth");
Route::post("/contests/new", [\App\Http\Controllers\ContestController::class, "create"])->middleware("auth");
Route::get("/contests/{contest:id}", [\App\Http\Controllers\ContestController::class, "show"])->middleware("auth");
Route::get("/contests/{contest:id}/delete", [\App\Http\Controllers\ContestController::class, "deleteForm"])->middleware("auth");
Route::get("/contests/{contest:id}/enlist", [\App\Http\Controllers\ContestController::class, "enlist"])->middleware("auth");
Route::delete("/contests/{contest:id}", [\App\Http\Controllers\ContestController::class, "delete"])->middleware("auth");

Route::get("/announcements", [\App\Http\Controllers\AnnouncementController::class, "list"])->middleware("auth");
Route::get("/announcements/new", [\App\Http\Controllers\AnnouncementController::class, "createForm"])->middleware("auth");
Route::post("/announcements/new", [\App\Http\Controllers\AnnouncementController::class, "create"])->middleware("auth");
Route::get("/announcements/{announcement:id}/delete", [\App\Http\Controllers\AnnouncementController::class, "deleteForm"])->middleware("auth");
Route::delete("/announcements/{announcement:id}", [\App\Http\Controllers\AnnouncementController::class, "delete"])->middleware("auth");

Route::get("/login", [\App\Http\Controllers\SessionController::class, "login"])->name("login");
Route::get("/change-password", [\App\Http\Controllers\SessionController::class, "changePassword"]);
Route::post("/change-password", [\App\Http\Controllers\SessionController::class, "update"]);
Route::post("/logout", [\App\Http\Controllers\SessionController::class, "logout"]);
Route::post("/login", [\App\Http\Controllers\SessionController::class, "authenticate"]);

Route::post("/push", [\App\Http\Controllers\NotificationController::class, "subscribe"]);
