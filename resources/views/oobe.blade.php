@extends("layouts.app")

@section("title", "Samorząd II LO")

@section("content")
	<div class="oobe-view" id="view2">
		<img src="/img/oobe/welcome.webp" alt="Witaj!">
		<div class="oobe-view__content">
			<h1>Witaj w aplikacji mSamorząd!</h1>
			<h2>Ten przewodnik pomoże ci skonfigurować aplikację mSamorząd. Kliknij dalej aby rozpocząć.</h2>
			<button>Dalej</button>
		</div>
	</div>

	<div class="oobe-view" id="view1" hidden>
		<header>Zainstaluj aplikację</header>
		<div id="autoInstall"></div>
		<div id="firefoxAndroidInstall"></div>
		<div id="safariInstall"></div>
	</div>
@endsection
