<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use App\Notifications\AnnouncementCreated;
use App\Notifications\PaymentCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\File;

class AnnouncementController extends Controller
{
	public function list(Request $request)
	{
		$announcements = Announcement::latest()->where("global", "1")->orWhere("classunit_id", $request->user()->classunit_id)->get();
		if ($request->user()->isPrivileged) {
			$deletionPermissions = [];
			foreach ($announcements as $announcement) {
				$deletionPermissions["a-" . $announcement->id] = $this->verifyDeletionPermissions($request->user(), $announcement);
			}

			return view("announcement.list", [
				"announcements" => $announcements,
				"isPrivileged" => true,
				"deletionPermissions" => $deletionPermissions
			]);
		} else {
			return view("announcement.list", [
				"announcements" => $announcements,
				"isPrivileged" => false
			]);
		}
	}

	public function createForm(Request $request)
	{
		if ($request->user()->cannot("create", Announcement::class)) {
			abort(403);
		}

		$canPostToClass = $request->user()->can("postToClass", Announcement::class);
		$canPostGlobally = $request->user()->can("postGlobally", Announcement::class);

		return view("announcement.create", [
			"canPostToClass" => $canPostToClass,
			"canPostGlobally" => $canPostGlobally,
			"class" => $request->user()->classunit->name
		]);
	}

	public function create(Request $request)
	{
		if ($request->user()->cannot("create", Announcement::class)) {
			abort(403);
		}

		$canPostToClass = $request->user()->can("postToClass", Announcement::class);
		$canPostGlobally = $request->user()->can("postGlobally", Announcement::class);

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
			$users = User::all();
		} else if ($canPostToClass && $data["postArea"] == "class") {
			$announcement->global = false;
			$users = $request->user()->classUnit->users;
		} else {
			abort(400);
		}

		try {
			Notification::sendNow($users, new AnnouncementCreated($announcement->title));
		} catch (\Exception $e) {
			Log::error("Failed to send notifications due to invalid keys. Further investigation required." . $e->getMessage());
		}

		if ($request->hasFile("image")) {
			$path = $request->file("image")->store("public/banners");
			$announcement->imageUrl = str_replace("public/banners", "/storage/banners", $path);
		}

		$announcement->save();

		return redirect("/announcements/");
	}

	protected function verifyDeletionPermissions(User $user, Announcement $announcement): bool
	{
		$canPostToClass = $user->can("postToClass", Announcement::class);
		$canPostGlobally = $user->can("postGlobally", Announcement::class);

		if ((!$canPostGlobally && $announcement->global) || (!$canPostToClass && !$announcement->global)) {
			return false;
		}

		if (!$announcement->global) {
			if ($user->classunit->id !== $announcement->classunit_id) {
				return false;
			}
		}
		return true;
	}

	public function deleteForm(Request $request, Announcement $announcement)
	{
		if ($request->user()->cannot("delete", $announcement)) {
			abort(403);
		}

		if (!$this->verifyDeletionPermissions($request->user(), $announcement)) abort(403);

		return view("announcement.delete", [
			"announcement" => $announcement
		]);
	}

	public function delete(Request $request, Announcement $announcement)
	{
		if ($request->user()->cannot("delete", $announcement)) {
			abort(403);
		}

		if (!$this->verifyDeletionPermissions($request->user(), $announcement)) abort(403);

		$announcement->delete();
		return redirect("/announcements");
	}
}
