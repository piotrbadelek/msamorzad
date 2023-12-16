@extends("layouts.app")

@section("title", "Ogłoszenia - samorząd II LO")

@section("content")
	<h1>Ogłoszenia</h1>
	@if($isAdmin)
		<a href="/announcements/new" class="payment-card__button" id="new_payment">Nowe ogłoszenie</a>
	@endif
	@foreach($announcements as $announcement)
		<div class="message_container">
			<div class="message announcement">
				<header>{{ $announcement->title }}</header>
				<p>{{ $announcement->description }}</p>
				@if ($announcement->imageUrl)
					<a href="{{ $announcement->imageUrl }}">
						<img src="{{ $announcement->imageUrl }}" loading="lazy" decoding="async" alt="{{ $announcement->title }}" class="announcement-image">
					</a>
				@endif
			</div>
		</div>
	@endforeach
@endsection
