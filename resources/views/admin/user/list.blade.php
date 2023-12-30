@extends("layouts.app")

@section("title", "Administracja - samorząd II LO")

@section("content")
    <h1>Administracja - użytkownicy</h1>
	<a href="/admin/" class="button">Cofnij</a>
	<a href="/admin/user/new" class="button">Nowy użytkownik</a>
	<ul class="payment-students">
		@foreach($users as $user)
			<li><a href="/admin/user/{{ $user->id }}">{{ $user->name }}</a></li>
		@endforeach
	</ul>
@endsection
