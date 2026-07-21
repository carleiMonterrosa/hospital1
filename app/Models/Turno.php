<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turnos';

    /**
     * 🔥 CORREGIDO: La clave primaria es 'id_turno', no 'id'
     */
    protected $primaryKey = 'id_turno';

    /**
     * 🔥 CORREGIDO: El campo que se incrementa automáticamente
     */
    public $incrementing = true;

    /**
     * 🔥 CORREGIDO: El tipo de la clave primaria es integer
     */
    protected $keyType = 'int';

    /**
     * 🔥 CORREGIDO: Los campos que se pueden asignar masivamente
     * Ahora coinciden con los nombres reales de la base de datos
     */
    protected $fillable = [
        'identificacion_paciente',  // 🔥 CORREGIDO: antes era 'identificacion'
        'id_modulo',                // 🔥 CORREGIDO: antes era 'ventanilla'
        'fecha_turno',              // 🔥 CORREGIDO: antes era 'fecha'
        'estado',
        'especialidad',
        'nombre_persona'
    ];

    /**
     * 🔥 NUEVO: Campos que NO deben ser mostrados en arrays/JSON
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * 🔥 NUEVO: Campos que deben ser tratados como fechas
     */
    protected $dates = [
        'fecha_turno',
        'created_at',
        'updated_at'
    ];

    /**
     * 🔥 NUEVO: Accesor para obtener el número de turno con prefijo
     */
    public function getNumeroTurnoAttribute()
    {
        $prefijos = ['CON', 'ODO', 'LAB', 'RAY'];
        $indice = ($this->id_modulo - 1) % 4;
        $prefijo = $prefijos[$indice] ?? 'CON';
        return $prefijo . '-' . str_pad($this->id_turno, 2, '0', STR_PAD_LEFT);
    }

    /**
     * 🔥 NUEVO: Accesor para obtener el número simple (sin prefijo)
     */
    public function getNumeroSimpleAttribute()
    {
        return $this->id_turno;
    }

    /**
     * 🔥 NUEVO: Accesor para obtener el nombre de la especialidad según el módulo
     */
    public function getNombreEspecialidadAttribute()
    {
        $nombres = [
            1 => 'Consulta Externa',
            2 => 'Odontología',
            3 => 'Laboratorio Clínico',
            4 => 'Rayos X',
            5 => 'Consulta Externa',
            6 => 'Odontología',
            7 => 'Laboratorio Clínico',
            8 => 'Rayos X'
        ];
        return $nombres[$this->id_modulo] ?? 'Consulta Externa';
    }

    /**
     * 🔥 NUEVO: Relación con la persona (paciente)
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'identificacion_paciente', 'identificacion');
    }

    /**
     * 🔥 CORREGIDO: Scope para turnos en espera
     */
    public function scopeEnEspera($query)
    {
        return $query->where('estado', 'espera');
    }

    /**
     * 🔥 CORREGIDO: Scope para turnos pendientes (espera o llamado)
     */
    public function scopePendientes($query)
    {
        return $query->whereIn('estado', ['espera', 'llamado']);
    }

    /**
     * 🔥 CORREGIDO: Scope por especialidad (usando el campo de la BD)
     */
    public function scopePorEspecialidad($query, $especialidad)
    {
        return $query->where('especialidad', $especialidad);
    }

    /**
     * 🔥 NUEVO: Scope por módulo
     */
    public function scopePorModulo($query, $modulo)
    {
        return $query->where('id_modulo', $modulo);
    }

    /**
     * 🔥 NUEVO: Scope por fecha (solo turnos de hoy)
     */
    public function scopeDeHoy($query)
    {
        return $query->whereDate('fecha_turno', now()->toDateString());
    }

    /**
     * 🔥 NUEVO: Scope para turnos activos (no atendidos ni eliminados)
     */
    public function scopeActivos($query)
    {
        return $query->whereNotIn('estado', ['atendido', 'eliminado']);
    }

    /**
     * 🔥 NUEVO: Verificar si el turno está en espera
     */
    public function isEnEspera()
    {
        return $this->estado === 'espera';
    }

    /**
     * 🔥 NUEVO: Verificar si el turno está llamado
     */
    public function isLlamado()
    {
        return $this->estado === 'llamado';
    }

    /**
     * 🔥 NUEVO: Verificar si el turno está atendido
     */
    public function isAtendido()
    {
        return $this->estado === 'atendido';
    }

    /**
     * 🔥 NUEVO: Cambiar estado del turno
     */
    public function cambiarEstado($nuevoEstado)
    {
        $this->estado = $nuevoEstado;
        return $this->save();
    }

    /**
     * 🔥 NUEVO: Llamar al turno (cambiar a 'llamado')
     */
    public function llamar()
    {
        return $this->cambiarEstado('llamado');
    }

    /**
     * 🔥 NUEVO: Atender al turno (cambiar a 'atendido')
     */
    public function atender()
    {
        return $this->cambiarEstado('atendido');
    }

    /**
     * 🔥 NUEVO: Poner en espera
     */
    public function ponerEnEspera()
    {
        return $this->cambiarEstado('espera');
    }

    /**
     * 🔥 NUEVO: Obtener estadísticas de turnos por módulo
     */
    public static function estadisticasPorModulo($modulo)
    {
        return [
            'activos' => self::porModulo($modulo)->pendientes()->count(),
            'atendidos' => self::porModulo($modulo)->where('estado', 'atendido')->count(),
            'totales' => self::porModulo($modulo)->count(),
            'en_espera' => self::porModulo($modulo)->enEspera()->count()
        ];
    }

    /**
     * 🔥 NUEVO: Obtener el siguiente turno en espera para un módulo
     */
    public static function siguienteEnEspera($modulo)
    {
        return self::porModulo($modulo)
            ->enEspera()
            ->orderBy('id_turno', 'asc')
            ->first();
    }

    /**
     * 🔥 NUEVO: Mutador para asegurar que el estado siempre sea válido
     */
    public function setEstadoAttribute($value)
    {
        $estadosValidos = ['espera', 'llamado', 'atendido', 'eliminado'];
        $this->attributes['estado'] = in_array($value, $estadosValidos) ? $value : 'espera';
    }

    /**
     * 🔥 NUEVO: Boot del modelo para establecer valores por defecto
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($turno) {
            if (empty($turno->estado)) {
                $turno->estado = 'espera';
            }
            if (empty($turno->fecha_turno)) {
                $turno->fecha_turno = now();
            }
        });
    }
}