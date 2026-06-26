<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionEmpresa extends Model
{
    use HasFactory;

    /**
     * DESACTIVAR TIMESTAMPS - Tu tabla no tiene created_at y updated_at
     */
    public $timestamps = false;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'configuracion_empresa';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_empresa',
        'direccion_empresa',
        'logo_empresa_url',
        'imagen_fondo_login', // NUEVO CAMPO PARA IMAGEN DE FONDO DEL LOGIN
    ];

    /**
     * Obtener la URL completa del logo
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo_empresa_url) {
            return asset($this->logo_empresa_url);
        }
        return null;
    }

    /**
     * Obtener la URL completa de la imagen de fondo del login
     */
    public function getFondoLoginUrlAttribute()
    {
        if ($this->imagen_fondo_login) {
            return asset($this->imagen_fondo_login);
        }
        return null;
    }

    /**
     * Verificar si la configuración existe
     */
    public static function existe()
    {
        return self::count() > 0;
    }

    /**
     * Obtener la primera configuración o crear una por defecto
     */
    public static function obtenerOInicializar()
    {
        $config = self::first();
        
        if (!$config) {
            $config = self::create([
                'nombre_empresa' => 'Mi Empresa',
                'direccion_empresa' => 'Dirección no registrada',
                'logo_empresa_url' => null,
                'imagen_fondo_login' => null, // NUEVO
            ]);
        }
        
        return $config;
    }
}