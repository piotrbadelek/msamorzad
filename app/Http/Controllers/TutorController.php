<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function listStudents(Request $request) {
		$user = $request->user();

		if (!$user->isTeacher) {
			abort(403);
		}

		$users = User::whereNot("username", "ghost")->where("classunit_id", $user->classunit_id)->get();

		return view("admin.user.list", [
			"users" => $users
		]);
	}
}
