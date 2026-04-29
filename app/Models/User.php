<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'permisos',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'permisos' => 'array',
    ];

    /**
     * Obtener el nombre completo del usuario
     */
    public function getFullNameAttribute()
    {
        return $this->name ?? $this->username ?? 'Sin nombre';
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function tienePermiso($permiso)
    {
        $permisos = $this->permisos ?? [];
        return isset($permisos[$permiso]) && $permisos[$permiso] == 1;
    }

    /**
     * Obtener todos los permisos del usuario
     */
    public function getPermisosArray()
    {
        return $this->permisos ?? [
            'login' => 0,
            'agregar_paciente' => 0,
            'usuarios' => 0,
            'servicios' => 0,
            'reportes' => 0,
            'atender_turnos' => 0,
        ];
    }

    /**
     * Buscar usuarios por username (coincidencia parcial)
     */
    public static function buscarPorUsername($busqueda)
    {
        return self::where('username', 'LIKE', '%' . $busqueda . '%')
            ->orWhere('name', 'LIKE', '%' . $busqueda . '%')
            ->get();
    }

    /**
     * Buscar un usuario específico por username exacto
     */
    public static function buscarExacto($username)
    {
        return self::where('username', $username)->first();
    }
}