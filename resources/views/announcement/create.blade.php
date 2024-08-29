@extends("layouts.app")

@section("title", "Utwórz ogłoszenie - mSamorząd")

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
	<form action="/announcements/new" method="post" enctype="multipart/form-data">
		@csrf
		<label for="title">Tytuł ogłoszenia</label>
		<input type="text" name="title" id="title" maxlength="128" minlength="3" required>
		<label for="description">Ogłoszenie</label><br>
		<textarea name="description" id="description" cols="30" rows="10" required maxlength="2048"></textarea>
		<label for="image">Zdjęcie (opcjonalne)</label>
		<input type="file" name="image" id="image" accept="image/*">

		@if ($canPostToClass && $canPostGlobally)
			<label for="postArea">Postujesz na forum:</label>
			<select name="postArea" id="postArea">
				<option value="school">szkolnym</option>
				<option value="class">klasowym ({{ $class }})</option>
			</select>
		@elseif ($canPostGlobally)
			<span
				title="Nie masz uprawnień do postowania na forum klasowym gdyż nie jesteś członkiem żadnego samorządu klasowego.">Postujesz na forum szkolnym.</span>
		@else
			<span
				title="Nie masz uprawnień do postowania na forum szkolnym gdyż nie jesteś członkiem samorządu szkolnego.">Postujesz na forum klasowym.</span>
		@endif

		<button type="submit">Utwórz</button>
	</form>
@endsection
