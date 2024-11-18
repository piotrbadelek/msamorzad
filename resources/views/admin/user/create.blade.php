@extends("layouts.app")

@section("title", "Administracja - mSamorząd")

@section("content")
	<h1>Administracja - utwórz użytkownika</h1>

	<form action="/admin/user" method="post" data-turbo="false">
		@csrf
		<label for="username">Nazwa użytkownika</label>
		<input type="text" name="username" id="username" minlength="3" maxlength="128">
		<label for="name">Nazwisko i imię</label>
		<input type="text" name="name" id="name" minlength="3" maxlength="128">
		<label for="classunit_id">Należy do klasy:</label>
		<select name="classunit_id" id="classunit_id">
			@foreach($classunits as $classunit)
				<option value="{{ $classunit->id }}">{{ $classunit->name }}</option>
			@endforeach
		</select><br>
		<label for="type">Rola w samorządzie klasowym:</label>
		<select name="type" id="type">
			<option value="student">Uczeń</option>
			<option value="skarbnik">Skarbnik</option>
			<option value="wiceprzewodniczacy">Wiceprzewodniczący</option>
			<option value="przewodniczacy">Przewodniczący</option>
			<option value="nauczyciel">Nauczyciel</option>
		</select><br>

		<label for="samorzadType">Rola w samorządzie szkolnym:</label>
		<select name="samorzadType" id="samorzadType">
			<option value="student">Uczeń</option>
			<option value="sekretarz">Sekretarz</option>
			<option value="skarbnik">Skarbnik</option>
			<option value="wiceprzewodniczacy">Wiceprzewodniczący</option>
			<option value="przewodniczacy">Przewodniczący</option>
			<option value="nauczyciel">Nauczyciel</option>
		</select><br>

		<button type="submit">Zapisz</button>
	</form><br>
	<a href="/admin/user/" class="button">Cofnij</a>
@endsection
