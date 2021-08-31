<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;
use App\Models\CategoriaReceta;

class CategoriasController extends Controller
{
    public function show(CategoriaReceta $categoriaReceta) {
        // Treure les receptes que en el camp categoria_id sigui el mateix que la ID de la recepta que se li passa per paràmetre
        $recetas = Receta::where('categoria_id', $categoriaReceta->id)->paginate(2);

        return view('categorias.show', compact('recetas', 'categoriaReceta'));
    }
}
