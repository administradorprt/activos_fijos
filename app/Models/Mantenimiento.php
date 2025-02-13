<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mantenimiento extends Model
{
    use SoftDeletes;

    public function archivos():HasMany
    {
        return $this->hasMany(MantenimientoArchivo::class);
    }
    //obtener archivos pdf
    public function pdfs():HasMany
    {
        return $this->hasMany(MantenimientoArchivo::class)->where('extension', 'pdf');
    }
    //obtener imagenes según su mime type
    public function imagenes():HasMany
    {
        return $this->hasMany(MantenimientoArchivo::class)->whereIn('type', [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 
            'image/svg+xml', 'image/avif', 'image/heic', 'image/heif'
        ]);
    }
}
