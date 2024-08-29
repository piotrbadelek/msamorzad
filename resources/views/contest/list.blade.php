@extends("layouts.app")

@section("title", "Konkursy - mSamorząd")

@section("content")
	<h1>Konkursy</h1>
	@if($canCreateContests)
		<a href="/contests/new" class="button" id="new_payment">Nowy konkurs</a>
	@endif
	@if (sizeof($contests) > 0)
		@foreach($contests as $contest)
			<a href="/contests/{{ $contest->id }}" class="message_container">
				<div class="message">
					<p>{{ $contest->title }}</p>
				</div>
			</a>
		@endforeach
	@else
		<x-no-entries type="contest"/>
	@endif
@endsection
