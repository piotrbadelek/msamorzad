@extends("layouts.app")

@section("title", "Administracja - Samorząd II LO")

@section("content")
	<h1>Administracja - statystyki</h1>
	<x-start-card name="Aktywni użytkownicy" imageFile="users" url="admin/stats/active-users"/>
	<x-start-card name="Laravel Pulse" imageFile="statistics" url="pulse"/>
	<x-start-card name="Wyjdź" imageFile="backwards" url="admin"/>
	<footer class="appver">mSamorząd {{ config("app.version") }} (on PHP {{ phpversion() }})</footer>
@endsection
