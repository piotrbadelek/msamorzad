@extends("layouts.app")

@section("title", "Administracja - samorząd II LO")

@section("content")
	<h1>Administracja - klasy</h1>
	<a href="/admin/" class="button">Cofnij</a>
	<a href="/admin/classunit/new" class="button">Nowa klasa</a>
	<p>Kliknij na klasę, aby ją usunąć wraz ze wszystkimi uczniami.</p>
	<ul class="payment-students">
		@foreach($classunits as $classunit)
			<li><a href="/admin/classunit/{{ $classunit->id }}">{{ $classunit->name }}</a></li>
		@endforeach
	</ul>
@endsection
