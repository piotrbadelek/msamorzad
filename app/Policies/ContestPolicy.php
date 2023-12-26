<?php

namespace App\Policies;

use App\Models\Contest;
use App\Models\User;

class ContestPolicy
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
		return $user->isSamorzadSzkolny || $user->isTeacher;
	}

	public function listParticipants(User $user): bool {
		return $user->isSamorzadSzkolny || $user->isTeacher;
	}

	public function enlist(User $user): bool {
		return !$user->isTeacher;
	}

	public function delete(User $user): bool {
		return $user->isSamorzadSzkolny || $user->isTeacher;
	}
}
