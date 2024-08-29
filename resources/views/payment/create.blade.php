@extends("layouts.app")

@section("title", "Utwórz składkę - mSamorząd")

@section("content")
	<h1>Utwórz składkę</h1>
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<form action="/skladki/new" method="post">
		@csrf
		<label for="title">Nazwa składki</label>
		<input type="text" name="title" id="title" maxlength="80" minlength="3" required>
		<label for="money">Wysokość (na ucznia)</label>
		<input type="number" name="money" id="money" oninput="calculateTotalAmount()" min="1" max="999" required>
		<span id="totalAmount" data-total-students="{{ $classUnitSize }}"></span><br>
		<label for="deadline">Data końcowa</label>
		<input type="date" name="deadline" id="deadline" min="{{ date("Y-m-d") }}" required>
		<input type="checkbox" name="excludeStudents" id="excludeStudents">
		<label for="excludeStudents"><span class="icon-text">Składka nie dotyczy całej klasy</span>
			<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor"
				 class="icon-svg" id="feature-guide-exclude-students-invoker">
				<path
					d="M478-240q21 0 35.5-14.5T528-290q0-21-14.5-35.5T478-340q-21 0-35.5 14.5T428-290q0 21 14.5 35.5T478-240Zm-36-154h74q0-33 7.5-52t42.5-52q26-26 41-49.5t15-56.5q0-56-41-86t-97-30q-57 0-92.5 30T342-618l66 26q5-18 22.5-39t53.5-21q32 0 48 17.5t16 38.5q0 20-12 37.5T506-526q-44 39-54 59t-10 73Zm38 314q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/>
			</svg>
		</label>

		<div id="excludedStudentsForm" hidden>
			<p>Wybierz uczniów, których składka nie dotyczy.</p>

			<ul class="payment-students">
				@foreach($classUnitStudents as $student)
					<li>
						<input type="checkbox" name="student[{{ $student->id }}]" id="student[{{ $student->id }}]">
						<label for="student[{{ $student->id }}]">
							{{ $student->name }}
						</label>
					</li>
				@endforeach
			</ul>
		</div>

		<button class="block" type="submit">Utwórz</button>
	</form>

	<dialog id="feature-guide-exclude-students">
		<header>Składka nie dotyczy całej klasy</header>
		<p>Ta funkcja pozwala ci utworzyć składkę, która będzie dotyczyć tylko niektórych uczniów. Jednym z zastosowań
			tej funkcji może być utworzenie składki na wycieczkę, w której bierze udział tylko część klasy.</p>
		<button>Zrozumiano</button>
	</dialog>
@endsection
