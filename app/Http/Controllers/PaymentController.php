<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function view() {
        $payments = Payment::all();
        return view("payments", [
            "payments" => $payments
        ]);
    }
}
