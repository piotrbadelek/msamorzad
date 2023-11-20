@extends("layouts.app")

@section("title", "Ogłoszenia - samorząd II LO")

@section("content")
	<h1>Ogłoszenia</h1>
	@if($isAdmin)
		<a href="/announcements/new" class="payment-card__button" id="new_payment">Nowy ogłoszenie</a>
	@endif
	@foreach($announcements as $announcement)
		<div class="message_container">
			<div class="message announcement">
				<header>{{ $announcement->title }}</header>
				<p>{{ $announcement->description }}</p>
			</div>
		</div>
	@endforeach
@endsection
