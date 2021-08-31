<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\Receta;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'show']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function show(Perfil $perfil)
    {

        $recetas = Receta::where('user_id', $perfil->user_id)->paginate(10);
        //
        return view('perfiles.show', compact('perfil', 'recetas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function edit(Perfil $perfil)
    {
        // Ejecutar el Policy view
        $this->authorize('view', $perfil);

        return view('perfiles.edit', compact('perfil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perfil $perfil)
    {

        // Ejecutar el Policy
        $this->authorize('update', $perfil);

        // Validar
        $data = request()->validate([
            'nombre' => 'required',
            'url' => 'required',
            'biografia' => 'required',
        ]);


        // Si el usuario sube una imagen
           
            if($request['imagen']) {
                 // Creo variable guardant la nova imatge
                $ruta_imagen = $request['imagen']->store('upload-perfiles', 'public');

                // Insereixo la nova imatge en el directori
                $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(600,600);
                $img->save(); // Es guarda amb el save

                $array_imagen = ['imagen' => $ruta_imagen];
            }


        // Asignar nombre y URL
        auth()->user()->name = $data['nombre'];
        auth()->user()->url = $data['url'];      
        auth()->user()->save();
            
        // Si no fem un unset als camps després de guardar-los a la BD, persisteixen i al fer la línea
        // de baix, dóna error perquè li estàs passant camps que no estan dins de la taula perfiles.
        unset($data['url']);
        unset($data['nombre']);

        // Asignar Bio e imagen
        // Guardar info
        auth()->user()->perfil()->update(array_merge(
            $data,
            $array_imagen ?? [] // Si existeix array_imagen, passa-li. Si no, passa buiit
        ));

        // També es podria fer sense fer els unset de la següent manera:
            //$perfil->biografia = $data['biografia'];
            //$perfil->save();

        // Redireccionar
        return redirect()->action('RecetaController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perfil $perfil)
    {
        //
    }
}
