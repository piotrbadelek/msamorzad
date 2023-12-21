<a href="@if($username)/messages/{{ $id }}@else javascript:void(0)@endif" data-message="{{ $question }}" class="message_container">
	<div class="message">
		@if($username)
			<header>{{ $username }}</header>
		@endif
		<p>{{ $question }}</p>
		@if($response)
			<div class="message_answer"><span>Odpowiedź samorządu:</span> <br>{{ $response }}</div>
		@endif
	</div>
</a>
