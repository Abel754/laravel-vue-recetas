<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'url',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Evento que se ejecuta cuando un usuario es creado
    protected static function boot() { // S'utilitza el mètode boot per assignar el perfil a l'usuari quan es creï
        parent::boot();

        // Asignar perfil una vez se haya creado un usuario nuevo
        static::created(function($user) {
            $user->perfil()->create();
        });
    }


    // Relación 1:N Usuario a Recetas

    public function recetas() {
        return $this->hasMany(Receta::class); // Se li indica el Model
    }

    //Relación 1:1 de usuario y perfil
    public function perfil() {
        return $this->hasOne(Perfil::class);
    }

    // Recetas que el usuario le ha dado me gusta
    public function meGusta() {
        return $this->belongsToMany(Receta::class, 'likes_receta');
    }

}
