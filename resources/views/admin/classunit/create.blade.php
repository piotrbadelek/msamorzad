@extends("layouts.app")

@section("title", "Administracja - samorząd II LO")

@section("content")
	<h1>Administracja - utwórz klasę</h1>

	<form action="/admin/classunit" method="post">
		@csrf
		<label for="name">Nazwa klasy</label>
		<input type="text" name="name" id="name" minlength="4" maxlength="8">

		<button type="submit">Utwórz</button>
	</form><br>
	<a href="/admin/classunit/" class="button">Cofnij</a>
@endsection
