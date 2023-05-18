@extends('welcome')

@section('content')
    <h1>Perfil {{ $perfil->display_name }}</h1>
    <img src="{{ $perfil->images[0]->url }}" alt="">

    <span>Datos</span>
    <ul>
        <li>
            <span>Email</span>
            <p>{{ $perfil->email }}</p>
        </li>
        <li>
            <span>Country</span>
            <p>{{ $perfil->country }}</p>
        </li>
                <li>
            <span>Type</span>
            <p>{{ $perfil->type }}</p>
        </li>
    </ul>


<form action="pista" method="GET">
    @csrf
    <input type="text" name="track" placeholder="Nombre artista">
    <button type="submit">Buscar</button>
    @isset($error)
        {{ $error }}
    @endisset
</form>

<hr>
@isset($message)
        <span>Resultado busqueda:</span>
        <ul>
            @foreach ($message->items as $item)
                <li>
                    <span>Artista.-{{ $item->artists[0]->name}}</span>
                    <span>Cancion.-{{ $item->name}}</span>
                    <audio controls>
                    <source src="{{ $item->preview_url}}" type="audio/mpeg">
                    </audio>
                </li>
            @endforeach
        </ul>
@endisset




@endsection
