<?php

namespace App\Http\Controllers;

use App\Models\CategoriaReceta;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class RecetaController extends Controller
{

    public function __construct() // Farà que només accedeixis als mètodes si estàs autenticat
    {
        $this->middleware('auth', ['except' => ['show', 'search']]); // Aplicarà el auth a totes les rutes menys show
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Altra forma de fer:
            //auth()->user()->recetas->dd();

        // Mostra les receptes que ha publicat l'usuari.
        // Es posa 'recetas' perquè al Model User.php el mètode que relacione les dues taules es diu així
            //$recetas = Auth::user()->recetas;

        $usuario = auth()->user();

        $recetas = Receta::where('user_id', $usuario->id)->paginate(2);

        return view('recetas.index')->with('recetas',$recetas)->with('usuario', $usuario);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Seria com fer un select nombre, id from categoria_receta
            // DB::table('categoria_receta')->get()->pluck('nombre','id')->dd(); //Treu tots els valors que té aquella taula, com un var_dump

        // Obtener categorias (sin modelo)
            //$categorias = DB::table('categoria_recetas')->get()->pluck('nombre','id');

        // Con modelo
        $categorias = CategoriaReceta::all(['id','nombre']);

        return view('recetas.create')->with('categorias',$categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request['imagen']->store('upload-recetas', 'public'));

        $data = request()->validate([ // Validem com sempre els camps que rebem
            'titulo' => 'required|min:6',
            'preparacion' => 'required',
            'ingredientes' => 'required',
            'imagen' => 'required|image', // Tipus img
            'categoria' => 'required',
        ]);

        // Variable que guarda la imatge rebuda dins d'storage upload-recetas
        $ruta_imagen = $request['imagen']->store('upload-recetas', 'public');

        $img = Image::make($request->file('imagen')->getRealPath()); // Utilitzo el Intervention per guardar l'imatge

        //$img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000,550);   // NO FUNCIONA!

        $img->save('my-image.jpg'); // Poso aquest 'my-image.jpg perquè si no, dóna error de format tmp

        // Almacenar a base de datos (sin modelo)
        /*DB::table('recetas')->insert([ // Utilitzem instrucció de Laravel DB::table i la taula fem un insert del valor titulo que reb
            'titulo' => $data['titulo'],
            'preparacion' => $data['preparacion'],
            'ingredientes' => $data['ingredientes'],
            'imagen' => $ruta_imagen,
            'user_id' => Auth::user()->id,
            'categoria_id' => $data['categoria'],
        ]);*/

        // Almanerar en la BD (con moldelo) 
        auth()->user()->recetas()->create([ // Agafa mètode recetas del model User
            'titulo' => $data['titulo'],
            'preparacion' => $data['preparacion'],
            'ingredientes' => $data['ingredientes'],
            'imagen' => $ruta_imagen,
            'categoria_id' => $data['categoria'],
        ]); 

        return redirect()->action('RecetaController@index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta) // Passem nom del Model
    {
        // Obtener si al usuario le gusta la receta y está autenticado
        $like = (auth()->user()) ? auth()->user()->meGusta->Contains($receta->id) : false;

        // Pasa la cantidad de likes a la vista. Likes és el mètode del Model Receta que uneix amb User
        $likes = $receta->likes->count(); 

        return view('recetas.show',compact('receta', 'like', 'likes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {

        // Revisar el policy view
        $this->authorize('view', $receta); // Revisa una autorització, agafa el mètode del policy i li passem la recepta

        $categorias = CategoriaReceta::all(['id','nombre']); // Perquè es mostri el select de la vista

        return view('recetas.edit', compact('categorias', 'receta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {

        // Revisar el policy
        $this->authorize('update', $receta); // Revisa una autorització, agafa el mètode del policy i li passem la recepta
         
        $data = request()->validate([ // Validem com sempre els camps que rebem
            'titulo' => 'required|min:6',
            'preparacion' => 'required',
            'ingredientes' => 'required',
            'categoria' => 'required',
        ]);

        // Asignar los valores
        $receta->titulo = $data['titulo'];
        $receta->ingredientes = $data['ingredientes'];
        $receta->preparacion = $data['preparacion'];
        $receta->categoria_id = $data['categoria'];

        // Creo variable guardant la nova imatge
        $ruta_imagen = $request['imagen']->store('upload-recetas', 'public');

        // Insereixo la nova imatge en el directori
        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000,550);

        $img->save(); // Es guarda amb el save
        $receta->imagen = $ruta_imagen; // Assigno la ruta al camp de la BD

        $receta->save(); // Guardo tots els camps

        return redirect()->action('RecetaController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        // Ejecutar el Policy
        $this->authorize('delete', $receta);

        // Eliminar la receta
        $receta->delete();

        return redirect()->action('RecetaController@index');
    }

    public function search(Request $request) {
        //$busqueda = $request['buscar'];
        $busqueda = $request->get('buscar');

        $recetas = Receta::where('titulo', 'like', '%' . $busqueda . '%')->paginate(1);
        // Executem el següent codi perquè no es turbi el Paginate:
        $recetas->appends(['buscar' => $busqueda]);

        return view('busquedas.show', compact('recetas', 'busqueda'));
    }
}
