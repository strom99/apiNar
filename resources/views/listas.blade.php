@extends('welcome')

@section('content')
    <h2>Listas</h2>


    @foreach ($listas->results as $lista)
        <div>
            <h4>{{ $lista->name}}</h4>
            <p>{{ $lista->description }}</p>
            <h6>Películas: {{ $lista->item_count }}</h6>
        </div>
    @endforeach

    <h2>Crear nueva lista</h2>
    <form action="/create-lista" method="POST">
        @csrf
        Nombre
        <input type="text" name="name">
        Descripción
        <input type="text" name="description">
        <button type="submit">Crear</button>
    </form>
@endsection
