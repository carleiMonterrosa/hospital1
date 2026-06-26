<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Turno;
use App\Models\User;
use App\Models\ConfiguracionEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TurnoController extends Controller
{
    // Mostrar vista del admin - CORREGIDO para pasar los permisos del usuario Y LA CONFIGURACIÓN
    public function admin()
    {
        $user = auth()->user();
        $permisos = $user->permisos ?? [];
        $usuariosBD = User::select('id', 'name', 'username', 'usuario_asesor', 'identificacion', 'servicio', 'nivel_acceso', 'modulos', 'permisos')->get();
        
        // 🔥 OBTENER LA CONFIGURACIÓN DE LA EMPRESA 🔥
        $configuracion = ConfiguracionEmpresa::first();
        
        return view('admin', compact('permisos', 'usuariosBD', 'configuracion'));
    }

    // ========== FUNCIÓN PARA GUARDAR PERSONA (PACIENTE) CON ZONA Y FECHA DE NACIMIENTO ==========
    public function storePersona(Request $request)
    {
        try {
            $request->validate([
                'identificacion' => 'required|string|max:250|unique:personas,identificacion',
                'primer_nombre' => 'required|string|max:50',
                'segundo_nombre' => 'nullable|string|max:50',
                'primer_apellido' => 'required|string|max:50',
                'segundo_apellido' => 'nullable|string|max:50',
                'zona' => 'required|in:U,R',
                'fecha_nacimiento' => 'required|date' // NUEVO: validación para fecha de nacimiento
            ]);

            $persona = Persona::create([
                'identificacion' => $request->identificacion,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'zona' => $request->zona,
                'fecha_nacimiento' => $request->fecha_nacimiento // NUEVO: guardar fecha de nacimiento
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paciente registrado correctamente',
                'persona' => $persona
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar: ' . $e->getMessage()
            ], 500);
        }
    }

    // Buscar persona por identificación (cédula) - AHORA TRAE EL CAMPO ZONA Y FECHA DE NACIMIENTO
    public function buscarPersona(Request $request)
    {
        try {
            $request->validate([
                'identificacion' => 'required|string|min:3|max:20'
            ]);

            // Buscar en la tabla personas - TRAE CAMPO ZONA Y FECHA_NACIMIENTO
            $persona = Persona::where('identificacion', $request->identificacion)->first();

            if ($persona) {
                // Generar nombre de usuario automático: primer_nombre.primer_apellido
                $usuarioGenerado = '';
                if ($persona->primer_nombre && $persona->primer_apellido) {
                    $usuarioGenerado = strtolower(
                        trim($persona->primer_nombre) . '.' . trim($persona->primer_apellido)
                    );
                    // Limpiar caracteres especiales y acentos
                    $usuarioGenerado = preg_replace('/[^a-z0-9.]/', '', $usuarioGenerado);
                }
                
                // Convertir zona: U = URBANO, R = RURAL
                $zonaTexto = '';
                if ($persona->zona === 'U') {
                    $zonaTexto = 'URBANO';
                } elseif ($persona->zona === 'R') {
                    $zonaTexto = 'RURAL';
                }
                
                return response()->json([
                    'success' => true,
                    'persona' => [
                        'identificacion' => $persona->identificacion,
                        'primer_nombre' => $persona->primer_nombre ?? '',
                        'segundo_nombre' => $persona->segundo_nombre ?? '',
                        'primer_apellido' => $persona->primer_apellido ?? '',
                        'segundo_apellido' => $persona->segundo_apellido ?? '',
                        'usuario' => $usuarioGenerado,
                        'zona' => $persona->zona ?? '',
                        'zona_texto' => $zonaTexto,
                        'fecha_nacimiento' => $persona->fecha_nacimiento ?? '' // NUEVO: enviar fecha de nacimiento
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Persona no encontrada'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========== MÉTODO MODIFICADO: Buscar usuario por username O por identificación ==========
    public function buscarUsuarioPorUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:2|max:50'
        ]);

        $busqueda = $request->username;
        
        // Buscar por username O por identificacion
        $usuario = User::where('username', $busqueda)
                        ->orWhere('identificacion', $busqueda)
                        ->first();

        if ($usuario) {
            $permisos = $usuario->permisos ?? [
                'login' => 0,
                'inicio' => 0,
                'agregar_paciente' => 0,
                'usuarios' => 0,
                'servicios' => 0,
                'reportes' => 0,
                'atender_turnos' => 0,
                'perfil' => 0,
                'agregar_nivel_acceso' => 0
            ];

            $permisosCompletos = [
                'login' => $permisos['login'] ?? 0,
                'inicio' => $permisos['inicio'] ?? 0,
                'agregar_paciente' => $permisos['agregar_paciente'] ?? 0,
                'usuarios' => $permisos['usuarios'] ?? 0,
                'servicios' => $permisos['servicios'] ?? 0,
                'reportes' => $permisos['reportes'] ?? 0,
                'atender_turnos' => $permisos['atender_turnos'] ?? 0,
                'perfil' => $permisos['perfil'] ?? 0,
                'agregar_nivel_acceso' => $permisos['agregar_nivel_acceso'] ?? 0,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Usuario encontrado',
                'usuario' => [
                    'id' => $usuario->id,
                    'name' => $usuario->name,
                    'username' => $usuario->username,
                    'identificacion' => $usuario->identificacion,
                    'email' => $usuario->email,
                    'permisos' => $permisosCompletos
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
                'username_buscado' => $busqueda
            ]);
        }
    }

    // ========== MÉTODO MODIFICADO: Guardar los permisos de un usuario en columna JSON ==========
    public function guardarPermisosUsuario(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'permisos' => 'required|array'
        ]);

        // Buscar por username O por identificacion
        $usuario = User::where('username', $request->username)
                        ->orWhere('identificacion', $request->username)
                        ->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }

        // Guardar permisos en la columna JSON 'permisos'
        $usuario->permisos = $request->permisos;
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Permisos actualizados correctamente'
        ]);
    }

    // Generar nuevo turno
    public function generarTurno(Request $request)
    {
        $request->validate([
            'persona_id' => 'required|string',
            'identificacion' => 'required|string',
            'nombre_completo' => 'required|string',
            'especialidad' => 'required|in:consulta-externa,odontologia,laboratorio,rayos-x'
        ]);

        $persona = Persona::where('identificacion', $request->persona_id)->first();
        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'Persona no encontrada'
            ], 404);
        }

        $especialidades = [
            'consulta-externa' => ['nombre' => 'Consulta Externa', 'prefijo' => 'CON', 'ventanilla' => 1],
            'odontologia' => ['nombre' => 'Odontología', 'prefijo' => 'ODO', 'ventanilla' => 2],
            'laboratorio' => ['nombre' => 'Laboratorio', 'prefijo' => 'LAB', 'ventanilla' => 3],
            'rayos-x' => ['nombre' => 'Rayos X', 'prefijo' => 'RAY', 'ventanilla' => 4],
        ];

        $esp = $especialidades[$request->especialidad];
        
        $ultimoTurno = Turno::where('especialidad', $request->especialidad)
            ->orderBy('id', 'desc')
            ->first();

        $numeroSimple = $ultimoTurno ? $ultimoTurno->numero_simple + 1 : 1;
        
        if ($numeroSimple > 50) {
            $numeroSimple = 1;
        }

        $formattedTurn = str_pad($numeroSimple, 2, '0', STR_PAD_LEFT);
        $turnoCompleto = $esp['prefijo'] . '-' . $formattedTurn;

        $turno = Turno::create([
            'numero' => $turnoCompleto,
            'numero_simple' => $numeroSimple,
            'persona_id' => $persona->identificacion,
            'identificacion' => $persona->identificacion,
            'nombre_persona' => $request->nombre_completo,
            'especialidad' => $request->especialidad,
            'nombre_especialidad' => $esp['nombre'],
            'ventanilla' => $esp['ventanilla'],
            'estado' => 'pendiente',
            'fecha' => now()->toDateString(),
            'hora' => now()->toTimeString()
        ]);

        return response()->json([
            'success' => true,
            'turno' => $turnoCompleto,
            'ventanilla' => $esp['ventanilla'],
            'message' => "Turno {$turnoCompleto} generado para {$request->nombre_completo}"
        ]);
    }

    // Obtener turnos para la pantalla TV
    public function getTurnosTV()
    {
        $turnos = Turno::with('persona')
            ->whereIn('estado', ['pendiente', 'llamado'])
            ->orderBy('created_at', 'asc')
            ->get();

        $primerosPorVentanilla = [];
        for ($i = 1; $i <= 4; $i++) {
            $primerosPorVentanilla[$i] = $turnos->where('ventanilla', $i)->first();
        }

        $otrosTurnos = $turnos->filter(function ($turno) use ($primerosPorVentanilla) {
            return !in_array($turno->id, array_column($primerosPorVentanilla, 'id'));
        })->values();

        return response()->json([
            'turnos' => $turnos,
            'primeros' => $primerosPorVentanilla,
            'otros' => $otrosTurnos
        ]);
    }

    // Cambiar estado de un turno
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,llamado,atendido,eliminado'
        ]);

        $turno = Turno::findOrFail($id);
        $turno->estado = $request->estado;
        $turno->save();

        return response()->json([
            'success' => true,
            'message' => "Turno {$turno->numero} actualizado a {$request->estado}"
        ]);
    }

    // Obtener un usuario por su username
    public function obtenerUsuarioPorUsername($username)
    {
        $usuario = User::where('username', $username)->first();
        
        if ($usuario) {
            return response()->json([
                'success' => true,
                'usuario' => [
                    'id' => $usuario->id,
                    'name' => $usuario->name,
                    'username' => $usuario->username,
                    'servicio' => $usuario->servicio ?? '',
                    'nivel_acceso' => $usuario->nivel_acceso ?? 'admin',
                    'modulos' => $usuario->modulos ?? [],
                    'usuario_asesor' => $usuario->usuario_asesor ?? '',
                    'identificacion' => $usuario->identificacion ?? ''
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ]);
    }

    // Actualizar un usuario existente
    public function actualizarUsuario(Request $request, $id)
    {
        try {
            $usuario = User::find($id);
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ]);
            }
            
            $request->validate([
                'name' => 'nullable|string',
                'username' => 'nullable|string',
                'password' => 'nullable|string|min:6',
                'usuario_asesor' => 'nullable|string',
                'identificacion' => 'nullable|string',
                'servicio' => 'nullable|string',
                'nivel_acceso' => 'nullable|string',
                'modulos' => 'nullable|array',
            ]);
            
            if ($request->has('name')) {
                $usuario->name = $request->name;
            }
            
            if ($request->has('username')) {
                $usuario->username = $request->username;
            }
            
            if ($request->has('password') && !empty($request->password)) {
                $usuario->password = bcrypt($request->password);
            }
            
            if ($request->has('usuario_asesor')) {
                $usuario->usuario_asesor = $request->usuario_asesor;
            }
            
            if ($request->has('identificacion')) {
                $usuario->identificacion = $request->identificacion;
            }
            
            if ($request->has('servicio')) {
                $usuario->servicio = $request->servicio;
            }
            
            if ($request->has('nivel_acceso')) {
                $usuario->nivel_acceso = $request->nivel_acceso;
            }
            
            if ($request->has('modulos')) {
                $usuario->modulos = $request->modulos;
            }
            
            $usuario->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente',
                'usuario' => [
                    'id' => $usuario->id,
                    'name' => $usuario->name,
                    'username' => $usuario->username,
                    'usuario_asesor' => $usuario->usuario_asesor,
                    'identificacion' => $usuario->identificacion,
                    'servicio' => $usuario->servicio,
                    'nivel_acceso' => $usuario->nivel_acceso,
                    'modulos' => $usuario->modulos
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ]);
        }
    }

    // Eliminar un usuario
    public function eliminarUsuario($id)
    {
        try {
            $usuario = User::find($id);
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ]);
            }
            
            if ($usuario->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes eliminar tu propio usuario'
                ]);
            }
            
            $usuario->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ]);
        }
    }

    // ==================== MÉTODOS PARA NIVELES DE ACCESO ====================
    
    /**
     * Obtener todos los niveles de acceso de la base de datos
     */
    public function getNivelesAcceso()
    {
        try {
            $niveles = DB::table('nivel_acceso')->orderBy('nombre')->get();
            return response()->json([
                'success' => true,
                'niveles' => $niveles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar niveles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guardar un nuevo nivel de acceso en la base de datos
     */
    public function storeNivelAcceso(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:100|unique:nivel_acceso,nombre'
            ]);

            $id = DB::table('nivel_acceso')->insertGetId([
                'nombre' => $request->nombre,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nivel de acceso agregado correctamente',
                'nivel' => [
                    'id' => $id,
                    'nombre' => $request->nombre
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== NUEVOS MÉTODOS PARA CONFIGURACIÓN DE EMPRESA ====================

    /**
     * Obtener la configuración de la empresa
     */
    public function getConfiguracion()
    {
        try {
            $config = ConfiguracionEmpresa::first();
            
            if ($config) {
                return response()->json([
                    'success' => true,
                    'configuracion' => [
                        'id' => $config->id,
                        'nombre_empresa' => $config->nombre_empresa,
                        'direccion_empresa' => $config->direccion_empresa,
                        'logo_empresa_url' => $config->logo_empresa_url,
                        'imagen_fondo_login' => $config->imagen_fondo_login,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'configuracion' => null
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar configuración: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guardar o actualizar la configuración de la empresa
     */
    public function guardarConfiguracion(Request $request)
    {
        try {
            $request->validate([
                'nombre_empresa' => 'required|string|max:150',
                'direccion_empresa' => 'nullable|string|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'fondo_login' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120'
            ]);

            $config = ConfiguracionEmpresa::first();

            if (!$config) {
                $config = new ConfiguracionEmpresa();
            }

            $config->nombre_empresa = $request->nombre_empresa;
            $config->direccion_empresa = $request->direccion_empresa;

            // Subir el logo si se envió
            if ($request->hasFile('logo')) {
                // Eliminar logo anterior si existe
                if ($config->logo_empresa_url) {
                    $oldPath = str_replace('/storage/', '', $config->logo_empresa_url);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $file = $request->file('logo');
                $filename = 'logo_empresa_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('logos', $filename, 'public');
                $config->logo_empresa_url = '/storage/' . $path;
            }

            // ========== SUBIR IMAGEN DE FONDO DEL LOGIN ==========
            if ($request->hasFile('fondo_login')) {
                // Eliminar fondo anterior si existe
                if ($config->imagen_fondo_login) {
                    $oldPath = str_replace('/storage/', '', $config->imagen_fondo_login);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $file = $request->file('fondo_login');
                $filename = 'fondo_login_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('fondos_login', $filename, 'public');
                $config->imagen_fondo_login = '/storage/' . $path;
            }
            // ==========================================================

            $config->save();

            return response()->json([
                'success' => true,
                'message' => 'Configuración guardada correctamente',
                'configuracion' => [
                    'id' => $config->id,
                    'nombre_empresa' => $config->nombre_empresa,
                    'direccion_empresa' => $config->direccion_empresa,
                    'logo_empresa_url' => $config->logo_empresa_url,
                    'imagen_fondo_login' => $config->imagen_fondo_login,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar configuración: ' . $e->getMessage()
            ], 500);
        }
    }
}