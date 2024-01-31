<?php

namespace App\Http\Controllers;

use App\Models\Classunit;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
	public function list(Request $request) {
		return view("admin.list");
	}

	public function listPayments(Request $request) {
		$classunits = Classunit::all();
		$classunitsWithPayments = [];
		foreach ($classunits as $classunit) {
			$classunitsWithPayments[] = [
				"name" => $classunit->name,
				"payments" => Payment::where("classunit_id", $classunit->id)->get()
			];
		}

		return view("admin.payment.list", [
			"classunitsWithPayments" => $classunitsWithPayments,
			"user" => $request->user()
		]);
	}
}
