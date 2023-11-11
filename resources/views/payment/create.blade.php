@extends("layouts.app")

@section("title", "Utwórz składkę - samorząd II LO")

@section("content")
    <h1>Utwórz składkę</h1>
    <form action="/skladki/new" method="post">
        @csrf
        <label for="title">Nazwa składki</label>
        <input type="text" name="title" id="title">
        <label for="money">Wysokość (na ucznia)</label>
        <input type="number" name="money" id="money" oninput="calculateTotalAmount()">
        <span id="totalAmount" data-total-students="{{ $classUnitSize }}"></span>
    </form>
@endsection
