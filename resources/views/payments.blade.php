@extends("layouts.app")

@section("title", "Składki - samorząd II LO")

@section("content")
    <h1>Twoje składki</h1>
    @foreach($payments as $payment)
        <div class="payment-card">
            <div class="payment-card__content">
                <b>{{ $payment["title"] }}</b>
                <span>{{ $payment["amount"] }}zł</span>
            </div>
            <button class="payment-card__button">Zapłać</button>
        </div>
    @endforeach
@endsection
