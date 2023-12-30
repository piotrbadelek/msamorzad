@extends("layouts.app")

@section("title", "Usuń konkurs - samorząd II LO")

@section("content")
	<h1>Usuń konkurs</h1>
	<p>Czy jesteś pewny, że chcesz usunąć konkurs <b>{{ $contest->title }}</b>? Jest to nieodwracalne.</p>
	<form action="/contests/{{ $contest->id }}" method="post">
		@csrf
		@method("delete")
		<button type="submit">Usuń</button>
	</form>
	<a href="/contests/{{ $contest->id }}" class="button cancel-deletion">Anuluj</a>
@endsection
