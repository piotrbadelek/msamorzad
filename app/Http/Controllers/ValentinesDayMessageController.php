<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ValentinesDayMessage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ValentinesDayMessageController extends Controller
{
	public function index(Request $request)
	{
		$user = $request->user();
		return view("valentine.index", [
			"user" => $user
		]);
	}

	public function create(Request $request)
	{
		$user = $request->user();
		$data = $request->validate([
			"content" => "required|max:960"
		]);

		if (ValentinesDayMessage::where("user_id", $user->id)->count() > 8) {
			return redirect()->back()->withErrors([
				"message" => "Nie możesz wysłać więcej niż 8 listów."
			]);
		}

		$valentinesDayMessage = new ValentinesDayMessage;
		$valentinesDayMessage->user_id = $user->id;
		$valentinesDayMessage->content = $data["content"];

		if ($request->filled("recipient")) {
			$data = $request->validate([
				"recipient" => "required|max:128"
			]);
			$valentinesDayMessage->recipient = $data["recipient"];
		} else {
			$randomRecipient = User::inRandomOrder()->first();
			$valentinesDayMessage->recipient = "$randomRecipient->name ({$randomRecipient->classunit->name})";
		}

		$valentinesDayMessage->save();

		return redirect()->back()->with("confirmation", "List został wysłany!");
	}

	public function export(Request $request)
	{
		if (!$request->user()->isSamorzadSzkolny) {
			abort(403);
		}

		$messages = ValentinesDayMessage::all();
		$pdf = Pdf::loadView("pdf.valentines_day_export", [
			"messages" => $messages
		]);

		$uuid = Str::uuid();

		return $pdf->download("valentines_day_$uuid.pdf");
	}
}
