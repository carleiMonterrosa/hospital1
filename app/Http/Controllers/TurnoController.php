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
    public function admin()
    {
        $user = auth()->user();
        $permisos = $user->permisos ?? [];
        $usuariosBD = User::select('id', 'name', 'username', 'usuario_asesor', 'identificacion', 'servicio', 'nivel_acceso', 'modulos', 'permisos')->get();
        
        $configuracion = ConfiguracionEmpresa::first();
        
        return view('admin', compact('permisos', 'usuariosBD', 'configuracion'));
    }

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
                'fecha_nacimiento' => 'required|date'
            ]);

            $persona = Persona::create([
                'identificacion' => $request->identificacion,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'zona' => $request->zona,
                'fecha_nacimiento' => $request->fecha_nacimiento
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

    public function buscarPersona(Request $request)
    {
        try {
            $request->validate([
                'identificacion' => 'required|string|min:3|max:20'
            ]);

            $persona = Persona::where('identificacion', $request->identificacion)->first();

            if ($persona) {
                $usuarioGenerado = '';
                if ($persona->primer_nombre && $persona->primer_apellido) {
                    $usuarioGenerado = strtolower(
                        trim($persona->primer_nombre) . '.' . trim($persona->primer_apellido)
                    );
                    $usuarioGenerado = preg_replace('/[^a-z0-9.]/', '', $usuarioGenerado);
                }
                
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
                        'fecha_nacimiento' => $persona->fecha_nacimiento ?? ''
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

    public function buscarUsuarioPorUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:2|max:50'
        ]);

        $busqueda = $request->username;
        
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

    public function guardarPermisosUsuario(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'permisos' => 'required|array'
        ]);

        $usuario = User::where('username', $request->username)
                        ->orWhere('identificacion', $request->username)
                        ->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }

        $usuario->permisos = $request->permisos;
        $usuario->save();

        return response()->json([
            'success' => true,
            'message' => 'Permisos actualizados correctamente'
        ]);
    }

    /**
     * 🔥 CORREGIDO: Guarda un nuevo turno en la base de datos
     * Ahora guarda también el nombre del paciente en nombre_persona
     * El estado SIEMPRE es 'espera' (no acepta otro valor)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validar los datos recibidos
            $request->validate([
                'identificacion' => 'required|string|max:250',
                'id_modulo' => 'required|integer|min:1|max:8',
                'fecha_turno' => 'nullable|date'
                // 🔥 ELIMINADO: 'estado' de la validación para que no pueda ser enviado
            ]);

            // 🔥 id_modulo se guarda DIRECTAMENTE como viene de la interfaz
            $id_modulo_real = (int)$request->id_modulo;

            // 🔥 OBTENER EL NOMBRE DE LA ESPECIALIDAD SEGÚN EL MÓDULO
            $nombresEspecialidades = [
                1 => 'Consulta Externa',
                2 => 'Odontología',
                3 => 'Laboratorio Clínico',
                4 => 'Rayos X',
                5 => 'Consulta Externa',
                6 => 'Odontología',
                7 => 'Laboratorio Clínico',
                8 => 'Rayos X'
            ];
            $especialidad = $nombresEspecialidades[$id_modulo_real] ?? 'Consulta Externa';

            // 🔥 OBTENER EL NOMBRE DEL PACIENTE DESDE LA TABLA PERSONAS
            $persona = Persona::where('identificacion', $request->identificacion)->first();
            $nombrePersona = 'Paciente';
            if ($persona) {
                $nombrePersona = trim(
                    ($persona->primer_nombre ?? '') . ' ' . 
                    ($persona->segundo_nombre ?? '') . ' ' . 
                    ($persona->primer_apellido ?? '') . ' ' . 
                    ($persona->segundo_apellido ?? '')
                );
                $nombrePersona = preg_replace('/\s+/', ' ', $nombrePersona);
                if (empty($nombrePersona)) {
                    $nombrePersona = 'Paciente';
                }
            }

            // Preparar los datos para guardar
            $data = [
                'identificacion_paciente' => $request->identificacion,
                'id_modulo' => $id_modulo_real,
                'fecha_turno' => $request->fecha_turno ?? now(),
                'estado' => 'espera', // 🔥 SIEMPRE 'espera' - NO SE PUEDE CAMBIAR DESDE EL FRONTEND
                'especialidad' => $especialidad,
                'nombre_persona' => $nombrePersona
            ];

            // Guardar en la tabla turnos
            $id = DB::table('turnos')->insertGetId($data, 'id_turno');

            // Obtener el turno recién creado
            $turno = DB::table('turnos')->where('id_turno', $id)->first();

            // Generar número de turno con prefijo
            $prefijos = ['CON', 'ODO', 'LAB', 'RAY'];
            $prefijo = $prefijos[($id_modulo_real - 1) % 4] ?? 'CON';
            $numeroTurno = $prefijo . '-' . str_pad($id, 2, '0', STR_PAD_LEFT);

            return response()->json([
                'success' => true,
                'message' => "Turno {$numeroTurno} generado correctamente",
                'data' => [
                    'id_turno' => $id,
                    'numero_turno' => $numeroTurno,
                    'identificacion_paciente' => $turno->identificacion_paciente,
                    'id_modulo' => $turno->id_modulo,
                    'especialidad' => $turno->especialidad,
                    'fecha_turno' => $turno->fecha_turno,
                    'estado' => $turno->estado, // Siempre será 'espera'
                    'nombre_persona' => $turno->nombre_persona
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar turno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera un turno (método alternativo que redirige a store)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generarTurno(Request $request)
    {
        return $this->store($request);
    }

    /**
     * 🔥 CORREGIDO: Genera un turno directamente desde el formulario
     * Ahora guarda también el nombre del paciente
     * El estado SIEMPRE es 'espera'
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generarTurnoDesdeFormulario(Request $request)
    {
        try {
            $identificacion = $request->input('identificacion');
            $id_modulo = $request->input('id_modulo');
            
            if (!$identificacion || !$id_modulo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Faltan datos: identificación y módulo son requeridos',
                    'datos_recibidos' => $request->all()
                ], 400);
            }
            
            // 🔥 id_modulo se guarda DIRECTAMENTE
            $id_modulo_real = (int)$id_modulo;

            $nombresEspecialidades = [
                1 => 'Consulta Externa',
                2 => 'Odontología',
                3 => 'Laboratorio Clínico',
                4 => 'Rayos X',
                5 => 'Consulta Externa',
                6 => 'Odontología',
                7 => 'Laboratorio Clínico',
                8 => 'Rayos X'
            ];
            $especialidad = $nombresEspecialidades[$id_modulo_real] ?? 'Consulta Externa';

            // 🔥 OBTENER EL NOMBRE DEL PACIENTE
            $persona = Persona::where('identificacion', $identificacion)->first();
            $nombrePersona = 'Paciente';
            if ($persona) {
                $nombrePersona = trim(
                    ($persona->primer_nombre ?? '') . ' ' . 
                    ($persona->segundo_nombre ?? '') . ' ' . 
                    ($persona->primer_apellido ?? '') . ' ' . 
                    ($persona->segundo_apellido ?? '')
                );
                $nombrePersona = preg_replace('/\s+/', ' ', $nombrePersona);
                if (empty($nombrePersona)) {
                    $nombrePersona = 'Paciente';
                }
            }
            
            $id = DB::table('turnos')->insertGetId([
                'identificacion_paciente' => $identificacion,
                'id_modulo' => $id_modulo_real,
                'fecha_turno' => now(),
                'estado' => 'espera', // 🔥 SIEMPRE 'espera'
                'especialidad' => $especialidad,
                'nombre_persona' => $nombrePersona
            ], 'id_turno');
            
            $turno = DB::table('turnos')->where('id_turno', $id)->first();
            
            $prefijos = ['CON', 'ODO', 'LAB', 'RAY'];
            $prefijo = $prefijos[($id_modulo_real - 1) % 4] ?? 'CON';
            $numeroTurno = $prefijo . '-' . str_pad($id, 2, '0', STR_PAD_LEFT);
            
            return response()->json([
                'success' => true,
                'message' => "✅ Turno {$numeroTurno} generado correctamente",
                'data' => [
                    'id_turno' => $id,
                    'numero_turno' => $numeroTurno,
                    'identificacion_paciente' => $turno->identificacion_paciente,
                    'id_modulo' => $turno->id_modulo,
                    'especialidad' => $turno->especialidad,
                    'fecha_turno' => $turno->fecha_turno,
                    'estado' => $turno->estado, // Siempre será 'espera'
                    'nombre_persona' => $turno->nombre_persona
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al generar turno: ' . $e->getMessage(),
                'linea_error' => $e->getLine(),
                'archivo_error' => $e->getFile()
            ], 500);
        }
    }

    /**
     * 🔥 CORREGIDO: Obtiene los turnos en ESPERA para la pantalla de TV
     * Solo muestra los turnos que están en estado 'espera'
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTurnosTV()
    {
        try {
            // 🔥 CAMBIADO: SOLO mostrar turnos en 'espera'
            $turnos = DB::table('turnos')
               ->whereIn('estado', ['espera', 'llamado']) // Solo turnos en espera
                ->orderBy('id_turno', 'asc')
                ->get();

            $primerosPorVentanilla = [];
            for ($i = 1; $i <= 4; $i++) {
                $primerosPorVentanilla[$i] = $turnos->where('id_modulo', $i)->first();
            }

            $otrosTurnos = $turnos->filter(function ($turno) use ($primerosPorVentanilla) {
                return !in_array($turno->id_turno, array_column($primerosPorVentanilla, 'id_turno'));
            })->values();

            $turnosMapeados = $turnos->map(function ($turno) {
                $prefijos = ['CON', 'ODO', 'LAB', 'RAY'];
                $prefijo = $prefijos[($turno->id_modulo - 1) % 4] ?? 'CON';
                $turno->numero = $prefijo . '-' . str_pad($turno->id_turno, 2, '0', STR_PAD_LEFT);
                return $turno;
            });

            return response()->json([
                'success' => true,
                'turnos' => $turnosMapeados,
                'primeros' => $primerosPorVentanilla,
                'otros' => $otrosTurnos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔥 NUEVO: Obtiene los turnos en ESPERA para el módulo específico
     * Este método es para "Ver Turnos" en el panel de administración
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTurnosEnEspera(Request $request)
    {
        try {
            $modulo = $request->input('modulo', 1);
            
            $turnos = DB::table('turnos')
                ->where('estado', 'espera')
                ->where('id_modulo', $modulo)
                ->whereDate('fecha_turno', now()->toDateString())
                ->orderBy('id_turno', 'asc')
                ->get();

            // Agregar número de turno
            $turnos = $turnos->map(function ($turno) {
                $prefijos = ['CON', 'ODO', 'LAB', 'RAY'];
                $prefijo = $prefijos[($turno->id_modulo - 1) % 4] ?? 'CON';
                $turno->numero = $prefijo . '-' . str_pad($turno->id_turno, 2, '0', STR_PAD_LEFT);
                return $turno;
            });

            return response()->json([
                'success' => true,
                'turnos' => $turnos,
                'total' => $turnos->count(),
                'modulo' => $modulo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener turnos en espera: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambia el estado de un turno específico
     * Usar: 'llamado' para atender/llamar, 'atendido' para finalizar
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $request->validate([
                'estado' => 'required|in:espera,llamado,atendido,eliminado'
            ]);

            $turno = DB::table('turnos')->where('id_turno', $id)->first();
            
            if (!$turno) {
                return response()->json([
                    'success' => false,
                    'message' => 'Turno no encontrado'
                ], 404);
            }

            DB::table('turnos')
                ->where('id_turno', $id)
                ->update([
                    'estado' => $request->estado
                ]);

            $prefijos = ['CON', 'ODO', 'LAB', 'RAY'];
            $prefijo = $prefijos[($turno->id_modulo - 1) % 4] ?? 'CON';
            $numeroTurno = $prefijo . '-' . str_pad($id, 2, '0', STR_PAD_LEFT);

            return response()->json([
                'success' => true,
                'message' => "Turno {$numeroTurno} actualizado a {$request->estado}"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔥 NUEVO: Elimina un turno de la base de datos
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $turno = DB::table('turnos')->where('id_turno', $id)->first();
            
            if (!$turno) {
                return response()->json([
                    'success' => false,
                    'message' => 'Turno no encontrado'
                ], 404);
            }

            DB::table('turnos')->where('id_turno', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Turno eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar turno: ' . $e->getMessage()
            ], 500);
        }
    }

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

            if ($request->hasFile('logo')) {
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

            if ($request->hasFile('fondo_login')) {
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

    /**
     * 🔥🔥🔥 NUEVO MÉTODO: ELIMINA LOS TURNOS CON NOMBRE "undefined"
     * Esta función elimina físicamente los turnos que tienen nombre_persona = 'undefined' o NULL
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarTurnosUndefined()
    {
        try {
            $eliminados = DB::table('turnos')
                ->where(function($query) {
                    $query->whereNull('nombre_persona')
                          ->orWhere('nombre_persona', '')
                          ->orWhere('nombre_persona', 'undefined')
                          ->orWhere('nombre_persona', 'null')
                          ->orWhere('nombre_persona', 'NULL');
                })
                ->delete();

            return response()->json([
                'success' => true,
                'message' => "✅ Se eliminaron {$eliminados} turnos con nombre 'undefined' de la base de datos",
                'eliminados' => $eliminados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar turnos: ' . $e->getMessage()
            ], 500);
        }
    }
}