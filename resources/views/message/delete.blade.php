@extends("layouts.app")

@section("title", "Usuń wiadomość - mSamorząd")

@section("content")
	<h1>Usuń wiadomość</h1>
	<p>Czy jesteś pewny, że chcesz usunąć wiadomość? Jest to nieodwracalne.</p>
	<form action="/messages/{{ $message->id }}" method="post">
		@csrf
		@method("delete")
		<button type="submit">Usuń</button>
	</form>
	<a href="/messages/{{ $message->id }}" class="button cancel-deletion">Anuluj</a>
@endsection
