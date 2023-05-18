@extends('welcome')

@section('content')
    <h2>Posts</h2>
    <form action="posts" method="GET">
        @csrf
        <input type="hidden" name="view" value="ver" id="">
        <button>Crear</button>
    </form>
@if ($view == true)
    <form action="create" method="POST">
        @csrf
        <input type="text" name="titulo" placeholder="titulo">
        <input type="text" name="body" placeholder="texto">
        <button>Publicar</button>
    </form>
@endif
    <hr>
    @foreach ($posts as $post)
        <div>
            <span>{{ $post->id}}</span>
            <h2>{{ $post->title }}</h2>
            <p>{{ $post->body }}</p>
        </div>
    @endforeach
@endsection
