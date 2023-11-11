<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function view(Request $request) {
        $payments = Payment::where("classunit_id", $request->user()->classunit_id)->get();
        return view("payment.list", [
            "payments" => $payments,
            "user" => $request->user()
        ]);
    }

    public function details(Payment $payment, Request $request) {
        if (!$request->user()->isAdmin) {
            abort(403);
        }

        $not_paid = User::whereNotIn("id", json_decode($payment->paid))->get();

        return view("payment.show", [
            "payment" => $payment,
            "not_paid" => $not_paid
        ]);
    }

    public function pay(Payment $payment, Int $userid, Request $request) {
        // TODO: Make sure the admin is from the class
        if (!$request->user()->isAdmin) {
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

    public function createForm(Request $request) {
        if (!$request->user()->isAdmin) {
            abort(403);
        }

        return view("payment.create", [
            "classUnitSize" =>  User::count("classunit_id", $request->user()->classunit_id)
        ]);
    }
}
