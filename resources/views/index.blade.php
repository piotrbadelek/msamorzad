@extends("layouts.app")

@section("title", "Samorząd II LO")

@section("content")
	<div class="outdated-ios-info">
		<header>Zaaktualizuj system operacyjny</header>
		<p>Twój telefon działa na przestarzałej wersji systemu iOS. Zaaktualizuj system operacyjny aby aplikacja
			mSamorząd mogła wysyłać powiadomienia.</p>
	</div>

	@unless($user->isTeacher && $user->notManagingAClass)
		<x-start-card name="Składki" imageFile="money" url="skladki"/>
	@endunless
	<x-start-card name="Konkursy" imageFile="contests" url="contests"/>
	<x-start-card name="Ogłoszenia" imageFile="ogloszenia" url="announcements"/>
	<x-start-card name="Kontakt" imageFile="contact" url="messages"/>
	<x-start-card name="O aplikacji" imageFile="about" url="about"/>
	@if($user->isTutor)
		<x-start-card name="Twoja klasa" imageFile="classunit" url="tutor/students"/>
	@endif

	@if(!$user->isTeacher && \App\Utilities\EventActivation::isValentinesDayEventActive())
		<x-start-card name="Poczta walentynkowa" imageFile="valentinemail" url="valentine"/>
	@endif

	@if($user->isAdministrator)
		<x-start-card name="Administracja" imageFile="admin" url="admin"/>
	@endif
	<form action="/logout" method="post">
		@csrf
		<button type="submit">Wyloguj</button>
	</form>
	<footer class="appver">mSamorząd {{ config("app.version") }} (on PHP {{ phpversion() }})</footer>
@endsection
