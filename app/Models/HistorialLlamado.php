<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialLlamado extends Model
{
    use HasFactory;

    protected $table = 'historical_llamados';  // Nombre correcto de tu tabla
    protected $primaryKey = 'id_llamado';
    
    protected $fillable = [
        'id_turno',           // Agregar esta columna
        'fecha_llamado',
        'llamado_por',
        'observaciones'
    ];
    
    public $timestamps = false;
    
    protected $casts = [
        'fecha_llamado' => 'datetime',
        'id_turno' => 'string'
    ];
}