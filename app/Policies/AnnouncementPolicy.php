<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
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
		return $user->isPrivileged;
	}

	public function postGlobally(User $user): bool {
		return $user->isSamorzadSzkolny || $user->isTeacher;
	}

	public function postToClass(User $user): bool {
		return $user->isSamorzadKlasowy && !($user->isTeacher && $user->notManagingAClass);
	}

	public function delete(User $user, Announcement $announcement): bool {
		if ($announcement->global) {
			return $user->isSamorzadSzkolny || $user->isTeacher;
		} else {
			return ($user->classunit_id == $announcement->classunit_id) && $user->isSamorzadKlasowy;
		}
	}
}
