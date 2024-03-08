<?php

namespace App\Console\Commands;

use App\Models\Classunit;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentDueSoon;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class PaymentDueSoonReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind users about payments that are soon due.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		foreach (Classunit::all() as $classunit) {
			$payments = Payment::where("inTrash", false)->where("classunit_id", $classunit->id)->get();
			foreach ($payments as $payment) {
				$now = new DateTime();
				$due_date = new DateTime($payment->deadline);
				$diff = $now->diff($due_date)->format("%r%a");

				if ($diff < 4) {
					$not_paid = User::whereNotIn("id", json_decode($payment->paid))->where('type', '!=', 'nauczyciel')->where("classunit_id", $classunit->id)->get();
					Notification::sendNow($not_paid, new PaymentDueSoon($diff, $payment->amount));
				}

				if ($diff < 0) {
					$not_paid = User::whereNotIn("id", json_decode($payment->paid))->where('type', '!=', 'nauczyciel')->where("classunit_id", $classunit->id)->get();
					foreach ($not_paid as $unpaid_user) {
						$unpaid_user->total_late_days += 1;
						$unpaid_user->save();
					}
				}
			}
		}
    }
}
