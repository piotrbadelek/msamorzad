@extends("layouts.app")

@section("title", "Zapłać BLIKiem za składkę - mSamorząd")

@section("content")
	<h1>Płatność BLIK - Składka {{ $payment->title }}</h1>
	@if($payment->blik_recipient_name == null)
		<p>Dla tej składki nie włączono płatności BLIK.</p>
	@else
		<p>Dla tej składki włączono płatności BLIK. Aby dokonać płatności, otwórz aplikację swojego banku oraz wyślij
			przelew na następujące dane:</p>
		<ul>
			<li>Numer telefonu: <b>{{ $payment->blik_recipient_number }}</b></li>
			<li>Odbiorca: <b>{{ $payment->blik_recipient_name }}</b></li>
			<li>Kwota: <b>{{ $payment->amount }}</b></li>
			<li>Tytuł: <b>{{ $payment->title }}</b></li>
		</ul>
	@endif
	<a href="/skladki" class="button">Cofnij</a>
@endsection
