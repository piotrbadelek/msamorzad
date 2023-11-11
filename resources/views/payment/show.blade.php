@extends("layouts.app")

@section("title", "Składki - samorząd II LO")

@section("content")
    <h1>Składka {{ $payment->title }}</h1>
    <p>Suma: {{ $payment->amount }} zł</p>
    <b>Zapłaciło:</b>
    <ul>
        @foreach(json_decode($payment->paid) as $user)
            <li><a href="/skladki/1/{{ $user }}">{{ \App\Models\User::where("id", $user)->first()->name }}</a></li>
        @endforeach
    </ul>
    <b>Nie zapłaciło:</b>
    <ul>
        @foreach($not_paid as $user)
            <li><a href="/skladki/1/{{ $user->id }}">{{ $user->name }}</a></li>
        @endforeach
    </ul>
@endsection
