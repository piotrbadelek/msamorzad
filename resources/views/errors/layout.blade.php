<!doctype html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=yes, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>@yield("error")</title>
	<link rel="stylesheet" href="/style.css?v=7">
	<link rel="manifest" href="/manifest.json">
	<link rel="icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/img/touch/256.png">
	<link rel="apple-touch-startup-image" href="/img/touch/256.png">
	<meta name="apple-mobile-web-app-title" content="mSamorząd">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="theme-color" content="#505B92">
	<meta name="description" content="Aplikacja samorządu uczniowskiego dla szkół podstawowych i ponadpodstawowych">
</head>
<body>
<header>
	<a href="/"><b>mSamorząd</b></a>
</header>
<main>
	<h1>@yield("error")</h1>
	<h2>@yield("explanation")</h2>
	<a href="/">Powróć do ekranu startowego.</a>

	<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" class="error-svg"
		 fill="currentColor">
		@yield("svgContent")
	</svg>
</main>
</body>
</html>
