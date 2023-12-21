@extends("layouts.app")

@section("title", "Samorząd II LO")

@section("content")
	@unless($user->isTeacher && $user->notManagingAClass)
		<a href="/skladki" class="card-container">
			<div class="card">
				<img src="/img/money.webp" alt="Składki">
				<p class="card_content">Składki</p>
			</div>
		</a>
	@endunless
	<a href="/contests" class="card-container">
		<div class="card">
			<img src="/img/contests.webp" alt="Konkursy">
			<p class="card_content">Konkursy</p>
		</div>
	</a>
	<a href="/announcements" class="card-container">
		<div class="card">
			<img src="/img/ogloszenia.webp" alt="Ogłoszenia">
			<p class="card_content">Ogłoszenia</p>
		</div>
	</a>
	<a href="/messages" class="card-container">
		<div class="card">
			<img src="/img/contact.webp" alt="Kontakt">
			<p class="card_content">Kontakt</p>
		</div>
	</a>
	<a href="https://forms.gle/VYPbwsUhNkTpNJef9" class="card-container">
		<div class="card">
			<img src="/img/bugReport.webp" alt="Zgłoś błąd">
			<p class="card_content">Zgłoś problem / sugestię</p>
		</div>
	</a>
	<form action="/logout" method="post">
		@csrf
		<button type="submit">Wyloguj</button>
	</form>
	<footer class="appver">mSamorząd {{ config("app.version") }} (on PHP {{ phpversion() }})</footer>
@endsection
