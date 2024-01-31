<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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

	public function editStudent(Request $request, User $user) {
		$requestUser = $request->user();

		if (!$user->isTutor || $user->classunit_id !== $requestUser->classunit_id) {
			abort(403);
		}
	}
}
