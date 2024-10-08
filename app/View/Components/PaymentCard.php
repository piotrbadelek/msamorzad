<?php

namespace App\View\Components;

use App\Models\Payment;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class PaymentCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
		public Payment $payment,
		public User $user,
		public Bool $inTrash = false
	)
    {
		//
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
		$currentDate = new \DateTime();
		$diffDate = $currentDate->diff(new \DateTime($this->payment["deadline"]));
		$hasExpired = $currentDate > new \DateTime($this->payment["deadline"]);

        return view('components.payment-card', [
			"userCanViewPaymentDetails" => $this->user->can("details", $this->payment),
			"hasExpired" => $hasExpired,
			"diffDate" => $diffDate,
			"user" => $this->user,
			"inTrash" => $this->inTrash
		]);
    }
}
