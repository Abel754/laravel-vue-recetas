@extends('layouts.app')

@section('botones')

    @include('ui.navegacion')

@endsection

@section('content')

    <h2 class="text-center mb-5">Administra tus recetas</h2>
    
    <div class="col-ld-10 mx-auto bg-white p-3">
        <table class="table">
            <thead class="bg-primary text-light">
                <tr>
                    <th scope="col">Título</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @foreach($recetas as $receta) <!-- $recetas és la variable que ens passem al mètode index del RecetaController amb la info de receptes -->
                    <tr>
                        <td>{{$receta->titulo}}</td>
                        <td>{{$receta->categoria->nombre}}</td> <!-- $receta->categoria perquè agafa el mètode del model Receta.php -->
                        <td>

                            <eliminar-receta receta-id={{$receta->id}}></eliminar-receta>
                                
                        <a href="{{route('recetas.edit', ['receta' => $receta->id])}}" class="btn btn-dark mr-1 mb-2 d-block">Editar</a>
                        <!-- Com crear enllaç mitjançant action. Action agafarà en el web.php el controlador i mètode. Després 
                        li passem un segon paràmetre que serà el valor get, en aquest cas receta que equival a la $receta->id -->
                            <a href="{{action('RecetaController@show', ['receta' => $receta->id])}}" class="btn btn-success mr-1 mb-2 d-block">Ver</a>
                        <!-- Altra manera de fer: amb el route agafarà el name del web.php -->
                        <a href="{{route('recetas.show', ['receta' => $receta->id])}}" class="btn btn-success mr-1 d-block">Ver 2a manera</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="col-12 mt-4 justify-content-center d-flex">
            {{$recetas->links('pagination::bootstrap-4')}}
        </div>

        @if(count($usuario->meGusta) > 0)    
            <h2 class="text-center my-5">Recetas que te gustan</h2>
            <div class="col-md-10 mx-auto bg-white p-3">
                <ul class="list-group">
                    @foreach($usuario->meGusta as $receta)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{$receta->titulo}}

                            <a class="btn btn-outline-success text-uppercase font-weight-bold" href="{{route('recetas.show', ['receta' => $receta->id])}}">Ver</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            @else 
            <p class="text-center">Aún no tienes recetas guardadas <small>Dale me gusta a las recetas</small></p>
        @endif

    </div>


@endsection
