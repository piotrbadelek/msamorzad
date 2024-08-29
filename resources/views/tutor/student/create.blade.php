@extends("layouts.app")

@section("title", "Utwórz użytkownika - mSamorząd")

@section("content")
	<h1>Twoja klasa - utwórz użytkownika</h1>

	<form action="/tutor/students" method="post">
		@csrf
		<label for="username">Nazwa użytkownika</label>
		<input type="text" name="username" id="username" minlength="3" maxlength="128">
		<label for="name">Nazwisko i imię</label>
		<input type="text" name="name" id="name" minlength="3" maxlength="128">
		<label for="type">Rola w samorządzie klasowym:</label>
		<select name="type" id="type">
			<option value="student">Uczeń</option>
			<option value="skarbnik">Skarbnik</option>
			<option value="wiceprzewodniczacy">Wiceprzewodniczący</option>
			<option value="przewodniczacy">Przewodniczący</option>
		</select><br>

		<div class="info-alert">
			<p>Jeżeli dodajesz ucznia, który ma specjalną rolę w samorządzie szkolnym (przewodniczący,
				wiceprzewodniczący, skarbnik lub sekretarz) skontaktuj się z samorządem szkolnym aby nadać mu specjalne
				uprawnienia.</p>
		</div>

		<button type="submit">Zapisz</button>
	</form><br>
	<a href="/admin/user/" class="button">Cofnij</a>
@endsection
