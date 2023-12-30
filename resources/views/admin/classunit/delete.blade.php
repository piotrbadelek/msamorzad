@extends("layouts.app")

@section("title", "Usuń klasę - samorząd II LO")

@section("content")
	<h1>Usuń klasę</h1>
	<p>Czy jesteś pewny, że chcesz usunąć klasę <b>{{ $classunit->name }}</b> WRAZ ZE WSZYSTKIMI UCZNIAMI KTÓRE DO NIEJ NALEŻĄ? Jest to nieodwracalne.</p>
	<form action="/admin/classunit/{{ $classunit->id }}" method="post">
		@csrf
		@method("delete")
		<button type="submit">Usuń</button>
	</form>
	<a href="/admin/classunit" class="button cancel-deletion">Anuluj</a>
@endsection
