<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class SessionController extends Controller
{
    public function login() {
        return view("login");
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            "username" => ["required"],
            "password" => ["required"]
        ]);

        if (Auth::attempt($credentials, true)) {
			if ($request->user()->hasNotChangedPassword) {
				return redirect("/change-password?changingForFirstTime=true");
			}
            return redirect("/");
        } else {
            return Redirect::back()->withErrors(["message" => "Niepoprawna nazwa użytkownika bądź hasło."]);
        }
    }

	public function changePassword() {
		return view("change-password", [
			"changingForFirstTime" => isset($_GET["changingForFirstTime"]) && $_GET["changingForFirstTime"]	 == "true"
		]);
	}

	public function update(Request $request) {
		$credentials = $request->validate([
			"password" => "required|min:8|max:2048"
		]);

		User::whereId(auth()->user()->id)->update([
			"password" => Hash::make($credentials["password"]),
			"hasNotChangedPassword" => false
		]);

		return \redirect("/");
	}

	public function logout(Request $request) {
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect('/');
	}
}
