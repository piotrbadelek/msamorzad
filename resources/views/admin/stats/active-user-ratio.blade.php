@extends("layouts.app")

@section("title", "Aktywni użytkownicy - Samorząd II LO")

@section("content")
	<h1>Statystyki - aktywni użytkownicy</h1>
	<a href="/" class="button">Cofnij</a>
	<table>
		<thead>
		<tr>
			<th>Procent niezalogowanych</th>
			<th>Klasa</th>
		</tr>
		</thead>
		<tbody>
		@foreach($stats as $stat)
			<tr>
				<td>{{ $stat->notActivePercentage }}</td>
				<td>{{ $stat->classUnitName }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@endsection
