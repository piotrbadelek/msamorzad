@extends("layouts.app")

@section("title", "Wiadomości - mSamorząd")

@section("content")
	<h1>Kontakt</h1>
	<form action="/messages" method="POST" data-turbo="false">
		@csrf
		<p>Wyślij zapytanie do samorządu szkolnego</p>
		<label for="question">Treść</label><br>
		<textarea name="question" id="question" cols="30" rows="10" maxlength="800" required></textarea>
		<button type="submit">Wyślij</button>
	</form>
	<label for="messageSearch" class="search-label">Wyszukaj pytanie</label>
	<input type="search" name="messageSearch" id="messageSearch">
	@if (sizeof($messages) > 0)
		@foreach($messages as $message)
			@if($isSamorzadSzkolny)
				<x-message :id="$message->id" :question="$message->question" :response="$message->response"
						   :username="$message->user->name"></x-message>
			@else
				@if ($message->response)
					<x-message :id="$message->id" :question="$message->question"
							   :response="$message->response"></x-message>
				@endif
			@endif
		@endforeach
	@else
		<x-no-entries type="message"/>
	@endif
@endsection
