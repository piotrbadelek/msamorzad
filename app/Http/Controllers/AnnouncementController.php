<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function list(Request $request) {
		return view("announcement.list", [
			"announcements" => Announcement::latest()->get(),
			"isAdmin" => $request->user()->isAdmin
		]);
	}

	public function createForm(Request $request) {
		if (!$request->user()->isAdmin) {
			abort(403);
		}

		return view("announcement.create");
	}

	public function create(Request $request) {
		if (!$request->user()->isAdmin) {
			abort(403);
		}

		$data = $request->validate([
			"title" => ["required", "max:128"],
			"description" => ["required", "min:3", "max:2048"]
		]);

		$announcement = new Announcement;
		$announcement->title = $data["title"];
		$announcement->description = $data["description"];
		$announcement->save();

		return redirect("/announcements/");
	}
}
