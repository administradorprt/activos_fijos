<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $connection = 'concentradora';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    //-----scopes-----

    public function scopeBuscar(Builder $query,String $txt):void
    {
        $query->where('n_empleado','LIKE',"%$txt%")
        ->orWhere('email','LIKE',"%$txt%")
        ->orWhereHas('empleado',function(Builder $empleado)use($txt){
            $empleado->where('nombres','LIKE',"%$txt%");
        });
    }
    public function scopeActivos(Builder $query):void
    {
        $query->where('status',1);
    }

    //----------------
    public function empleado():BelongsTo
    {
        return $this->belongsTo(Empleado::class,'n_empleado','n_empleado','id_empleado');
    }
}
