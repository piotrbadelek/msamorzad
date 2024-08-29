@extends("layouts.app")

@section("title", "Twoja klasa - mSamorząd")

@section("content")
	<h1>Twoja klasa - uczniowie zalegający z płatnościami</h1>
	<a href="/tutor" class="button">Cofnij</a>
	<a href="/tutor/student-payment-stats/pdf" class="button">Pobierz w formie PDF</a>
	<p>Liczymy sumę dni, kiedy uczeń był spóżniony z co najmniej jedną płatnością.</p>
	<ol>
		@foreach($users as $user)
			<li>{{ $user->name }} - łącznie {{ $user->total_late_days }} spóżnionych dni</li>
		@endforeach
	</ol>
@endsection
