<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\User;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    public function list(Request $request) {
		$contests = Contest::all();

		return view("contest.list", [
			"contests" => Contest::latest()->get(),
			"canCreateContests" => $request->user()->cannot("create", Contest::class)
		]);
	}

	public function show(Contest $contest, Request $request) {
		$enlisted = json_decode($contest->enlisted);
		if ($request->user()->can("listParticipants", $contest)) {
			$enlisted_names = User::whereIn("id", $enlisted)->get();
		}

		return view("contest.show", [
			"contest" => $contest,
			"user_id" => $request->user()->id,
			"enlisted" => $enlisted,
			"enlisted_names" => $enlisted_names ?? [],
			"canManageContest" => $request->user()->can("listParticipants", $contest),
			"canEnlist" => $request->user()->can("enlist", $contest)
		]);
	}

	public function enlist(Contest $contest, Request $request)
	{
		if ($request->user()->cannot("enlist", $contest)) {
			abort(403);
		}

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

	public function createForm(Request $request) {
		if ($request->user()->cannot("create", Contest::class)) {
			abort(403);
		}

		return view("contest.create");
	}

	public function deleteForm(Request $request, Contest $contest) {
		if ($request->user()->cannot("delete", $contest)) {
			abort(403);
		}

		return view("contest.delete", [
			"contest" => $contest
		]);
	}

	public function create(Request $request) {
		if ($request->user()->cannot("create", Contest::class)) {
			abort(403);
		}

		$data = $request->validate([
			"title" => ["required", "max:128"],
			"description" => ["required", "min:3", "max:2048"]
		]);

		$contest = new Contest;
		$contest->title = $data["title"];
		$contest->description = $data["description"];
		$contest->enlisted = "[]";
		$contest->save();

		return redirect("/contests/" . $contest->id);
	}

	public function delete(Request $request, Contest $contest) {
		if ($request->user()->cannot("delete", $contest)) {
			abort(403);
		}

		$contest->delete();
		return redirect("/contests");
	}
}
