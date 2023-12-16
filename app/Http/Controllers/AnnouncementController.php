<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

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

		$canPostToClass = $request->user()->isAdmin && !($request->user()->isTeacher && $request->user()->notManagingAClass);
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

		$canPostToClass = $request->user()->isAdmin && !($request->user()->isTeacher && $request->user()->notManagingAClass);
		$canPostGlobally = $request->user()->isSamorzad || $request->user()->isTeacher;

		$data = $request->validate([
			"title" => ["required", "max:128"],
			"description" => ["required", "min:3", "max:2048"],
			"postArea" => [],
			"image" => [File::types(['png', 'jpg', "webp"])
				->min("1kb")
				->max("10mb")]
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

		if ($request->hasFile("image")) {
			$path = $request->file("image")->store("public/banners");
			$announcement->imageUrl = str_replace("public/banners", "/storage/banners",$path);
		}

		$announcement->save();

		return redirect("/announcements/");
	}
}
