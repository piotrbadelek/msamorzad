<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
	public function view(Request $request)
	{
		$user = $request->user();
		if ($user->isTeacher && $user->notManagingAClass) {
			abort(403);
		}

		$payments = Payment::where("classunit_id", $user->classunit_id)->where("inTrash", false)->get();

		$userIsSamorzadKlasowy = $user->isSamorzadKlasowy;

		if ($userIsSamorzadKlasowy) {
			$paymentsInTrash = Payment::where("classunit_id", $user->classunit_id)->where("inTrash", true)->get();
		} else {
			$payments = $payments->filter(fn($payment) => !in_array($user->id, $payment->excluded_students_array));
		}

		return view("payment.list", [
			"payments" => $payments,
			"user" => $user,
			"isSamorzadKlasowy" => $userIsSamorzadKlasowy,
			"paymentsInTrash" => $paymentsInTrash ?? []
		]);
	}

	public function details(Payment $payment, Request $request)
	{
		if ($request->user()->cannot("details", $payment)) {
			abort(403);
		}

		$not_paid = $this->getNotPaidUsers($payment);
		$paid = $this->getPaidUsers($payment);
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

		$student = User::where("id", $userid)->first();
		if ($student->classunit_id !== $payment->classunit_id) {
			abort(400);
		}

		if (in_array($userid, $payment->excluded_students_array)) {
			abort(400);
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
		$user = $request->user();
		if ($user->cannot("create", Payment::class)) {
			abort(403);
		}

		$students = User::where("classunit_id", $user->classunit_id)
			->where('type', '!=', 'nauczyciel')
			->get();

		return view("payment.create", [
			"classUnitSize" => $students->count(),
			"classUnitStudents" => $students
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
		$user = $request->user();
		if ($user->cannot("create", Payment::class)) {
			abort(403);
		}

		$data = $request->validate([
			"money" => ["required", "numeric", "max:999"],
			"title" => ["required", "min:3", "max:80"],
			"deadline" => ["required", "date", "after:tomorrow"]
		]);

		$payment = new Payment;

		if ($request->has("excludeStudents")) {
			$excludedStudents = $request->input("student");
			$excludedStudents = array_keys($excludedStudents);
			$payment->excludedStudents = json_encode($excludedStudents);
			$users = $user->classUnit->users->whereNotIn("id", $excludedStudents);
		} else {
			$users = $user->classUnit->users;
		}

		$payment->amount = $data["money"];
		$payment->title = $data["title"];
		$payment->deadline = $data["deadline"];
		$payment->classunit_id = $user->classunit_id;
		$payment->paid = "[]";
		$payment->isGlobal = false;
		$payment->save();

		if (!app()->isDownForMaintenance()) {
			try {
				Notification::sendNow($users, new PaymentCreated($payment->title));
			} catch (\Exception $e) {
				Log::error("Failed to send notifications due to invalid keys. Further investigation required." . $e->getMessage());
			}
		}


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
		if ($request->user()->cannot("details", $payment)) {
			abort(403);
		}


		$not_paid = $this->getNotPaidUsers($payment);
		$paid = $this->getPaidUsers($payment);

		$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView("pdf.payment_close", [
			"payment" => $payment,
			"not_paid" => $not_paid,
			"paid" => $paid
		]);

		return $pdf->download("payment_closed_" . Str::of($payment->title)->slug() . ".pdf");
	}

	public function movePaymentToTrash(Payment $payment, Request $request)
	{
		if ($request->user()->cannot("delete", $payment)) {
			abort(403);
		}

		$payment->inTrash = true;
		$payment->save();
		return redirect("/skladki/");
	}

	protected function getNotPaidUsers(Payment $payment): array
	{
		$paidStudents = json_decode($payment->paid);
		$excludedStudents = $payment->excluded_students_array;
		$paidAndExcludedStudents = array_merge($paidStudents, $excludedStudents);

		$not_paid = User::whereNotIn("id", $paidAndExcludedStudents)
			->where('type', '!=', 'nauczyciel')
			->where("classunit_id", "=", $payment->classunit_id)->get();

		$not_paid = $not_paid->toArray();
		$name = array_column($not_paid, "name");
		array_multisort($name, SORT_ASC, $not_paid);

		return $not_paid;
	}

	protected function getPaidUsers(Payment $payment): array
	{
		$paid = json_decode($payment->paid);
		sort($paid);
		return $paid;
	}
}
