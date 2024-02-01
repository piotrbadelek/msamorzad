<?php

namespace App\Http\Controllers;

use App\Models\Classunit;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TutorController extends Controller
{
    public function listStudents(Request $request) {
		$user = $request->user();

		if (!$user->isTutor) {
			abort(403);
		}

		$users = User::whereNot("type", "nauczyciel")->where("classunit_id", $user->classunit_id)->get();

		return view("tutor.student.list", [
			"users" => $users
		]);
	}

	public function studentDetails(Request $request, User $user) {
		$requestUser = $request->user();

		if (!$requestUser->isTutor || $user->classunit_id !== $requestUser->classunit_id) {
			abort(403);
		}

		return view("tutor.student.show", [
			"user" => $user
		]);
	}

	public function studentResetPassword(Request $request, User $user) {
		$requestUser = $request->user();

		if (!$requestUser->isTutor || $user->classunit_id !== $requestUser->classunit_id) {
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

	public function updateStudentForm(Request $request, User $user) {
		$requestUser = $request->user();

		if (!$requestUser->isTutor || $user->classunit_id !== $requestUser->classunit_id) {
			abort(403);
		}

		return view("tutor.student.update", [
			"user" => $user
		]);
	}

	public function updateStudent(Request $request, User $user) {
		$requestUser = $request->user();
		if (!$requestUser->isTutor || $user->classunit_id !== $requestUser->classunit_id) {
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

	public function deleteStudentForm(Request $request, User $user) {
		$requestUser = $request->user();
		if (!$requestUser->isTutor || $user->classunit_id !== $requestUser->classunit_id) {
			abort(403);
		}

		return view("tutor.student.delete", [
			"user" => $user
		]);
	}

	public function deleteUser(Request $request, User $user) {
		$requestUser = $request->user();
		if (!$requestUser->isTutor || $user->classunit_id !== $requestUser->classunit_id) {
			abort(403);
		}

		if ($user->id == $requestUser->id) {
			abort(400);
		}

		$ghost_user = User::where("username", "ghost")->first();
		$messages = Message::where("user_id", $user->id)->get();
		foreach ($messages as $message) {
			$message->user_id = $ghost_user->id;
			$message->save();
		}

		$user->deleteOrFail();

		return redirect("/tutor/students");
	}

	public function createStudentForm(Request $request) {
		$user = $request->user();

		if (!$user->isTutor) {
			abort(403);
		}

		return view("tutor.student.create");
	}

	public function createStudent(Request $request) {
		$requestUser = $request->user();
		if (!$requestUser->isTutor) {
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
}
