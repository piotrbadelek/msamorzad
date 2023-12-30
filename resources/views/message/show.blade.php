@extends("layouts.app")

@section("title", "Wiadomości - samorząd II LO")

@section("content")
	<h1>Odpowiedz</h1>
	<div class="message">
		<header>{{ $message->user->name }}</header>
		<p>{{ $message->question }}</p>
	</div>
	<form action="/messages/{{ $message->id }}" method="POST">
		@csrf
		@method("patch")
		<label for="response">Treść odpowiedzi</label><br>
		<textarea name="response" id="response" cols="30" rows="10" maxlength="800" required></textarea><br>
		<button type="submit">Wyślij</button>
	</form>
	<br>

	<a href="/messages/{{ $message->id }}/delete" class="button">Usuń wiadomość</a>
@endsection
