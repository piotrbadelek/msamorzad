<?php

namespace App\Utilities;

use App\Models\Message;
use App\Models\User;
use App\Models\ValentinesDayMessage;

/**
 * Provides services to purge a user's presence from
 * the database, including purging messages and removal from payments
 */
class UserPurge
{
	public static function deleteMessages(User $user, User $ghostUser): void
	{
		$messages = Message::where("user_id", $user->id)->get();
		foreach ($messages as $message) {
			$message->user_id = $ghostUser->id;
			$message->save();
		}
	}

	public static function removeFromPayments(User $user, User $ghostUser): void
	{
		$payments = $user->classUnit->payments;
		foreach ($payments as $payment) {
			$paidUsers = json_decode($payment->paid);
			if (in_array($user->id, $paidUsers)) {
				$paidUsers[array_search($user->id, $paidUsers)] = $ghostUser->id;
				$payment->paid = json_encode($paidUsers);
				$payment->save();
			}
		}
	}

	// No need to replace the message's author with a ghostUser, just delete the message entirely.
	public static function deleteValentineMessages(User $user): void
	{
		ValentinesDayMessage::where("user_id", $user->id)->delete();
	}
}
