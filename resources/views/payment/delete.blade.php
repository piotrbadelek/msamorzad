@extends("layouts.app")

@section("title", "Usuń składkę - samorząd II LO")

@section("content")
	<h1>Usuń składkę</h1>
	<p>Czy jesteś pewny, że chcesz usunąć składkę <b>{{ $payment->title }}</b>? Jest to nieodwracalne.</p>
	<form action="/skladki/{{ $payment->id }}" method="post">
		@csrf
		@method("delete")
		<button type="submit">Usuń</button>
	</form>
	<a href="/skladki/{{ $payment->id }}" class="payment-card__button cancel-deletion">Anuluj</a>
@endsection
