@extends("layouts.app")

@section("title", "Twoja klasa - mSamorząd")

@section("content")
	<h1>Twoja klasa</h1>
	<x-start-card name="Zarządzanie uczniami" imageFile="users" url="tutor/students"/>
	<x-start-card name="Uczniowie zalegający z płatnościami" imageFile="lateStudents"
				  url="tutor/student-payment-stats"/>
	<x-start-card name="Cofnij" imageFile="backwards" url=""/>
@endsection
