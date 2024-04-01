<!doctype html>
<html lang="pl" dir="ltr">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport"
		  content="width=device-width, user-scalable=yes, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
	<title>Uczniowie zalegający z płatnościami</title>
	<style>
		h1 {
			font-size: 48px;
			margin-top: 0;
		}

		h2 {
			font-size: 32px;
		}

		body {
			font-family: Inter, sans-serif;
		}

		footer {
			position: fixed;
			bottom: 0;
			text-align: center;
			width: 100%;
		}

		.summary {
			font-size: 20px;
		}

		.paid {
			margin-bottom: 10px;
			font-size: 18px;
		}

		.signature {
			margin-top: 96px;
		}

		p {
			margin: 0 .5rem;
		}

		/*
				@font-face {
					font-family: "Inter";
					font-style: normal;
					font-weight: 400;
					src: url("http://127.0.0.1:8000/fonts/Inter-Regular.ttf") format("truetype");
				}

				@font-face {
					font-family: "Inter";
					font-style: normal;
					font-weight: 700;
					src: url("inter-v13-latin-ext-700.ttf") format("truetype");
				}*/
	</style>
</head>
<body>
<h1>mSamorząd</h1>
<h2>Uczniowie zalegający z płatnościami w klasie {{ $classunit_name }}</h2>
<p>@foreach($users as $user)
		<li>{{ $user->name }} - łącznie {{ $user->total_late_days }} spóżnionych dni</li>
	@endforeach</p>
<footer>{{ (new DateTime())->format("Y-m-d H:i:s") }} | msamorzad.pl | Wygenerowano za pomocą
	wersji {{ config("app.version") }}</footer>
</body>
</html>
