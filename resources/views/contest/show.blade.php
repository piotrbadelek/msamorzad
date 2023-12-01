@extends("layouts.app")

@section("title", "Konkursy - samorząd II LO")

@section("content")
	<h1>{{ $contest->title }}</h1>
	<p>{{ $contest->description }}</p>
	@if($is_admin)
		<ul>
			@foreach($enlisted_names as $name)
				<li>{{ $name->name }}</li>
			@endforeach
		</ul>
	@endif
	<span>Bierze udział: {{ count($enlisted) }}</span>


	@unless ($is_wychowawca)
		@if (in_array($user_id, $enlisted))
			<a href="/contests/{{ $contest->id }}/enlist" class="payment-card__button">Wycofaj udział</a>
		@else
			<a href="/contests/{{ $contest->id }}/enlist" class="payment-card__button">Weź udział</a>
		@endif
	@endunless
@endsection

