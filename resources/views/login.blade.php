@extends("layouts.app")

@section("title", "Login - samorząd II LO")

@section("content")
    <h1>Zaloguj</h1>
    @if($errors->any())
        <h2>{{$errors->first()}}</h2>
    @endif
    <form action="/login" method="post">
        @csrf
        <label for="username">Nazwa użytkownika</label>
        <input type="text" name="username" id="username">
        <label for="password">Hasło</label>
        <input type="password" name="password" id="password">
        <button type="submit">Zaloguj</button>
    </form>
@endsection
