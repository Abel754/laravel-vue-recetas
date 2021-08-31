<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CategoriaReceta;

class InicioController extends Controller
{
    public function index()  {

        // Mostrar las recetas por cantidad de votos
        // Model Receta, relació likes, receptes amb més de 0 vots
            //$votadas = Receta::has('likes', '>' , 0)->get();
        // És millor fer amb el següent. Compta del mètode likes del Model Receta.
        // La instrucció withCount genera una columna temporal, aquesta es diu 'likes_count', així que ordenem la columna i treiem els 3 resultats
        $votadas = Receta::withCount('likes')->orderBy('likes_count', 'desc')->take(3)->get();

        // Obtener las recetas nuevas
        
        // $nuevas = Receta::latest()->get(); // També tenim aquesta instrucció
        $nuevas = Receta::oldest()->limit(5)->get(); // Fa el mateix que l'ordre de baix
        // $nuevas = Receta::orderBy('created_at', 'ASC')->get();

        // Obtener todas las categorías
        $categorias = CategoriaReceta::all();

        // Agrupar las recetas por categoría
        $recetas = [];

        foreach($categorias as $categoria) {
            // Utilitzem el helper slug perquè imprimeixi els títols sense espais ja que pot causar problemes
            $recetas[Str::slug($categoria->nombre)][] = Receta::where('categoria_id', $categoria->id)->get();
        }
        
        return view('inicio.index', compact('nuevas', 'recetas', 'votadas'));
    }
}
