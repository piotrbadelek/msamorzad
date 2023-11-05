@extends("layouts.app")

@section("title", "Składki - samorząd II LO")

@section("content")
    <h1>Twoje składki</h1>
    @foreach($payments as $payment)
        <div class="payment-card">
            <div class="payment-card__content">
                <b>{{ $payment["title"] }}</b>
                @php
                    $currentDate = new DateTime();
					$diffDate = $currentDate->diff(new DateTime($payment["deadline"]));
                @endphp
                <span>{{ $payment["amount"] }}zł | Pozostało {{ $diffDate->d + ($diffDate->m * 30) }} dni
                    @if($user->isAdmin) | Zapłaciło {{ sizeof(json_decode($payment["paid"])) }} @endif</span>
            </div>

            @if ($user->isAdmin)
                <a href="/skladki/{{ $payment->id }}" class="payment-card__button">Szczegóły</a>
            @else
                @if (!in_array($user->id, json_decode($payment["paid"])))
                    <button class="payment-card__button">Zapłać</button>
                @else
                    <button class="payment-card__button" disabled>Opłacone</button>
                @endif
            @endif
        </div>
    @endforeach
@endsection
