@extends("layouts.app")

@section("title", "Administracja - Samorząd II LO")

@section("content")
	<h1>Administracja</h1>
	<a href="/admin/user" class="card-container">
		<div class="card">
			<img src="/img/users.webp" alt="Użytkownicy">
			<p class="card_content">Użytkownicy</p>
		</div>
	</a>
	<a href="/admin/classunit" class="card-container">
		<div class="card">
			<img src="/img/classunit.webp" alt="Klasy">
			<p class="card_content">Klasy</p>
		</div>
	</a>
	{{--<a href="/admin" class="card-container">
		<div class="card">
			<img src="/img/statistics.webp" alt="Statystyki">
			<p class="card_content">Statystyki</p>
		</div>
	</a>--}}
	<a href="/" class="card-container">
		<div class="card">
			<img src="/img/backwards.webp" alt="Wyjdź">
			<p class="card_content">Wyjdź</p>
		</div>
	</a>
	<footer class="appver">mSamorząd {{ config("app.version") }} (on PHP {{ phpversion() }})</footer>
@endsection
