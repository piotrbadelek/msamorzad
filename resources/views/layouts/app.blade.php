<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield("title")</title>
    <link rel="stylesheet" href="/style.css">
	<link rel="manifest" href="/manifest.json">
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
		<button onclick="enableNotifications();">Włącz</button>
		<button class="not-primary" onclick="disableNotifications();">Nie teraz</button>
	</dialog>

	<dialog id="installDialogue">
		<header>Zainstaluj aplikację</header>
		<p>Zainstaluj aplikację, aby dodać ją do ekranu domowego</p>
		<button onclick="installApp();" id="installButton">Zainstaluj</button>
		<button class="not-primary" onclick="dontInstallApp();">Nie teraz</button>
	</dialog>
	<script src="/script.js" defer></script>
	<script src="/sw.js" defer></script>
</body>
</html>
