@extends("layouts.app")

@section("title", "Składki - samorząd II LO")

@section("content")
	<h1>Składka {{ $payment->title }}</h1>
	<p>Wysokość: {{ $payment->amount }} zł.<br>
		Suma: {{ count($paid) * $payment->amount + count($not_paid) * $payment->amount }} zł.</p>
	<b>Zapłaciło:</b><br>
	<span>Łącznie: {{ count($paid) * $payment->amount }} zł</span>
	<ul>
		@foreach($paid as $user)
			<li><a href="/skladki/1/{{ $user }}">{{ \App\Models\User::where("id", $user)->first()->name }}</a></li>
		@endforeach
	</ul>
	<b>Nie zapłaciło:</b><br>
	<span>Łącznie: {{ count($not_paid) * $payment->amount }} zł</span>
	<ul>
		@foreach($not_paid as $user)
			<li><a href="/skladki/1/{{ $user->id }}">{{ $user->name }}</a></li>
		@endforeach
	</ul>
@endsection
