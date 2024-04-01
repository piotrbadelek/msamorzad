<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utilities\UserPurge;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TutorController extends Controller
{
	public function listStudents(Request $request)
	{
		$user = $request->user();

		$users = User::whereNot("type", "nauczyciel")->where("classunit_id", $user->classunit_id)->get();

		return view("tutor.student.list", [
			"users" => $users
		]);
	}

	public function studentDetails(Request $request, User $user)
	{
		$requestUser = $request->user();

		if ($user->classunit_id !== $requestUser->classunit_id) {
			abort(403);
		}

		return view("tutor.student.show", [
			"user" => $user
		]);
	}

	public function studentResetPassword(Request $request, User $user)
	{
		$requestUser = $request->user();

		if ($user->classunit_id !== $requestUser->classunit_id) {
			abort(403);
		}

		$temporaryPassword = Str::password(10, true, true, false);
		$user->update([
			"password" => Hash::make($temporaryPassword),
			"hasNotChangedPassword" => true
		]);

		return view("tutor.student.password_changed", [
			"temporaryPassword" => $temporaryPassword,
			"user" => $user
		]);
	}

	public function updateStudentForm(Request $request, User $user)
	{
		$requestUser = $request->user();

		if ($user->classunit_id !== $requestUser->classunit_id) {
			abort(403);
		}

		return view("tutor.student.update", [
			"user" => $user
		]);
	}

	public function updateStudent(Request $request, User $user)
	{
		$requestUser = $request->user();
		if ($user->classunit_id !== $requestUser->classunit_id) {
			abort(403);
		}

		$data = $request->validate([
			"username" => ["required", "min:3", "max:128", "unique:users"],
			"name" => ["required", "min:3", "max:128"],
			"type" => ["required"]
		]);

		if ($data["type"] == "nauczyciel") {
			abort(400);
			// Teachers cannot add teachers
		}

		$user->username = $data["username"];
		$user->name = $data["name"];
		$user->type = $data["type"];
		$user->saveOrFail();

		return redirect("/tutor/students/" . $user->id);
	}

	public function deleteStudentForm(Request $request, User $user)
	{
		$requestUser = $request->user();
		if ($user->classunit_id !== $requestUser->classunit_id) {
			abort(403);
		}

		return view("tutor.student.delete", [
			"user" => $user
		]);
	}

	public function deleteUser(Request $request, User $user)
	{
		$requestUser = $request->user();
		if ($user->classunit_id !== $requestUser->classunit_id) {
			abort(403);
		}

		if ($user->id == $requestUser->id) {
			abort(400);
		}

		$ghost_user = User::where("username", "ghost")->first();

		UserPurge::deleteMessages($user, $ghost_user);
		UserPurge::removeFromPayments($user, $ghost_user);
		UserPurge::deleteValentineMessages($user);

		$user->deleteOrFail();

		return redirect("/tutor/students");
	}

	public function createStudentForm()
	{
		return view("tutor.student.create");
	}

	public function createStudent(Request $request)
	{
		$requestUser = $request->user();

		$data = $request->validate([
			"username" => ["required", "min:3", "max:128", "unique:users"],
			"name" => ["required", "min:3", "max:128"],
			"type" => ["required"]
		]);

		if ($data["type"] == "nauczyciel") {
			abort(400);
			// Teachers cannot add teachers
		}

		$user = new User();

		$temporaryPassword = Str::password(10, true, true, false);
		$user->password = Hash::make($temporaryPassword);

		$user->username = $data["username"];
		$user->name = $data["name"];
		$user->classunit_id = $requestUser->classunit_id;
		$user->type = $data["type"];
		$user->samorzadType = "student";
		$user->saveOrFail();

		return view("tutor.student.created", [
			"user" => $user,
			"temporaryPassword" => $temporaryPassword
		]);
	}

	public function studentPaymentStats(Request $request)
	{
		$user = $request->user();

		$users = User::whereNot("type", "nauczyciel")->where("classunit_id", $user->classunit_id)->orderBy("total_late_days", "DESC")->get();

		return view("tutor.late_days_stats", [
			"users" => $users
		]);
	}

	public function studentPaymentStatsPDF(Request $request)
	{
		$user = $request->user();

		$users = User::whereNot("type", "nauczyciel")->where("classunit_id", $user->classunit_id)->orderBy("total_late_days", "DESC")->get();

		$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("pdf.late_students", [
			"classunit_name" => $user->classunit->name,
			"users" => $users
		]);

		$date = new DateTime();

		return $pdf->download("late_students_" . $date->format("Ymd\THis") . ".pdf");
	}
}
