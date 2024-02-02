<?php

namespace App\Utilities;

/**
 * Provides timekeeping services to determine
 * if time-limited events are currently active or not.
 */

class EventActivation
{
	public static function isValentinesDayEventActive(): bool
	{
		if (config("app.debug")) {
			return true;
		}

		$currentDate = date("Y-m-d");
		$currentDate = date('Y-m-d', strtotime($currentDate));
		$currentYear = date("Y");
		$eventBegin = date('Y-m-d', strtotime("$currentYear-02-12"));
		$eventEnd = date('Y-m-d', strtotime("$currentYear-02-14"));

		if (($currentDate >= $eventBegin) && ($currentDate <= $eventEnd)) {
			return true;
		} else {
			return false;
		}
	}
}
