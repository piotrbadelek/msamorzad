@extends("layouts.app")

@section("title", "Administracja - mSamorząd")

@section("content")
	<h1>Administracja - {{ $user->name }}</h1>
	<p>Nazwa użytkownika: {{ $user->username }}</p>
	<p>Rola w samorządzie klasowym: {{ $user->type }}</p>
	<p>Rola w samorządzie szkolnym: {{ $user->samorzadType }}</p>
	<p>Zmienił hasło? {{ $user->hasNotChangedPassword ? "Nie": "Tak" }}</p>
	<p>Stworzono konto: {{ $user->created_at }}</p>
	<p>ID: {{ $user->id }}</p>
	<a href="/admin/user/{{ $user->id }}/reset_password" class="button button-margin">Zresetuj hasło</a>
	<a href="/admin/user/{{ $user->id }}/delete" class="button button-margin">Usuń konto</a>
	<a href="/admin/user/{{ $user->id }}/update" class="button button-margin">Zmień detale</a>
	<a href="/admin/user" class="button">Cofnij</a>
@endsection
