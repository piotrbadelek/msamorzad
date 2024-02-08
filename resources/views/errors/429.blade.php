@extends('errors.layout')

@section("error", "429 Too Many Requests")
@section("explanation", "Wysyłasz zbyt szybko zapytania do serwera. Zwolnij, spróbuj ponownie za kilka minut.")
@section("svgContent")
	<path
		d="m612-292 56-56-148-148v-184h-80v216l172 172ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/>
@endsection
