@extends("layouts.app")

@section("title", "Twoja klasa - samorząd II LO")

@section("content")
	<h1>Twoja klasa - uczniowie</h1>
	<a href="/tutor" class="button">Cofnij</a>
	<a href="/tutor/students/new" class="button">Nowy uczeń</a>
	<ul class="payment-students">
		@foreach($users as $user)
			<li><a href="/tutor/students/{{ $user->id }}">{{ $user->name }}</a></li>
		@endforeach
	</ul>
@endsection
