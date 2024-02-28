<?php

namespace App\Http\Controllers;

use App\Models\Classunit;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{

	public function list(Request $request)
	{
		$users = User::whereNot("username", "ghost")->get();

		return view("admin.user.list", [
			"users" => $users
		]);
	}

	public function show(Request $request, User $user)
	{
		return view("admin.user.show", [
			"user" => $user
		]);
	}

	public function deleteForm(Request $request, User $user)
	{
		return view("admin.user.delete", [
			"user" => $user
		]);
	}

	public function delete(Request $request, User $user)
	{
		$ghost_user = User::where("username", "ghost")->first();
		$messages = Message::where("user_id", $user->id)->get();
		foreach ($messages as $message) {
			$message->user_id = $ghost_user->id;
			$message->save();
		}

		$user->deleteOrFail();

		return redirect("/admin/user");
	}

	public function adminResetPassword(Request $request, User $user)
	{
		$temporaryPassword = Str::password(10, true, true, false);
		$user->update([
			"password" => Hash::make($temporaryPassword),
			"hasNotChangedPassword" => true
		]);

		return view("admin.user.password_changed", [
			"temporaryPassword" => $temporaryPassword,
			"user" => $user
		]);
	}

	public function updateForm(Request $request, User $user)
	{
		return view("admin.user.update", [
			"user" => $user,
			"classunits" => Classunit::all()
		]);
	}

	public function update(Request $request, User $user)
	{
		$validateUsernameUniqueness = true;
		$uniquenessRequirement = "unique:users";

		if ($request->has("username") && $user->username === $request->post("username")) {
			$validateUsernameUniqueness = false;
		}

		if (!$validateUsernameUniqueness) {
			$uniquenessRequirement = "";
		}

		$data = $request->validate([
			"username" => ["required", "min:3", "max:128", $uniquenessRequirement],
			"name" => ["required", "min:3", "max:128"],
			"classunit_id" => ["required"],
			"type" => ["required"],
			"samorzadType" => ["required"]
		]);

		$this->updateUserAttributes($user, $data);

		return redirect("/admin/user/" . $user->id);
	}

	public function createForm(Request $request)
	{
		return view("admin.user.create", [
			"classunits" => Classunit::all()
		]);
	}

	public function create(Request $request)
	{
		$data = $request->validate([
			"username" => ["required", "min:3", "max:128", "unique:users"],
			"name" => ["required", "min:3", "max:128"],
			"classunit_id" => ["required"],
			"type" => ["required"],
			"samorzadType" => ["required"]
		]);

		$user = new User();

		$isTeacher = $data["type"] == "nauczyciel" || $data["samorzadType"] == "nauczyciel";
		$belongsToAClass = Classunit::where("id", $data["classunit_id"])->first()->name != "Bez klasy";

		if ($isTeacher && $belongsToAClass) {
			$user->notManagingAClass = false;
		}

		$temporaryPassword = Str::password(10, true, true, false);
		$user->password = Hash::make($temporaryPassword);

		$this->updateUserAttributes($user, $data);

		return view("admin.user.created", [
			"user" => $user,
			"temporaryPassword" => $temporaryPassword
		]);
	}

	protected function updateUserAttributes(User $user, array $data)
	{
		$user->username = $data["username"];
		$user->name = $data["name"];
		$user->classunit_id = $data["classunit_id"];
		$user->type = $data["type"];
		$user->samorzadType = $data["samorzadType"];
		$user->saveOrFail();
	}
}
