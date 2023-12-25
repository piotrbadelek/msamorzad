<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function list(Request $request) {
		$messages = Message::latest()->take(20)->get();
		return view("message.list", [
			"messages" => $messages,
			"isSamorzadKlasowy" => $request->user()->isSamorzadKlasowy
		]);
	}

	public function create(Request $request) {
		$data = $request->validate([
			"question" => "required|max:800"
		]);

		$question = new Message;
		$question->user_id = $request->user()->id;
		$question->question = $data["question"];
		$question->save();

		return redirect()->back();
	}

	public function show(Message $message, Request $request) {
		if ($request->user()->cannot("respond", $message)) {
			abort(403);
		}

		return view("message.show", [
			"message" => $message
		]);
	}

	public function update(Message $message, Request $request) {
		if ($request->user()->cannot("respond", $message)) {
			abort(403);
		}

		$data = $request->validate([
			"response" => "required|max:800"
		]);

		$message->response = $data["response"];
		$message->save();

		return redirect("/messages");
	}

	public function deleteForm(Request $request, Message $message) {
		if ($request->user()->cannot("delete", $message)) {
			abort(403);
		}

		return view("message.delete", [
			"message" => $message
		]);
	}

	public function delete(Request $request, Message $message) {
		if ($request->user()->cannot("delete", $message)) {
			abort(403);
		}

		$message->delete();
		return redirect("/messages");
	}
}
