@extends("layouts.app")

@section("title", "Poczta walentynkowa - mSamorząd")

@section("content")
	<h1>Poczta walentynkowa</h1>

	@if (\Illuminate\Support\Facades\Session::has("confirmation"))
		<div class="info-alert">
			<p>List został pomyślnie wysłany!</p>
		</div>
	@endif

	<div class="info-alert">
		@if ($user->isAdministrator)
			@if (\App\Utilities\EventActivation::isValentinesDayEventActive())
				<p>Poczta walentynkowa trwa od 10 do 20:00 13 lutego. Uczniowie obecnie widzą tą zakładkę i mogą wysyłać
					wiadomości.</p>
			@else
				<p>Poczta walentynkowa nie jest obecnie aktywna - będzie trwać od 10 do 20:00 13 lutego. Uczniowie
					obecnie nie widzą tej zakładki.</p>
			@endif
		@else
			<p>Poczta walentynkowa trwa od 10 do 20:00 13 lutego.</p>
		@endif
	</div>

	<p>Zapraszamy do skorzystania z poczty walentynkowej w aplikacji mSamorząd. Możesz również wysłać wiadomość za
		pomocą skrzynki znajdującej się w szkole. Zapewniamy anonimowość dla odbiorcy wiadomości.<br>
		Możesz również wysłać wiadomość do losowej osoby w szkole - w tym celu pozostaw pole "odbiorca" pustym.</p>

	<form action="/valentine" method="POST">
		@csrf

		@if($errors->any())
			<b>{{$errors->first()}}</b>
		@endif

		<p>Wyślij wiadomość</p>
		<label for="recipient">Odbiorca (pamiętaj o podaniu klasy!)</label>
		<input type="text" name="recipient" id="recipient" maxlength="128">
		<label for="content">Treść listu</label><br>
		<textarea name="content" id="content" cols="30" rows="10" maxlength="960" required></textarea>
		<button type="submit">Wyślij</button>
	</form>

	@if ($user->isSamorzadSzkolny)
		<br><a href="/valentine/export" class="button button-margin">Pobierz listy do druku</a>
	@endif
@endsection
