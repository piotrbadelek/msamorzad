<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ClassunitController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

	Route::get("/login", [SessionController::class, "login"])->name("login");
	Route::post("/login", [SessionController::class, "authenticate"]);
	Route::get("/change-password", [SessionController::class, "changePassword"])->middleware("auth");
	Route::post("/change-password", [SessionController::class, "update"])->middleware("auth");
	Route::post("/logout", [SessionController::class, "logout"])->middleware("auth");

	Route::middleware(["auth", "password.changed"])->group(function() {
		Route::get('/', function (Request $request) {
			if ($request->user()->hasNotChangedPassword) {
				return redirect("/change-password?changingForFirstTime=true");
			}
			return view("index", [
				"user" => $request->user()
			]);
		});

		Route::get('/skladki', [PaymentController::class, "view"]);
		Route::get('/skladki/new', [PaymentController::class, "createForm"]);
		Route::get("/skladki/{payment:id}", [PaymentController::class, "details"]);
		Route::get("/skladki/{payment:id}/delete", [PaymentController::class, "deleteForm"]);
		Route::get("/skladki/{payment:id}/pdf", [PaymentController::class, "generatePaymentConfirmation"]);
		Route::get("/skladki/{payment:id}/trash", [PaymentController::class, "movePaymentToTrash"]);
		Route::get("/skladki/{payment:id}/{id}", [PaymentController::class, "pay"]);
		Route::post("/skladki/new", [PaymentController::class, "create"]);
		Route::delete("/skladki/{payment:id}", [PaymentController::class, "delete"]);

		Route::get("/messages", [MessageController::class, "list"]);
		Route::post("/messages", [MessageController::class, "create"]);
		Route::get("/messages/{message:id}", [MessageController::class, "show"]);
		Route::patch("/messages/{message:id}", [MessageController::class, "update"]);
		Route::get("/messages/{message:id}/delete", [MessageController::class, "deleteForm"]);
		Route::delete("/messages/{message:id}", [MessageController::class, "delete"]);

		Route::get("/contests", [ContestController::class, "list"]);
		Route::get("/contests/new", [ContestController::class, "createForm"]);
		Route::post("/contests/new", [ContestController::class, "create"]);
		Route::get("/contests/{contest:id}", [ContestController::class, "show"]);
		Route::get("/contests/{contest:id}/delete", [ContestController::class, "deleteForm"]);
		Route::get("/contests/{contest:id}/enlist", [ContestController::class, "enlist"]);
		Route::delete("/contests/{contest:id}", [ContestController::class, "delete"]);

		Route::get("/announcements", [AnnouncementController::class, "list"]);
		Route::get("/announcements/new", [AnnouncementController::class, "createForm"]);
		Route::post("/announcements/new", [AnnouncementController::class, "create"]);
		Route::get("/announcements/{announcement:id}/delete", [AnnouncementController::class, "deleteForm"]);
		Route::delete("/announcements/{announcement:id}", [AnnouncementController::class, "delete"]);

		Route::post("/push", [NotificationController::class, "subscribe"]);

		Route::view("/confirm-password", "confirm_password")->name("password.confirm");
		Route::post('/confirm-password', function (Request $request) {
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

	Route::get("/tutor/students", [\App\Http\Controllers\TutorController::class, "listStudents"]);
	Route::get("/tutor/students/{user:id}", [\App\Http\Controllers\TutorController::class, "editStudent"]);

	Route::middleware(["auth", "admin", "password.changed"])->group(function() {
		Route::get("/admin", [AdminController::class, "list"]);
		Route::get("/admin/user", [UserController::class, "list"]);
		Route::get("/admin/user/new", [UserController::class, "createForm"]);
		Route::post("/admin/user/", [UserController::class, "create"]);
		Route::get("/admin/user/{user:id}", [UserController::class, "show"]);
		Route::get("/admin/user/{user:id}/delete", [UserController::class, "deleteForm"]);
		Route::delete("/admin/user/{user:id}", [UserController::class, "delete"]);
		Route::get("/admin/user/{user:id}/reset_password", [UserController::class, "adminResetPassword"]);
		Route::get("/admin/user/{user:id}/update", [UserController::class, "updateForm"]);
		Route::patch("/admin/user/{user:id}", [UserController::class, "update"]);

		Route::get("/admin/classunit", [ClassunitController::class, "list"]);
		Route::get("/admin/classunit/{classunit:id}", [ClassunitController::class, "deleteForm"])->middleware(["password.confirm"]);
		Route::delete("/admin/classunit/{classunit:id}", [ClassunitController::class, "delete"])->middleware(["password.confirm"]);

		Route::get("/admin/payments", [AdminController::class, "listPayments"]);
	});
});

