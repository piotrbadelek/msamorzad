@extends("layouts.app")

@section("title", "Utworzono konto - samorząd II LO")

@section("content")
	<h1>Utworzono konto</h1>
	<p>Utworzono konto dla użytkownika {{ $user->name }}. Tymczasowe hasło: <b>{{ $temporaryPassword }}</b></p>
	<p>Użytkownik będzie musiał zmienić hasło przy następnym logowaniu.</p>
	<p>Karta startowa:</p>
	<button id="startCardInvoker">Wygeneruj kartę startową.</button>
	<canvas id="cardCanvas" width="360" height="240" data-password="{{ $temporaryPassword }}" data-username="{{ $user->username }}" data-name="{{ $user->name }}">
		Twoja przeglądarka nie obsługuje interfejsu <i>Canvas</i>.<br>
		mSamorząd rekomenduje przeglądarkę <a href="https://firefox.com">Firefox</a>.
	</canvas>
	<a href="javascript:void(0)" id="downloadCard" hidden>Pobierz kartę</a>
@endsection

@section("scripts")
	<script src="/js/admin.js?v=1" defer></script>
@endsection
