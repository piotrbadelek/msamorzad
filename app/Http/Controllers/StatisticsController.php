<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
	public function activeUsers()
	{
		$results = User::select(
			DB::raw('(SUM(hasNotChangedPassword) / COUNT(*)) * 100 AS notActivePercentage'),
			DB::raw('(SELECT name FROM classunits WHERE classunits.id = users.classunit_id) AS classUnitName')
		)
			->groupBy('classunit_id')
			->get();

		return view("admin.stats.active-user-ratio", [
			"stats" => $results
		]);
	}
}
