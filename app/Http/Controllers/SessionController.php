<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (Auth::attempt($credentials)) {
            return redirect("/");
        } else {
            return Redirect::back()->withErrors(["message" => "Niepoprawna nazwa użytkownika bądź hasło."]);
        }
    }
}
