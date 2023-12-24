@extends("layouts.app")

@section("title", "Konkursy - samorząd II LO")

@section("content")
	<h1>Konkursy</h1>
	@if($isSamorzadKlasowy)
		<a href="/contests/new" class="payment-card__button" id="new_payment">Nowy konkurs</a>
	@endif
	@foreach($contests as $contest)
		<a href="/contests/{{ $contest->id }}" class="message_container">
			<div class="message">
				<p>{{ $contest->title }}</p>
			</div>
		</a>
	@endforeach
@endsection
