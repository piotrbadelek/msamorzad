@extends("layouts.app")

@section("title", "Konkursy - samorząd II LO")

@section("content")
	<h1>Konkursy</h1>
	@foreach($contests as $contest)
		<a href="/contests/{{ $contest->id }}" class="message_container">
			<div class="message">
				<p>{{ $contest->title }}</p>
			</div>
		</a>
	@endforeach
@endsection
