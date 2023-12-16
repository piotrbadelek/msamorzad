@extends("layouts.app")

@section("title", "Utwórz składkę - samorząd II LO")

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
        <button type="submit">Utwórz</button>
    </form>
@endsection
