@extends("layouts.app")

@section("title", "Zmień hasło - samorząd II LO")

@section("content")
	<h1 data-dont-show-notif-prompt>Zmień hasło</h1>
	@if($changingForFirstTime)
		<h2>Po pierwszym zalogowaniu do aplikacji musisz zmienić hasło ze względów bezpieczeństwa.</h2>
	@endif
	@if($errors->any())
		<h2>{{$errors->first()}}</h2>
	@endif
	<form action="/change-password" method="post">
		@csrf
		<label for="password">Nowe hasło</label>
		<input type="password" name="password" id="password" minlength="8" required>
		<p>Co najmniej 8 znaków.</p>
		<button type="submit">Zmień</button>
	</form>
@endsection
