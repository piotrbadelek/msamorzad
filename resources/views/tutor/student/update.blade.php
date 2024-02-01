@extends("layouts.app")

@section("title", "Administracja - samorząd II LO")

@section("content")
	<h1>Administracja - {{ $user->name }}</h1>

	<form action="/tutor/students/{{ $user->id }}" method="post">
		@csrf
		@method("PATCH")

		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<label for="username">Nazwa użytkownika</label>
		<input type="text" name="username" id="username" value="{{ $user->username }}" minlength="3" maxlength="128">
		<label for="name">Nazwisko i imię</label>
		<input type="text" name="name" id="name" value="{{ $user->name }}" minlength="3" maxlength="128">
		<label for="type">Rola w samorządzie klasowym:</label>
		<select name="type" id="type">
			<option value="student" @if($user->type == "student") selected @endif>Uczeń</option>
			<option value="skarbnik" @if($user->type == "skarbnik") selected @endif>Skarbnik</option>
			<option value="wiceprzewodniczacy" @if($user->type == "wiceprzewodniczacy") selected @endif>Wiceprzewodniczący</option>
			<option value="przewodniczacy" @if($user->type == "przewodniczacy") selected @endif>Przewodniczący</option>
		</select><br>

		<button type="submit">Zapisz</button>
	</form><br>
	<a href="/tutor/students/{{ $user->id }}" class="button">Cofnij</a>
@endsection
