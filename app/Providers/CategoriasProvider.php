<?php

namespace App\Providers;

use View;
use App\Models\CategoriaReceta;
use Illuminate\Support\ServiceProvider;

class CategoriasProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() // Quan no utilitzaràs res del que Laravel t'ofereix
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() // Quan sí que utilitzaràs eines que t'ofereix Laravel
    {
        View::composer('*', function($view){
            $categorias = CategoriaReceta::all();
            $view->with('categorias', $categorias);
        });
    }
}
