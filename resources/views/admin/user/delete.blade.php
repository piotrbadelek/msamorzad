@extends("layouts.app")

@section("title", "Usuń użytkownika - samorząd II LO")

@section("content")
	<h1>Usuń użytkownika</h1>
	<p>Czy jesteś pewny, że chcesz usunąć użytkownika <b>{{ $user->name }}</b>? Jest to nieodwracalne.</p>
	<form action="/admin/user/{{ $user->id }}" method="post">
		@csrf
		@method("delete")
		<button type="submit">Usuń</button>
	</form>
	<a href="/admin/user/{{ $user->id }}" class="button cancel-deletion">Anuluj</a>
@endsection