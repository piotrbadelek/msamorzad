<!doctype html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=yes, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>@yield("error")</title>
	<link rel="manifest" href="/manifest.json">
	<link rel="icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/img/touch/128.png">
	<link rel="apple-touch-startup-image" href="/img/touch/128.png">
	<meta name="apple-mobile-web-app-title" content="mSamorząd">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="theme-color" content="#505B92">
	<meta name="description" content="Aplikacja samorządu uczniowskiego dla II LO w Tomaszowie Mazowieckim">
	<style>
		:root {
			--md-error: rgb(186 26 26);
			--md-surface: rgb(251 248 255);
			--md-on-surface: rgb(27 27 33);
		}

		body {
			font-family: Inter, -system-ui, -apple-system, "Segoe UI", Roboto, Ubuntu, sans-serif;
			line-height: 1.625;
			background-color: var(--md-surface);
			color: var(--md-on-surface);
		}

		main {
			width: 90vw;
			margin: 0 auto;
			min-height: 90vh;
			position: relative;
		}

		header {
			text-align: center;
			margin-top: 1.2rem;
			margin-bottom: 2rem;
		}

		header a {
			text-decoration: none;
			color: inherit;
		}

		.error-svg {
			color: var(--md-error);
			position: fixed;
			bottom: -64px;
			right: -64px;
			width: 300px;
			height: 300px;
		}

		/* Media queries */

		@media (prefers-color-scheme: dark) {
			:root {
				--md-error: rgb(255 180 171);
				--md-surface: rgb(18 19 24);
				--md-on-surface: rgb(227 225 233);
			}

			a {
				color: lightblue;
			}
		}

		/* Fonts */

		/* inter-regular - latin */
		@font-face {
			font-display: swap;
			font-family: "Inter";
			font-style: normal;
			font-weight: 400;
			src: url("/fonts/inter-v13-latin-ext-regular.woff2") format("woff2");
		}

		/* inter-700 - latin */
		@font-face {
			font-display: swap;
			font-family: "Inter";
			font-style: normal;
			font-weight: 700;
			src: url("/fonts/inter-v13-latin-ext-700.woff2") format("woff2");
		}
	</style>
</head>
<body>
<header>
	<a href="/"><b>mSamorząd</b></a>
</header>
<main>
	<h1>Brak połączenia z internetem.</h1>
	<h2>Aplikacja mSamorząd wymaga połączenia z internetem do prawidłowego funkcjonowania. Wróć do trybu online i
		naciśnij "Powróć do ekranu startowego" aby spróbować ponownie.</h2>
	<a href="/">Powróć do ekranu startowego.</a>

	<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" class="error-svg"
		 fill="currentColor">
		<path
			d="M790-56 414-434q-47 11-87.5 33T254-346l-84-86q32-32 69-56t79-42l-90-90q-41 21-76.5 46.5T84-516L0-602q32-32 66.5-57.5T140-708l-84-84 56-56 736 736-58 56Zm-310-64q-42 0-71-29.5T380-220q0-42 29-71t71-29q42 0 71 29t29 71q0 41-29 70.5T480-120Zm236-238-29-29-29-29-144-144q81 8 151.5 41T790-432l-74 74Zm160-158q-77-77-178.5-120.5T480-680q-21 0-40.5 1.5T400-674L298-776q44-12 89.5-18t92.5-6q142 0 265 53t215 145l-84 86Z"/>
	</svg>
</main>
</body>
</html>
