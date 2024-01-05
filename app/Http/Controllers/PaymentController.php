<?php

namespace App\Http\Controllers;

use App\Models\Classunit;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
	public function view(Request $request)
	{
		if ($request->user()->isTeacher && $request->user()->notManagingAClass) {
			abort(403);
		}

		$payments = Payment::where("classunit_id", $request->user()->classunit_id)->get();
		return view("payment.list", [
			"payments" => $payments,
			"user" => $request->user()
		]);
	}

	public function details(Payment $payment, Request $request)
	{
		if ($request->user()->cannot("details", $payment)) {
			abort(403);
		}

		$not_paid = User::whereNotIn("id", json_decode($payment->paid))
			->where('type', '!=', 'nauczyciel')
			->where("classunit_id", "=", $payment->classunit_id)->get();
		$paid = json_decode($payment->paid);

		// $not_paid is a Collection, $paid is an array.
		$not_paid = $not_paid->toArray();
		$name = array_column($not_paid, "name");
		array_multisort($name, SORT_ASC, $not_paid);
		sort($paid);

		return view("payment.show", [
			"payment" => $payment,
			"not_paid" => $not_paid,
			"paid" => $paid
		]);
	}

	public function pay(Payment $payment, int $userid, Request $request)
	{
		if ($request->user()->cannot("pay", $payment)) {
			abort(403);
		}

		$paymentPaid = json_decode($payment->paid);
		if (in_array($userid, $paymentPaid)) {
			$key = array_search($userid, $paymentPaid);
			array_splice($paymentPaid, $key, 1);
		} else {
			$paymentPaid[] = $userid;
		}
		$payment->update(["paid" => json_encode($paymentPaid)]);
		$payment->save();
		return redirect()->back();
	}

	public function createForm(Request $request)
	{
		if ($request->user()->cannot("create", Payment::class)) {
			abort(403);
		}

		return view("payment.create", [
			"classUnitSize" => User::count("classunit_id", $request->user()->classunit_id)
		]);
	}

	public function deleteForm(Request $request, Payment $payment)
	{
		if ($request->user()->cannot("delete", $payment)) {
			abort(403);
		}

		return view("payment.delete", [
			"payment" => $payment
		]);
	}

	public function create(Request $request)
	{
		if ($request->user()->cannot("create", Payment::class)) {
			abort(403);
		}

		$data = $request->validate([
			"money" => ["required", "numeric", "max:999"],
			"title" => ["required", "min:3", "max:80"],
			"deadline" => ["required", "date", "after:tomorrow"]
		]);

		$payment = new Payment;
		$payment->amount = $data["money"];
		$payment->title = $data["title"];
		$payment->deadline = $data["deadline"];
		$payment->classunit_id = $request->user()->classunit_id;
		$payment->paid = "[]";
		$payment->save();

		$users = $request->user()->classUnit->users;
		Notification::sendNow($users, new PaymentCreated($payment->title));

		return redirect("/skladki/" . $payment->id);
	}

	public function delete(Request $request, Payment $payment)
	{
		if ($request->user()->cannot("delete", $payment)) {
			abort(403);
		}

		$payment->delete();
		return redirect("/skladki/");
	}

	public function generatePaymentConfirmation(Payment $payment, Request $request)
	{
		// TODO: Refactor duplicated code fragment into a method
		if ($request->user()->cannot("details", $payment)) {
			abort(403);
		}


		$not_paid = User::whereNotIn("id", json_decode($payment->paid))
			->where('type', '!=', 'nauczyciel')
			->where("classunit_id", "=", $payment->classunit_id)->get();
		$paid = json_decode($payment->paid);

		// $not_paid is a Collection, $paid is an array.
		$not_paid = $not_paid->toArray();
		$name = array_column($not_paid, "name");
		array_multisort($name, SORT_ASC, $not_paid);
		sort($paid);

		$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("pdf.payment_close", [
			"payment" => $payment,
			"not_paid" => $not_paid,
			"paid" => $paid
		]);

		return $pdf->download("payment_closed_" . Str::of($payment->title)->slug() . ".pdf");
	}
}
