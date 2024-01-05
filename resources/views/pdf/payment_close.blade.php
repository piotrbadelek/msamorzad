<!doctype html>
<html lang="pl" dir="ltr">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport"
		  content="width=device-width, user-scalable=yes, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
	<title>Potwierdzenie zamknięcia składki</title>
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
<h2>Potwierdzenie zamknięcia składki</h2>
<p class="summary">Nazwa składki: {{ $payment->title }}</p>
<p class="summary">Wysokość składki: {{ $payment->amount }} zł na osobę | łącznie {{ $payment->amount * 35 }} zł</p>
<p class="summary">Opłaciło: {{ count($paid) }} na {{ count($paid) + count($not_paid) }} osób</p>
<p class="summary">Łącznie wpłacono {{ count($paid) * $payment->amount }} zł</p><br>
<p class="paid">Wpłacili:</p>

@if (count($paid) > 0)
<p>@foreach($paid as $user)
		{{ $user->name }},
	@endforeach</p>
@else
	<p><i>Brak</i></p>
@endif
<br>
<p class="paid">Nie wpłacili:</p>

@if (count($not_paid) > 0)
<p>@foreach($not_paid as $user)
		{{ $user["name"] }},
	@endforeach</p>
@else
	<p><i>Brak</i></p>
@endif
<p class="signature">Podpis skarbnika</p>
<footer>{{ (new DateTime())->format("Y-m-d H:i:s") }} | msamorzad.pl | Wygenerowano za pomocą wersji {{ config("app.version") }}</footer>
</body>
</html>
