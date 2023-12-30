@extends("layouts.app")

@section("title", "Administracja - samorząd II LO")

@section("content")
	<h1>Administracja - {{ $user->name }}</h1>

	<form action="/admin/user/{{ $user->id }}" method="post">
		@csrf
		@method("PATCH")
		<label for="username">Nazwa użytkownika</label>
		<input type="text" name="username" id="username" value="{{ $user->username }}" minlength="3" maxlength="128">
		<label for="name">Nazwisko i imię</label>
		<input type="text" name="name" id="name" value="{{ $user->name }}" minlength="3" maxlength="128">
		<label for="classunit_id">Należy do klasy:</label>
		<select name="classunit_id" id="classunit_id">
			@foreach($classunits as $classunit)
				<option value="{{ $classunit->id }}" @if($user->classunit_id == $classunit->id) selected @endif>{{ $classunit->name }}</option>
			@endforeach
		</select><br>
		<label for="type">Rola w samorządzie klasowym:</label>
		<select name="type" id="type">
			<option value="student" @if($user->type == "student") selected @endif>Uczeń</option>
			<option value="skarbnik" @if($user->type == "skarbnik") selected @endif>Skarbnik</option>
			<option value="wiceprzewodniczacy" @if($user->type == "wiceprzewodniczacy") selected @endif>Wiceprzewodniczący</option>
			<option value="przewodniczacy" @if($user->type == "przewodniczacy") selected @endif>Przewodniczący</option>
			<option value="nauczyciel" @if($user->type == "nauczyciel") selected @endif>Nauczyciel</option>
		</select><br>

		<label for="samorzadType">Rola w samorządzie szkolnym:</label>
		<select name="samorzadType" id="samorzadType">
			<option value="student" @if($user->samorzadType == "student") selected @endif>Uczeń</option>
			<option value="sekretarz" @if($user->samorzadType == "sekretarz") selected @endif>Sekretarz</option>
			<option value="skarbnik" @if($user->samorzadType == "skarbnik") selected @endif>Skarbnik</option>
			<option value="wiceprzewodniczacy" @if($user->samorzadType == "wiceprzewodniczacy") selected @endif>Wiceprzewodniczący</option>
			<option value="przewodniczacy" @if($user->samorzadType == "przewodniczacy") selected @endif>Przewodniczący</option>
			<option value="nauczyciel" @if($user->samorzadType == "nauczyciel") selected @endif>Nauczyciel</option>
		</select><br>

		<button type="submit">Zapisz</button>
	</form><br>
	<a href="/admin/user/{{ $user->id }}" class="button">Cofnij</a>
@endsection
