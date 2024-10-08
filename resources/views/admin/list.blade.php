@extends("layouts.app")

@section("title", "Administracja - mSamorząd")

@section("content")
	<h1>Administracja</h1>
	<x-start-card name="Użytkownicy" imageFile="users" url="admin/user"/>
	<x-start-card name="Klasy" imageFile="classunit" url="admin/classunit"/>
	<x-start-card name="Składki" imageFile="money" url="admin/payments"/>
	<x-start-card name="Wydarzenia ograniczone czasowo" imageFile="timeLimitedEvents" url="admin/events"/>
	<x-start-card name="Statystyki" imageFile="statistics" url="admin/stats"/>
	<x-start-card name="Wyjdź" imageFile="backwards" url=""/>
	<footer class="appver">mSamorząd {{ config("app.version") }} (on PHP {{ phpversion() }})</footer>
@endsection
