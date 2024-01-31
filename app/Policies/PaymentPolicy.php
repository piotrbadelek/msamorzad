<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

	public function before(User $user): bool|null {
		if ($user->isAdministrator) {
			return true;
		}

		return null;
	}

	public function create(User $user): bool {
		$teacherWithoutAClass = $user->isTeacher && $user->notManagingAClass;
		$isPrivileged = $user->isPrivileged;
		return !$teacherWithoutAClass && $isPrivileged;
	}

	/**
	 * Allow to see details on a Payment,
	 * only if the user belongs to the same
	 * class as the payment (if the payment is local),
	 * or if the payment is global allow both members of
	 * the school council and the class council
	 */
	public function details(User $user, Payment $payment): bool {
		if ($payment->global) {
			return $user->isPrivileged;
		} else {
			return ($payment->classunit_id == $user->classunit_id) && $user->isSamorzadKlasowy;
		}
	}

	/**
	 * Only allow to mark student as paid,
	 * if the user is part of a class council,
	 * and the user belongs to the same class unit
	 * as the payment
	 */
	public function pay(User $user, Payment $payment): bool {
		$teacherWithoutAClass = $user->isTeacher && $user->notManagingAClass;
		$isSamorzadKlasowy = $user->isSamorzadKlasowy;
		if ($teacherWithoutAClass || !$isSamorzadKlasowy) {
			return false;
		}

		return $payment->classunit_id == $user->classunit_id;
	}

	/**
	 * Allow to delete a Payment,
	 * only if the user belongs to the same
	 * class as the payment (if the payment is local),
	 * or if the payment is global allow only members of
	 * the school council
	 */
	public function delete(User $user, Payment $payment): bool {
		if ($payment->global) {
			return $user->isSamorzadSzkolny;
		} else {
			return ($payment->classunit_id == $user->classunit_id) && $user->isSamorzadKlasowy;
		}
	}
}
