@extends("layouts.app")

@section("title", "Samorząd II LO")

@section("content")
    <a href="/skladki" class="card-container">
        <div class="card">
            <img src="/img/money.webp" alt="Składki">
            <p class="card_content">Składki</p>
        </div>
    </a>
	<a href="/contests" class="card-container">
		<div class="card">
			<img src="/img/contests.webp" alt="Składki">
			<p class="card_content">Konkursy</p>
		</div>
	</a>
	<a href="/contests" class="card-container">
		<div class="card">
			<img src="/img/ogloszenia.webp" alt="Ogłoszenia">
			<p class="card_content">Ogłoszenia</p>
		</div>
	</a>
	<a href="/messages" class="card-container">
		<div class="card">
			<img src="/img/contact.webp" alt="Składki">
			<p class="card_content">Kontakt</p>
		</div>
	</a>
@endsection
