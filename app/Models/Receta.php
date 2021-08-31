<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;

    // Campos que se agregarán
    protected $fillable = [
        'titulo',
        'preparacion',
        'ingredientes',
        'imagen',
        'categoria_id',
    ];

    // Obtiene la categoria de la receta via FK 
    // belongsTo perquè una receta pertany a una categoria
    public function categoria() {
        return $this->belongsTo(CategoriaReceta::class); // CategoriaReceta, nom del model
    }

    // Obtiene la información del usuario via FK
    public function autor() {
        return $this->belongsTo(User::class, 'user_id'); // Indiquem el camp amb el que relacionem
    }

    // Likes que ha recibido una receta
    public function likes() {
        return $this->belongsToMany(User::class, 'likes_receta');
    }

}
