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
			@endif
			@if($inTrash)
				| W koszu
			@endif
		</span>
	</div>

	@if ($userCanViewPaymentDetails)
		@if($payment->blik_recipient_number != null)
			<a href="/skladki/{{ $payment->id }}/blik" class="button button-margin">BLIK</a>
		@endif
		<a href="/skladki/{{ $payment->id }}" class="button">Szczegóły</a>
	@else
		@if (in_array($user->id, json_decode($payment["paid"])))
			<button class="button" disabled>Opłacone</button>
		@else
			@if($payment->blik_recipient_number != null)
				<a href="/skladki/{{ $payment->id }}/blik" class="button">BLIK</a>
			@endif
		@endif
	@endif
</div>
