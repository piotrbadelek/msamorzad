<?php

namespace App\Utilities;

use Illuminate\Support\Carbon;

/**
 * Provides timekeeping services to determine
 * if time-limited events are currently active or not.
 */
class EventActivation
{
	protected static function determineIfBetweenDates(string $start, string $end): bool
	{
		$now = Carbon::now();
		$startDate = Carbon::parse($start);
		$endDate = Carbon::parse($end);

		return $now->between($startDate, $endDate);
	}

	public static function isValentinesDayEventActive(): bool
	{
		if (config("app.debug")) {
			return true;
		}

		$currentYear = date("Y");
		$start = "$currentYear-02-10T00:00:00+01:00";
		$end = "$currentYear-02-13T20:00:00+01:00";

		return EventActivation::determineIfBetweenDates($start, $end);
	}
}
