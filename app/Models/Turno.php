<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turnos';

    protected $fillable = [
        'numero',
        'numero_simple',
        'persona_id',
        'identificacion',
        'nombre_persona',
        'especialidad',
        'nombre_especialidad',
        'ventanilla',
        'estado',
        'fecha',
        'hora'
    ];

    // Relación con persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    // Scope para turnos pendientes
    public function scopePendientes($query)
    {
        return $query->whereIn('estado', ['pendiente', 'llamado']);
    }

    // Scope por especialidad
    public function scopePorEspecialidad($query, $especialidad)
    {
        return $query->where('especialidad', $especialidad);
    }
}