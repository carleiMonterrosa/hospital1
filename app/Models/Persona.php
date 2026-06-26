<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'personas';
    
    // Definir la llave primaria (porque no usas ID autoincremental)
    protected $primaryKey = 'identificacion';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Deshabilitar timestamps (created_at y updated_at)
    public $timestamps = false;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'identificacion',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'zona',
        'fecha_nacimiento'  // <--- AGREGADO
    ];

    // Accesor para obtener el nombre completo
    public function getNombreCompletoAttribute()
    {
        $nombres = trim($this->primer_nombre . ' ' . $this->segundo_nombre);
        $apellidos = trim($this->primer_apellido . ' ' . $this->segundo_apellido);
        return trim($nombres . ' ' . $apellidos);
    }

    // Accesor para obtener solo nombres
    public function getNombresAttribute()
    {
        return trim($this->primer_nombre . ' ' . $this->segundo_nombre);
    }

    // Accesor para obtener solo apellidos
    public function getApellidosAttribute()
    {
        return trim($this->primer_apellido . ' ' . $this->segundo_apellido);
    }

    // Relación con turnos
    public function turnos()
    {
        return $this->hasMany(Turno::class, 'persona_id');
    }

    // Método para buscar por identificación
    public function scopePorIdentificacion($query, $identificacion)
    {
        return $query->where('identificacion', $identificacion);
    }
}