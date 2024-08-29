@extends("layouts.app")

@section("title", "Usuń ogłoszenie - mSamorząd")

@section("content")
	<h1>Usuń ogłoszenie</h1>
	<p>Czy jesteś pewny, że chcesz usunąć ogłoszenie <b>{{ $announcement->title }}</b>? Jest to nieodwracalne.</p>
	<form action="/announcements/{{ $announcement->id }}" method="post">
		@csrf
		@method("delete")
		<button type="submit">Usuń</button>
	</form>
	<a href="/announcements" class="button cancel-deletion">Anuluj</a>
@endsection
