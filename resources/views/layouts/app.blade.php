<!doctype html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=yes, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>@yield("title")</title>
	<link rel="stylesheet" href="/style.css?v=7">
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
	<meta name="description" content="Aplikacja samorządu uczniowskiego dla szkół podstawowych i ponadpodstawowych">

	<script src="/js/script.js?v=8" defer></script>
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
<nav>
	<ul class="navigation-bar">
		<li @if(Request::is("/")) class="navigation-bar__selected" @endif><a href="/">
				<svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px"
					 fill="currentColor">
					<path
						d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/>
				</svg>
				Start</a></li>
		<li @if(Request::is("skladki") || Request::is("skladki/*")) class="navigation-bar__selected" @endif><a
				href="/skladki">
				<svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px"
					 fill="currentColor">
					<path
						d="M546.67-426.67q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM240-293.33q-27.5 0-47.08-19.59-19.59-19.58-19.59-47.08v-373.33q0-27.5 19.59-47.09Q212.5-800 240-800h613.33q27.5 0 47.09 19.58Q920-760.83 920-733.33V-360q0 27.5-19.58 47.08-19.59 19.59-47.09 19.59H240ZM333.33-360H760q0-39 27.17-66.17 27.16-27.16 66.16-27.16V-640q-39 0-66.16-27.17Q760-694.33 760-733.33H333.33q0 39-27.16 66.16Q279-640 240-640v186.67q39 0 66.17 27.16Q333.33-399 333.33-360ZM800-160H106.67q-27.5 0-47.09-19.58Q40-199.17 40-226.67V-680h66.67v453.33H800V-160ZM240-360v-373.33V-360Z"/>
				</svg>
				Składki</a></li>
		<li @if(Request::is("announcements") || Request::is("announcements/*")) class="navigation-bar__selected" @endif>
			<a
				href="/announcements">
				<svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px"
					 fill="currentColor">
					<path
						d="M726.67-446.67v-66.66H880v66.66H726.67ZM776-160l-123.33-92 40-53.33 123.33 92L776-160Zm-81.33-495.33-40-53.34L776-800l40 53.33-121.33 91.34ZM206.67-200v-160h-60q-27.5 0-47.09-19.58Q80-399.17 80-426.67v-106.66q0-27.5 19.58-47.09Q119.17-600 146.67-600H320l200-120v480L320-360h-46.67v160h-66.66Zm246.66-158v-244L338-533.33H146.67v106.66H338L453.33-358ZM560-346v-268q27 24 43.5 58.5T620-480q0 41-16.5 75.5T560-346ZM300-480Z"/>
				</svg>
				Ogłoszenia</a></li>
		<li @if(Request::is("messages") || Request::is("messages/*")) class="navigation-bar__selected" @endif><a
				href="/messages">
				<svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px"
					 fill="currentColor">
					<path
						d="M653.33-80v-87.78Q598.67-185.33 561-228t-45-98.67h67.33q9.34 43.67 42.84 71.84 33.5 28.16 80.5 28.16h120q22.22 0 37.77 15.56Q880-195.56 880-173.33V-80H653.33Zm113.31-196.67q-31.64 0-54.14-22.53T690-353.36q0-31.64 22.53-54.14T766.7-430q31.63 0 54.13 22.53 22.5 22.53 22.5 54.17 0 31.63-22.53 54.13-22.53 22.5-54.16 22.5ZM380-413.33q0-134 93-227t227-93v66.66q-106.33 0-179.83 73.5-73.5 73.5-73.5 179.84H380Zm133.33 0q0-77.67 54.6-132.17Q622.53-600 700-600v66.67q-50 0-85 35t-35 85h-66.67ZM80-530v-93.33q0-22.23 15.83-37.78 15.84-15.56 37.5-15.56h120q47 0 80.5-28.16 33.5-28.17 42.84-71.84H444q-7.33 56-45 98.67t-92.33 60.22V-530H80Zm113.3-196.67q-31.63 0-54.13-22.53-22.5-22.53-22.5-54.16 0-31.64 22.53-54.14t54.16-22.5q31.64 0 54.14 22.53T270-803.3q0 31.63-22.53 54.13-22.53 22.5-54.17 22.5Z"/>
				</svg>
				Kontakt</a></li>
	</ul>
</nav>
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
