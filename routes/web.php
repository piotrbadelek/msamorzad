<?php

use Illuminate\Support\Facades\Hash;
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

Route::middleware(["security.headers"])->group(function() {

	Route::get("/future", function () {
		return redirect("/future_ad.mp4");
	});

	Route::get("/login", [\App\Http\Controllers\SessionController::class, "login"])->name("login");
	Route::post("/login", [\App\Http\Controllers\SessionController::class, "authenticate"]);
	Route::get("/change-password", [\App\Http\Controllers\SessionController::class, "changePassword"])->middleware("auth");
	Route::post("/change-password", [\App\Http\Controllers\SessionController::class, "update"])->middleware("auth");
	Route::post("/logout", [\App\Http\Controllers\SessionController::class, "logout"])->middleware("auth");

	Route::middleware(["auth", "password.changed"])->group(function() {
		Route::get('/', function (\Illuminate\Http\Request $request) {
			if ($request->user()->hasNotChangedPassword) {
				return redirect("/change-password?changingForFirstTime=true");
			}
			return view("index", [
				"user" => $request->user()
			]);
		});

		Route::get('/skladki', [\App\Http\Controllers\PaymentController::class, "view"]);
		Route::get('/skladki/new', [\App\Http\Controllers\PaymentController::class, "createForm"]);
		Route::get("/skladki/{payment:id}", [\App\Http\Controllers\PaymentController::class, "details"]);
		Route::get("/skladki/{payment:id}/delete", [\App\Http\Controllers\PaymentController::class, "deleteForm"]);
		Route::get("/skladki/{payment:id}/pdf", [\App\Http\Controllers\PaymentController::class, "generatePaymentConfirmation"]);
		Route::get("/skladki/{payment:id}/{id}", [\App\Http\Controllers\PaymentController::class, "pay"]);
		Route::post("/skladki/new", [\App\Http\Controllers\PaymentController::class, "create"]);
		Route::delete("/skladki/{payment:id}", [\App\Http\Controllers\PaymentController::class, "delete"]);

		Route::get("/messages", [\App\Http\Controllers\MessageController::class, "list"]);
		Route::post("/messages", [\App\Http\Controllers\MessageController::class, "create"]);
		Route::get("/messages/{message:id}", [\App\Http\Controllers\MessageController::class, "show"]);
		Route::patch("/messages/{message:id}", [\App\Http\Controllers\MessageController::class, "update"]);
		Route::get("/messages/{message:id}/delete", [\App\Http\Controllers\MessageController::class, "deleteForm"]);
		Route::delete("/messages/{message:id}", [\App\Http\Controllers\MessageController::class, "delete"]);

		Route::get("/contests", [\App\Http\Controllers\ContestController::class, "list"]);
		Route::get("/contests/new", [\App\Http\Controllers\ContestController::class, "createForm"]);
		Route::post("/contests/new", [\App\Http\Controllers\ContestController::class, "create"]);
		Route::get("/contests/{contest:id}", [\App\Http\Controllers\ContestController::class, "show"]);
		Route::get("/contests/{contest:id}/delete", [\App\Http\Controllers\ContestController::class, "deleteForm"]);
		Route::get("/contests/{contest:id}/enlist", [\App\Http\Controllers\ContestController::class, "enlist"]);
		Route::delete("/contests/{contest:id}", [\App\Http\Controllers\ContestController::class, "delete"]);

		Route::get("/announcements", [\App\Http\Controllers\AnnouncementController::class, "list"]);
		Route::get("/announcements/new", [\App\Http\Controllers\AnnouncementController::class, "createForm"]);
		Route::post("/announcements/new", [\App\Http\Controllers\AnnouncementController::class, "create"]);
		Route::get("/announcements/{announcement:id}/delete", [\App\Http\Controllers\AnnouncementController::class, "deleteForm"]);
		Route::delete("/announcements/{announcement:id}", [\App\Http\Controllers\AnnouncementController::class, "delete"]);

		Route::post("/push", [\App\Http\Controllers\NotificationController::class, "subscribe"]);

		Route::view("/confirm-password", "confirm_password")->name("password.confirm");
		Route::post('/confirm-password', function (\Illuminate\Http\Request $request) {
			if (!Hash::check($request->password, $request->user()->password)) {
				return back()->withErrors([
					'password' => ['Podane hasło się nie zgadza.']
				]);
			}

			$request->session()->passwordConfirmed();

			return redirect()->intended();
		})->middleware(['auth', 'throttle:6,1']);

		Route::view("/oobe", "oobe");
		Route::view("/about", "about");
	});

	Route::middleware(["auth", "admin", "password.changed"])->group(function() {
		Route::get("/admin", [\App\Http\Controllers\AdminController::class, "list"]);
		Route::get("/admin/user", [\App\Http\Controllers\UserController::class, "list"]);
		Route::get("/admin/user/new", [\App\Http\Controllers\UserController::class, "createForm"]);
		Route::post("/admin/user/", [\App\Http\Controllers\UserController::class, "create"]);
		Route::get("/admin/user/{user:id}", [\App\Http\Controllers\UserController::class, "show"]);
		Route::get("/admin/user/{user:id}/delete", [\App\Http\Controllers\UserController::class, "deleteForm"]);
		Route::delete("/admin/user/{user:id}", [\App\Http\Controllers\UserController::class, "delete"]);
		Route::get("/admin/user/{user:id}/reset_password", [\App\Http\Controllers\UserController::class, "adminResetPassword"]);
		Route::get("/admin/user/{user:id}/update", [\App\Http\Controllers\UserController::class, "updateForm"]);
		Route::patch("/admin/user/{user:id}", [\App\Http\Controllers\UserController::class, "update"]);

		Route::get("/admin/classunit", [\App\Http\Controllers\ClassunitController::class, "list"]);
		Route::get("/admin/classunit/{classunit:id}", [\App\Http\Controllers\ClassunitController::class, "deleteForm"])->middleware(["password.confirm"]);
		Route::delete("/admin/classunit/{classunit:id}", [\App\Http\Controllers\ClassunitController::class, "delete"])->middleware(["password.confirm"]);
	});
});

