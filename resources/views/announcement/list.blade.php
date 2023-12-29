@extends("layouts.app")

@section("title", "Ogłoszenia - samorząd II LO")

@section("content")
	<h1>Ogłoszenia</h1>
	@if($isPrivileged)
		<a href="/announcements/new" class="payment-card__button" id="new_payment">Nowe ogłoszenie</a>
	@endif
	@if (sizeof($announcements) > 0)
		@foreach($announcements as $announcement)
			<div class="message_container">
				<div class="message announcement">
					<header>{{ $announcement->title }}</header>
					<p>{{ $announcement->description }}</p>
					@if ($announcement->imageUrl)
						<a href="{{ $announcement->imageUrl }}">
							<img src="{{ $announcement->imageUrl }}" loading="lazy" decoding="async"
								 alt="{{ $announcement->title }}" class="announcement-image">
						</a>
					@endif

					@if ($isPrivileged && $deletionPermissions["a-" . $announcement->id])
						<a href="/announcements/{{ $announcement->id }}/delete" class="payment-card__button">Usuń
							ogłoszenie</a>
					@endif
				</div>
			</div>
		@endforeach
	@else
		<x-no-entries type="announcement"/>
	@endif
@endsection
