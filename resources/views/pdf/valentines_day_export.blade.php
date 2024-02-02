<!doctype html>
<html lang="pl" dir="ltr">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport"
		  content="width=device-width, user-scalable=yes, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
	<title>Eksport listów poczty walentynkowej</title>
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
			text-align: center;
			width: 100%;
		}

		table {
			border-collapse: collapse;
		}

		td,
		th {
			border: 1px solid black;
			padding: 10px 20px;
		}

		p {
			margin: 0 .5rem;
		}
	</style>
</head>
<body>
<h1>mSamorząd</h1>
<h2>Listy wysłane pocztą walentynkową</h2>
<table>
	@for ($i = 0; $i < count($messages); $i += 2)
		<tr>
			<td>Dla: {{ $messages[$i]->recipient }}</td>
			<td>Dla: {{ $messages[$i + 1]->recipient }}</td>
		</tr>
		<tr>
			<td>{{ $messages[$i]->content }}</td>
			<td>{{ $messages[$i + 1]->content }}</td>
		</tr>
	@endfor
</table>
<footer>{{ (new DateTime())->format("Y-m-d H:i:s") }} | msamorzad.pl | Wygenerowano za pomocą wersji {{ config("app.version") }}</footer>
</body>
</html>
