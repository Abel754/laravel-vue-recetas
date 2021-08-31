<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;

class LikesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {
        // Almacena los likes de un usuario a una receta
        return auth()->user()->meGusta()->toggle($receta); // Cridem al mètode meGusta del Model User i afegim el toggle perquè puguem activar o desactivar el like
    }

}
