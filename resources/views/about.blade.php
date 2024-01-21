@extends("layouts.app")

@section("title", "Samorząd II LO")

@section("content")
	<h1>O aplikacji</h1>
	<div class="about-app-header">
		<span class="about-app-name">mSamorząd</span>
		<h2>Wersja {{ config("app.version") }}</h2>
	</div>
	<a href="https://pomoc.msamorzad.pl" class="about-link">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
			<path
				d="M478-240q21 0 35.5-14.5T528-290q0-21-14.5-35.5T478-340q-21 0-35.5 14.5T428-290q0 21 14.5 35.5T478-240Zm-36-154h74q0-33 7.5-52t42.5-52q26-26 41-49.5t15-56.5q0-56-41-86t-97-30q-57 0-92.5 30T342-618l66 26q5-18 22.5-39t53.5-21q32 0 48 17.5t16 38.5q0 20-12 37.5T506-526q-44 39-54 59t-10 73Zm38 314q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/>
		</svg>
		<span>Uzyskaj pomoc</span></a>
	<a href="/oobe" class="about-link">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
			<path
				d="M686-132 444-376q-20 8-40.5 12t-43.5 4q-100 0-170-70t-70-170q0-36 10-68.5t28-61.5l146 146 72-72-146-146q29-18 61.5-28t68.5-10q100 0 170 70t70 170q0 23-4 43.5T584-516l244 242q12 12 12 29t-12 29l-84 84q-12 12-29 12t-29-12Zm29-85 27-27-256-256q18-20 26-46.5t8-53.5q0-60-38.5-104.5T386-758l74 74q12 12 12 28t-12 28L332-500q-12 12-28 12t-28-12l-74-74q9 57 53.5 95.5T360-440q26 0 52-8t47-25l256 256ZM472-488Z"/>
		</svg>
		<span>Skonfiguruj aplikację ponownie</span></a>
	<details>
		<summary>Kontakt z deweloperami</summary>
		Sugestie oraz błędy: <a
			href="https://forms.gle/VYPbwsUhNkTpNJef9">https://forms.gle/VYPbwsUhNkTpNJef9</a><br><br>
		Prosimy o zgłaszanie wszelkich potencjalnych luk bezpieczeństwa: <a
			href="mailto:piotrbadelek@proton.me?subject=mSamorząd - [Bezpieczeństwo]">piotrbadelek@proton.me</a>
	</details>

	<details>
		<summary>Polityka prywatności</summary>
		Aplikacja mSamorząd nie zbiera informacji o użytkowniku podczas użytkowania aplikacji. <br>
		Dane użytkowników są przechowywane na serwerach zlokalizowanych na terytorium Unii Europejskiej. <br>
		Wraz z zarejestrowaniem użytkownika w aplikacji zapisywane są następujące dane:
		<ul>
			<li>Imię i nazwisko</li>
			<li>Nazwa użytkownika</li>
			<li>Klasa</li>
		</ul>
		Aplikacja wykorzystuje bibliotekę <b>Sentry</b> firmy <i>Functional Software, inc.</i> w celu rejestrowania błędów aplikacji oraz poprawiania jej jakości. Sentry nie zbiera żadnych informacji osobistych o użytkowniku.<br>
		<a href="https://sentry.io/trust/privacy/">Informacje o przetwarzaniu danych przez Sentry.</a>
	</details>
@endsection
