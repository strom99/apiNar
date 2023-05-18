@extends('welcome')

@section('content')
<!-- PARA ACCEDER A AMBAS APIS -->
    <h1>Spotify</h1>
    <form action="login" method="post">
        @csrf
        <button href="login" >Login</button>
    </form>
    <hr>
        <h1>Placeholder</h1>
        <a href="/login-moviedb">Login MovieDB</a>
@endsection
