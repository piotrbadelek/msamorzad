@extends("layouts.app")

@section("title", "Samorząd II LO")

@section("content")
	<div class="oobe-view" id="view0" data-dont-show-notif-prompt>
		<img src="/img/oobe/welcome.webp" alt="Witaj!">
		<div class="oobe-view__content">
			<h1>Witaj w aplikacji mSamorząd!</h1>
			<h2>Ten przewodnik pomoże ci skonfigurować aplikację mSamorząd. Kliknij dalej aby rozpocząć.</h2>
			<button id="beginOobe">Dalej</button>
		</div>
	</div>

	<div class="oobe-view hidden" id="view1">
		<img src="/img/oobe/install.webp" alt="Zainstaluj">
		<div class="oobe-view__content">
			<header>Zainstaluj aplikację</header>
			<p>Zainstaluj aplikację mSamorząd, aby dodać ją do ekranu domowego.</p>
			<div id="autoInstall">
				<button data-oobe="installButton">Zainstaluj</button>
				<button class="not-primary" data-oobe="notNowButton">Nie teraz</button>
			</div>
			<div id="firefoxAndroidInstall">
				<small><i>Ponieważ wykryliśmy, że korzystasz z Firefoxa na Androidzie:</i></small>
				<p>Aby zainstalować aplikację mSamorząd otwórz menu podręczne, po czym wybierz zainstaluj.</p>
				<button data-oobe="continueButton">Dalej</button>
				<button class="not-primary" data-oobe="notNowButton">Nie teraz</button>
			</div>
			<div id="safariInstall">
				<small><i>Ponieważ wykryliśmy, że korzystasz z iOS'a:</i></small>
				<p>Aby zainstalować aplikację mSamorząd, otwórz menu "udostępnij", a następnie dodaj do ekranu głównego.</p>
				<button data-oobe="continueButton">Dalej</button>
				<button class="not-primary" data-oobe="notNowButton">Nie teraz</button>
			</div>
		</div>
	</div>

	<div class="oobe-view hidden" id="view2">
		<img src="/img/oobe/notifications.webp" alt="Powiadomienia">
		<div class="oobe-view__content">
			<p>Włącz powiadomienia, aby otrzymywać:</p>
			<ul>
				<li>Przypomenienia o składkach</li>
				<li>Informacje o nowych składkach</li>
				<li>Ogłoszenia samorządu uczniowskiego</li>
				<li>Ogłoszenia konkursów</li>
				<li>Odpowiedzi na zadane pytania</li>
			</ul>
			<button data-oobe="enableNotifications">Włącz</button>
			<button class="not-primary" data-oobe="disableNotifications">Nie teraz</button>
		</div>
	</div>

	<div class="oobe-view hidden" id="view3">
		<div class="oobe-view__content">
			<header>Aplikacja mSamorząd została pomyślnie skonfigurowana.</header>
			<p>Naciśnij Zakończ aby rozpocząć z niej korzystanie.</p>
			<a href="/" class="button">Zakończ</a>
		</div>
	</div>
@endsection

@section("scripts")
	<script src="/js/oobe.js?v=2" defer></script>
@endsection
