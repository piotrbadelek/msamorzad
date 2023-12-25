<?php

namespace App\Policies;

use App\Models\User;

class MessagePolicy
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

	public function respond(User $user): bool {
		return $user->isSamorzadSzkolny;
	}

	public function delete(User $user) {
		return $user->isSamorzadSzkolny;
	}
}
