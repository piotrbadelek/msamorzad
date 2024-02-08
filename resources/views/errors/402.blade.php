@extends('errors.layout')

@section("error", "402 Payment Required")
@section("explanation", "Aby skorzystać z podanej funkcji wymagana jest dodatkowa płatność. Jeżeli uważasz iż jest to pomyłka, skontaktuj się z poomcą techniczną.")
@section("svgContent")
	<path
		d="M120-160q-33 0-56.5-23.5T40-240v-440h80v440h680v80H120Zm160-160q-33 0-56.5-23.5T200-400v-320q0-33 23.5-56.5T280-800h560q33 0 56.5 23.5T920-720v320q0 33-23.5 56.5T840-320H280Zm80-80q0-33-23.5-56.5T280-480v80h80Zm400 0h80v-80q-33 0-56.5 23.5T760-400Zm-200-40q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35ZM280-640q33 0 56.5-23.5T360-720h-80v80Zm560 0v-80h-80q0 33 23.5 56.5T840-640Z"/>
@endsection
