<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\User;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    public function list() {
		$contests = Contest::all();

		return view("contest.list", [
			"contests" => Contest::all()
		]);
	}

	public function show(Contest $contest, Request $request) {
		$enlisted = json_decode($contest->enlisted);
		if ($request->user()->isAdmin) {
			$enlisted_names = User::whereIn("id", $enlisted)->get();
		}

		return view("contest.show", [
			"contest" => $contest,
			"user_id" => $request->user()->id,
			"enlisted" => $enlisted,
			"enlisted_names" => $enlisted_names ?? [],
			"is_admin" => $request->user()->isAdmin
		]);
	}

	public function enlist(Contest $contest, Request $request) {
		$contestEnlisted = json_decode($contest->enlisted);
		if (in_array($request->user()->id, $contestEnlisted)) {
			$key = array_search($request->user()->id, $contestEnlisted);
			array_splice($contestEnlisted, $key, 1);
		} else {
			$contestEnlisted[] = $request->user()->id;
		}
		$contest->update(["enlisted" => json_encode($contestEnlisted)]);
		$contest->save();
		return redirect()->back();
	}
}
