@extends("layouts.app")

@section("title", "Samorząd II LO")

@section("content")
    <a href="/skladki" class="card-container">
        <div class="card">
            <img src="/img/taxes.png" alt="Składki">
            <p class="card_content">Składki</p>
        </div>
    </a>
    <div class="card">
        <img src="/img/taxes.png" alt="Składki">
        <p class="card_content">Radiowęzeł</p>
    </div>
    <div class="card">
        <img src="/img/taxes.png" alt="Składki">
        <p class="card_content">Konkursy</p>
    </div>
	<a href="/messages" class="card-container">
		<div class="card">
			<img src="/img/taxes.png" alt="Składki">
			<p class="card_content">Kontakt</p>
		</div>
	</a>
@endsection
