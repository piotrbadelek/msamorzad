@extends("layouts.app")

@section("title", "Zresetuj hasło - samorząd II LO")

@section("content")
	<h1>Zresetowano hasło</h1>
	<p>Zresetowano hasło dla użytkownika {{ $user->name }}. Tymczasowe hasło: <b>{{ $temporaryPassword }}</b></p>
	<p>Użytkownik będzie musiał zmienić hasło przy następnym logowaniu.</p>
	<p>Karta zmiany hasła:</p>
	<button onclick="generateCard()" id="cardInvoker">Wygeneruj kartę zmiany hasła.</button>
	<canvas id="cardCanvas" width="360" height="240" data-password="{{ $temporaryPassword }}" data-username="{{ $user->username }}" data-name="{{ $user->name }}">
		Twoja przeglądarka nie obsługuje interfejsu <i>Canvas</i>.<br>
		mSamorząd rekomenduje przeglądarkę <a href="https://firefox.com">Firefox</a>.
	</canvas>
	<a href="javascript:void(0)" id="downloadCard" hidden>Pobierz kartę</a>
@endsection

@section("scripts")
<script src="/admin.js?v=1" defer></script>
@endsection