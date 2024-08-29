@extends("layouts.app")

@section("title", "Konkursy - mSamorząd")

@section("content")
	<h1>{{ $contest->title }}</h1>
	<p>{{ $contest->description }}</p>
	@if($canManageContest)
		<p>Uczestnicy:</p>
		<ul>
			@foreach($enlisted_names as $name)
				<li>{{ $name->name }}</li>
			@endforeach
		</ul>
	@endif
	<span class="contest-member-counter">Bierze udział: {{ count($enlisted) }}</span>

	@if ($canEnlist)
		@if (in_array($user_id, $enlisted))
			<a href="/contests/{{ $contest->id }}/enlist" class="button">Wycofaj udział</a>
		@else
			<a href="/contests/{{ $contest->id }}/enlist" class="button">Weź udział</a>
		@endif
	@endunless

	@if($canManageContest)
		<a href="/contests/{{ $contest->id }}/delete" class="button">Usuń konkurs</a>
	@endif
@endsection

