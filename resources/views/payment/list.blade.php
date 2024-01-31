@extends("layouts.app")

@section("title", "Składki - samorząd II LO")

@section("content")
	@if($user->can("create", \App\Models\Payment::class))
		<h1>Składki w klasie</h1>
		<a href="/skladki/new" class="button" id="new_payment">Nowa składka</a>
	@else
		<h1>Twoje składki</h1>
	@endif
	@if (sizeof($payments) > 0 || sizeof($paymentsInTrash) > 0)
		@foreach($payments as $payment)
			<x-payment-card :payment="$payment" :user="$user"/>
		@endforeach

		@if (sizeof($paymentsInTrash) > 0)
			<h2>Składki w koszu</h2>
			@foreach($paymentsInTrash as $payment)
				<x-payment-card :payment="$payment" :user="$user" :inTrash="true"/>
			@endforeach
		@endif
	@else
		<x-no-entries type="payment"/>
	@endif
@endsection
