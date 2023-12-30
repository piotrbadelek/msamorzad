@extends("layouts.app")

@section("title", "Wymagana autoryzacja - samorząd II LO")

@section("content")
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<h1>Wymagana autoryzacja</h1>
	<p>Aby wykonać operację, potrzebne jest twoje hasło.</p>
	<form action="/confirm-password" method="post">
		@csrf
		<label for="password">Hasło</label>
		<input type="password" name="password" id="password">

		<button type="submit">Kontynuuj</button>
	</form>
@endsection
