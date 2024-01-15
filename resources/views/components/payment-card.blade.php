<div class="payment-card">
	<div class="payment-card__content">
		<b>{{ $payment["title"] }}</b>
		<span>{{ $payment["amount"] }}zł |
					@if($hasExpired)
				{{ $diffDate->d + ($diffDate->m * 30) }} dni po terminie
			@else
				Pozostało {{ $diffDate->d + ($diffDate->m * 30) }} dni
			@endif
			@if($userCanViewPaymentDetails)
				| Zapłaciło {{ sizeof(json_decode($payment["paid"])) }}
			@endif</span>
	</div>

	@if ($userCanViewPaymentDetails)
		<a href="/skladki/{{ $payment->id }}" class="button">Szczegóły</a>
	@else
		@if (in_array($user->id, json_decode($payment["paid"])))
			<button class="button" disabled>Opłacone</button>
		@endif
	@endif
</div>
