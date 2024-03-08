@extends("layouts.app")

@section("title", "Twoja klasa - Samorząd II LO")

@section("content")
	<h1>Twoja klasa</h1>
	<x-start-card name="Uczniowie" imageFile="users" url="tutor/students"/>
	<x-start-card name="Uczniowie zalegający z płatnościami" imageFile="statistics" url="tutor/student-payment-stats"/>
	<x-start-card name="Cofnij" imageFile="backwards" url=""/>
@endsection
