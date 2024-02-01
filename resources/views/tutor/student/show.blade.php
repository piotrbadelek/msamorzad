@extends("layouts.app")

@section("title", "Edytuj ucznia - samorząd II LO")

@section("content")
	<h1>Twoja klasa - {{ $user->name }}</h1>
	<p>Nazwa użytkownika: {{ $user->username }}</p>
	<p>Rola w samorządzie klasowym: {{ $user->type }}</p>
	<p>Rola w samorządzie szkolnym: {{ $user->samorzadType }}</p>
	<p>Zmienił hasło? {{ $user->hasNotChangedPassword ? "Nie": "Tak" }}</p>
	<p>Stworzono konto: {{ $user->created_at }}</p>
	<a href="/tutor/students/{{ $user->id }}/reset_password" class="button button-margin">Zresetuj hasło</a>
	<a href="/tutor/students/{{ $user->id }}/delete" class="button button-margin">Usuń konto</a>
	<a href="/tutor/students/{{ $user->id }}/update" class="button button-margin">Zmień detale</a>
	<a href="/tutor/students" class="button">Cofnij</a>
@endsection
