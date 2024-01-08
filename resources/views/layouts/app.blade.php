<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield("title")</title>
    <link rel="stylesheet" href="/style.css?v=2">
	<link rel="manifest" href="/manifest.json">
	<script
		src="https://js.sentry-cdn.com/aabbc2c94ac8133dcdb4610c04d3395d.min.js"
		crossorigin="anonymous"
	></script>
	<meta name="csrf-token" content="{{ csrf_token() }}">
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
		<button onclick="enableNotifications();" id="enableNotifications">Włącz</button>
		<button class="not-primary" onclick="disableNotifications();">Nie teraz</button>
	</dialog>

	<dialog id="installDialogue">
		<header>Zainstaluj aplikację</header>
		<p>Zainstaluj aplikację, aby dodać ją do ekranu domowego</p>
		<button id="installButton">Zainstaluj</button>
		<button class="not-primary" onclick="dontInstallApp();">Nie teraz</button>
	</dialog>
	<script src="/js/script.js?v=2" defer></script>
	<script src="/js/sw.js" defer></script>
	@yield("scripts")
</body>
</html>
