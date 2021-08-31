@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" integrity="sha512-5m1IeUDKtuFGvfgz32VVD0Jd/ySGX7xdLxhqemTmThxHdgqlgPdupWoSN8ThtUSLpAGBvA8DY2oO7jJCrGdxoA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endsection

@section('botones')
<a href="{{route('recetas.index')}}" class="btn btn-outline-primary mr-2 text-uppercase font-weight-bold">
    <svg class="icono w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
    Volver
</a>
@endsection

@section('content')

    <h1 class="text-center">Editar Mi Perfil</h1>

    <div class="row justify-content-center mt-5">
        <div class="col-md-10 bg-white p-3">
            <form action="{{route('perfiles.update', ['perfil' => $perfil->id])}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
            
                <div class="form-group">
                    <label for="nombre">Nombre</label>

                    <!--Si hi ha error a l'input, afegirà la classe is-invalid que posa el borde de color vermell. Value=old serveix per si no s'envia, no es borrin els camps -->
                    <input type="text" 
                        name="nombre" 
                        class="form-control @error('nombre') is-invalid @enderror " 
                        id="nombre" 
                        placeholder="Tu Nombre"
                        value="{{$perfil->usuario->name}}"
                    />

                    @error('nombre')
                        <span class="invalud-feedback d-block text-danger" role="alert">
                            <strong>{{$message}}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nombre">Sitio Web</label>

                    <!--Si hi ha error a l'input, afegirà la classe is-invalid que posa el borde de color vermell. Value=old serveix per si no s'envia, no es borrin els camps -->
                    <input type="text" 
                        name="url" 
                        class="form-control @error('url') is-invalid @enderror " 
                        id="url" 
                        placeholder="Tu Sitio Web"
                        value="{{$perfil->usuario->url}}"
                    />

                    @error('url')
                        <span class="invalud-feedback d-block text-danger" role="alert">
                            <strong>{{$message}}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="biografia">Biografia</label>

                    <input id="biografia" type="hidden" name="biografia" value="{{$perfil->biografia}}">

                    <!-- Trix-edit és el CDN que he importat. Serveix per escriure textos llargs -->
                    <trix-editor 
                        class="form-control @error('biografia') is-invalid @enderror"
                        input="biografia">
                    </trix-editor>

                    @error('biografia')
                        <span class="invalud-feedback d-block text-danger" role="alert">
                            <strong>{{$message}}</strong>
                        </span>
                    @enderror
                    <br>
                </div>

                <div class="form-group mt-3">
                    <label for="imagen">Tu Imagen</label>

                    <input 
                        id="imagen" 
                        type="file" 
                        class="form-control @error('categoria') is-invalid @enderror "
                        name="imagen"
                    >

                    @if($perfil->imagen)
                        <div class="mt-4">
                            <p>Imagen Actual:</p>
                            <img src="/storage/{{$perfil->imagen}}" style="width: 300px" alt="">
                            
                        </div>

                        @error('imagen')
                            <span class="invalud-feedback d-block text-danger" role="alert">
                                <strong>{{$message}}</strong>
                            </span>
                        @enderror
                    @endif
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Actualizar Perfil">
                </div>
            </form>
        </div>
    </div>

@endsection



@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js" integrity="sha512-/1nVu72YEESEbcmhE/EvjH/RxTg62EKvYWLG3NdeZibTCuEtW5M4z3aypcvsoZw03FAopi94y04GhuqRU9p+CQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>

@endsection