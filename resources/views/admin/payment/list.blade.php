@extends("layouts.app")

@section("title", "Składki - mSamorząd")

@section("content")
	<h1>Administracja - składki</h1>
	@foreach($classunitsWithPayments as $classunitWithPayments)
		<h2>{{ $classunitWithPayments["name"] }}</h2>
		@if (sizeof($classunitWithPayments["payments"]) > 0)
			@foreach($classunitWithPayments["payments"] as $payment)
				<x-payment-card :payment="$payment" :user="$user" :inTrash="$payment->inTrash"/>
			@endforeach
		@else
			<p>Brak składek w danej klasie.</p>
		@endif
	@endforeach
@endsection
