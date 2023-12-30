<?php

namespace App\Http\Controllers;

use App\Models\Classunit;
use App\Models\Message;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class ClassunitController extends Controller
{
    public function list(Request $request) {
		return view("admin.classunit.list", [
			"classunits" => Classunit::all()
		]);
	}

	public function deleteForm(Request $request, Classunit $classunit) {
		return view("admin.classunit.delete", [
			"classunit" => $classunit
		]);
	}

	public function delete(Request $request, Classunit $classunit) {
		$ghost_user = User::where("username", "ghost")->first();
		$users = User::where("classunit_id", $classunit->id)->get();

		foreach ($users as $user) {
			$messages = Message::where("user_id", $user->id)->get();
			foreach ($messages as $message) {
				$message->user_id = $ghost_user->id;
				$message->save();
			}
			$user->delete();
		}

		$payments = Payment::where("classunit_id", $classunit->id)->get();
		foreach ($payments as $payment) {
			$payment->delete();
		}

		$classunit->deleteOrFail();

		return redirect("/admin/classunit");
	}
}
