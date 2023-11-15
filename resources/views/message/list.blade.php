@extends("layouts.app")

@section("title", "Wiadomości - samorząd II LO")

@section("content")
	<h1>Kontakt</h1>
	<form action="/messages" method="POST">
		@csrf
		<p>Wyślij zapytanie</p>
		<label for="question">Treść</label><br>
		<textarea name="question" id="question" cols="30" rows="10" maxlength="800" required></textarea><br>
		<button type="submit">Wyślij</button>
	</form>
	@foreach($messages as $message)
		<a href="/messages/{{ $message->id }}" class="message_container">
			<div class="message">
				<header>{{ $message->user->name }}</header>
				<p>{{ $message->question }}</p>
				@if($message->response)
					<div class="message_answer"><span>Odpowiedź samorządu:</span> <br>{{ $message->response }}</div>
				@endif
			</div>
		</a>
	@endforeach
@endsection
