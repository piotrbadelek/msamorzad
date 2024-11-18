<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
	public function home(Request $request)
	{
		$user = $request->user();
		$payments = $user->classUnit->payments;
		$unpaidPayments = 0;
		$latePayments = 0;
		foreach ($payments as $payment) {
			if (!$payment->inTrash) {
				if (!in_array($user->id, json_decode($payment->paid))) {
					$unpaidPayments++;
					if ($payment->is_late) {
						$latePayments++;
					}
				}
			}
		}

		return view("index", [
			"user" => $user,
			"unpaidPayments" => $unpaidPayments,
			"latePayments" => $latePayments
		]);
	}
}
