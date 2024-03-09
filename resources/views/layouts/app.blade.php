<!doctype html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=yes, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>@yield("title")</title>
	<link rel="stylesheet" href="/style.css?v=6">
	<link rel="manifest" href="/manifest.json">
	{{-- Do not load sentry in dev envrioments --}}
	@if (!config("app.debug"))
		<script
			src="https://js.sentry-cdn.com/aabbc2c94ac8133dcdb4610c04d3395d.min.js"
			crossorigin="anonymous"
		></script>
		<script src="/js/turbo.min.js" type="module"></script>
	@endif
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/img/touch/128.png">
	<link rel="apple-touch-startup-image" href="/img/touch/128.png">
	<meta name="apple-mobile-web-app-title" content="mSamorząd">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="theme-color" content="#505B92">
	<meta name="description" content="Aplikacja samorządu uczniowskiego dla II LO w Tomaszowie Mazowieckim">

	<script src="/js/script.js?v=7" defer></script>
	<script src="/sw.js" defer></script>
	@yield("scripts")
</head>
<body>
<header>
	<a href="/"><b>mSamorząd</b></a>
</header>
<main>
	@yield("content")
</main>
<dialog id="notificationDialogue">
	<header>Włącz powiadomienia</header>
	<p>Włącz powiadomienia, aby otrzymywać:</p>
	<ul>
		<li>Przypomenienia o składkach</li>
		<li>Informacje o nowych składkach</li>
		<li>Ogłoszenia samorządu uczniowskiego</li>
		<li>Ogłoszenia konkursów</li>
		<li>Odpowiedzi na zadane pytania</li>
	</ul>
	<button id="enableNotifications">Włącz</button>
	<button class="not-primary" id="disableNotifications">Nie teraz</button>
</dialog>

<dialog id="installDialogue">
	<header>Zainstaluj aplikację</header>
	<p>Zainstaluj aplikację, aby dodać ją do ekranu domowego</p>
	<button id="installButton">Zainstaluj</button>
	<button class="not-primary" id="dontInstallApp">Nie teraz</button>
</dialog>
</body>
</html>
