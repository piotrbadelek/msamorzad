<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function list(Request $request) {
		return view("announcement.list", [
			"announcements" => Announcement::latest()->where("global", "1")->orWhere("classunit_id", $request->user()->classunit_id)->get(),
			"isAdmin" => $request->user()->isAdmin
		]);
	}

	public function createForm(Request $request) {
		if (!$request->user()->isPrivileged) {
			abort(403);
		}

		$canPostToClass = $request->user()->isAdmin;
		$canPostGlobally = $request->user()->isSamorzad || $request->user()->isTeacher;

		return view("announcement.create", [
			"canPostToClass" => $canPostToClass,
			"canPostGlobally" => $canPostGlobally,
			"class" => $request->user()->classunit->name
		]);
	}

	public function create(Request $request) {
		if (!$request->user()->isPrivileged) {
			abort(403);
		}

		$canPostToClass = $request->user()->isAdmin;
		$canPostGlobally = $request->user()->isSamorzad || $request->user()->isTeacher;

		$data = $request->validate([
			"title" => ["required", "max:128"],
			"description" => ["required", "min:3", "max:2048"],
			"postArea" => []
		]);

		$announcement = new Announcement;
		$announcement->title = $data["title"];
		$announcement->description = $data["description"];
		$announcement->classunit_id = $request->user()->classunit_id;

		if ($canPostGlobally && $data["postArea"] == "school") {
			$announcement->global = true;
		} else if ($canPostToClass) {
			$announcement->global = false;
		} else {
			abort(400);
		}

		$announcement->save();

		return redirect("/announcements/");
	}
}
