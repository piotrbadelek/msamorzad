@extends("layouts.app")

@section("title", "Składki - samorząd II LO")

@section("content")
	<h1>Składka {{ $payment->title }}</h1>
	<p>Wysokość: {{ $payment->amount }} zł.<br>
		Suma: {{ count($paid) * $payment->amount + count($not_paid) * $payment->amount }} zł.</p>
	<b>Zapłaciło:</b><br>
	<span>Łącznie: {{ count($paid) * $payment->amount }} zł</span>
	<ul class="payment-students">
		@foreach($paid as $user)
			<li><a href="/skladki/{{ $payment->id }}/{{ $user }}">{{ \App\Models\User::where("id", $user)->first()->name }}</a></li>
		@endforeach
	</ul>
	<b>Nie zapłaciło:</b><br>
	<span>Łącznie: {{ count($not_paid) * $payment->amount }} zł</span>
	<ul class="payment-students">
		@foreach($not_paid as $user)
			<li><a href="/skladki/{{ $payment->id }}/{{ $user["id"] }}">{{ $user["name"] }}</a></li>
		@endforeach
	</ul>
	@if ($payment->inTrash)
		<a href="/skladki/{{ $payment->id }}/delete" class="button">Usuń składkę</a>
	@else
		<a href="/skladki/{{ $payment->id }}/trash" class="button">Przenieś składkę do kosza</a>
	@endif
	<a href="/skladki/{{ $payment->id }}/pdf" class="button">Pobierz potwierdzenie zamknięcia składki</a>
@endsection
