<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';
    protected $primaryKey = 'id_servicio';
    
    protected $fillable = [
        'nombre_servicio',
        'descripcion',
        'requiere_orden_medica',
        'activo',
        'id_modulo',
        'id_area'
        // Eliminé 'duracion_estimada'
    ];
    
    public $timestamps = false;
}