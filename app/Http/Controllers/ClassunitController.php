<?php

namespace App\Http\Controllers;

use App\Models\Classunit;
use App\Models\Payment;
use App\Models\User;
use App\Utilities\UserPurge;
use Illuminate\Http\Request;

class ClassunitController extends Controller
{
	public function list()
	{
		return view("admin.classunit.list", [
			"classunits" => Classunit::all()
		]);
	}

	public function deleteForm(Classunit $classunit)
	{
		return view("admin.classunit.delete", [
			"classunit" => $classunit
		]);
	}

	public function delete(Classunit $classunit)
	{
		$ghost_user = User::where("username", "ghost")->first();
		$users = User::where("classunit_id", $classunit->id)->get();

		foreach ($users as $user) {
			UserPurge::deleteMessages($user, $ghost_user);
			UserPurge::removeFromPayments($user, $ghost_user);
			UserPurge::deleteValentineMessages($user);
		}

		$payments = Payment::where("classunit_id", $classunit->id)->get();
		foreach ($payments as $payment) {
			$payment->delete();
		}

		$classunit->deleteOrFail();

		return redirect("/admin/classunit");
	}

	public function createForm()
	{
		return view("admin.classunit.create");
	}

	public function create(Request $request)
	{
		$data = $request->validate([
			"name" => ["required", "min:4", "max:8"]
		]);

		$classunit = new Classunit;
		$classunit->name = $data["name"];
		$classunit->save();

		return redirect("/admin/classunit");
	}
}
