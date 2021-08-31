@extends('layouts.app')



@section('content')

    <article class="contenido-receta bg-white p-5 shadow"> <!-- Entrada de blog és article -->
        <h1 class="text-center mb-4">{{$receta->titulo}}</h1>

        <div class="imagen-receta">
            <img src="/storage/{{$receta->imagen}}" class="w-100">
        </div>

        <div class="receta-meta mt-2">
            <p>
                <span class="font-weight-bold text-primary">Escrito en:</span>
                <a class="text-dark" href="{{route('categorias.show', ['categoriaReceta' => $receta->categoria->id])}}">
                    {{$receta->categoria->nombre}}
                </a>  
            </p>

            <p>
                <span class="font-weight-bold text-primary">Autor:</span>
                <a class="text-dark" href="{{route('perfiles.show', ['perfil' => $receta->autor->id])}}">
                    {{$receta->autor->name}}
                </a>  
            </p>

            <p>
                <span class="font-weight-bold text-primary">Fecha:</span>
                @php
                    $fecha = $receta->created_at
                @endphp
                <fecha-receta fecha="{{$fecha}}"></fecha-receta>
            </p>

            <div class="ingredientes">
                <h2 class="my-3 text-primary">Ingredientes</h2>

                {!! $receta->ingredientes !!} <!-- Mostra el contingut sense posar <div></div> -->
            </div>

            <div class="preparacion">
                <h2 class="my-3 text-primary">Preparación</h2>

                {!! $receta->preparacion !!} <!-- Mostra el contingut sense posar <div></div> -->
            </div>
           
            <div class="justify-content-center row text-center">
                <!-- Li passem la id de la recepta amb un props que agafem a LikeButton.vue -->
                <!-- I també li passem el $like que rebem del compact del controlador -->
                <like-button
                    receta-id="{{$receta->id}}"
                    like="{{$like}}"
                    likes="{{$likes}}"
                ></like-button>
            </div>

        </div>
    </article>
@endsection
