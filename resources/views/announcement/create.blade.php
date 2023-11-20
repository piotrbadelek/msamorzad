@extends("layouts.app")

@section("title", "Utwórz ogłoszenie - samorząd II LO")

@section("content")
	<h1>Utwórz ogłoszenie</h1>
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<form action="/announcements/new" method="post">
		@csrf
		<label for="title">Tytuł ogłoszenia</label>
		<input type="text" name="title" id="title" maxlength="128" minlength="3" required>
		<label for="description">Ogłoszenie</label><br>
		<textarea name="description" id="description" cols="30" rows="10" required maxlength="2048"></textarea>
		<button type="submit">Utwórz</button>
	</form>
@endsection
