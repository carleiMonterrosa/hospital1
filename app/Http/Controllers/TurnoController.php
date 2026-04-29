<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TurnoController extends Controller
{
    // Mostrar vista del admin - CORREGIDO para pasar los permisos del usuario
    public function admin()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();
        
        // Obtener sus permisos de la base de datos
        $permisos = $user->permisos ?? [];
        
        // Pasar los permisos a la vista
        return view('admin', compact('permisos'));
    }

    // Buscar persona por identificación (cédula) - CORREGIDO
    public function buscarPersona(Request $request)
    {
        try {
            $request->validate([
                'identificacion' => 'required|string|min:3|max:20' // Cambiado de min:5 a min:3
            ]);

            $persona = Persona::where('identificacion', $request->identificacion)->first();

            if ($persona) {
                return response()->json([
                    'success' => true,
                    'persona' => [
                        'id' => $persona->id,
                        'identificacion' => $persona->identificacion,
                        'primer_nombre' => $persona->primer_nombre,
                        'segundo_nombre' => $persona->segundo_nombre,
                        'primer_apellido' => $persona->primer_apellido,
                        'segundo_apellido' => $persona->segundo_apellido,
                        'nombres' => $persona->nombres ?? ($persona->primer_nombre . ' ' . ($persona->segundo_nombre ?? '')),
                        'apellidos' => $persona->apellidos ?? ($persona->primer_apellido . ' ' . ($persona->segundo_apellido ?? '')),
                        'nombre_completo' => $persona->nombre_completo ?? ($persona->primer_nombre . ' ' . ($persona->segundo_nombre ?? '') . ' ' . $persona->primer_apellido . ' ' . ($persona->segundo_apellido ?? ''))
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

    // ==================== MÉTODO PARA BUSCAR USUARIO EN LA TABLA USERS ====================
    /**
     * Buscar usuario por nombre de usuario (username) en la tabla users
     * Retorna los permisos del usuario encontrado
     */
    public function buscarUsuarioPorUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:2|max:50'
        ]);

        $username = $request->username;
        
        // Buscar por username exacto
        $usuario = User::where('username', $username)->first();

        if ($usuario) {
            // Obtener los permisos del usuario (decodificar JSON automáticamente por el cast 'array')
            $permisos = $usuario->permisos ?? [
                'login' => 0,
                'agregar_paciente' => 0,
                'usuarios' => 0,
                'servicios' => 0,
                'reportes' => 0,
                'atender_turnos' => 0,
                'perfil' => 0
            ];

            // Asegurar que todos los campos de permisos existan
            $permisosCompletos = [
                'login' => $permisos['login'] ?? 0,
                'agregar_paciente' => $permisos['agregar_paciente'] ?? 0,
                'usuarios' => $permisos['usuarios'] ?? 0,
                'servicios' => $permisos['servicios'] ?? 0,
                'reportes' => $permisos['reportes'] ?? 0,
                'atender_turnos' => $permisos['atender_turnos'] ?? 0,
                'perfil' => $permisos['perfil'] ?? 0,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Usuario encontrado',
                'usuario' => [
                    'id' => $usuario->id,
                    'name' => $usuario->name,
                    'username' => $usuario->username,
                    'email' => $usuario->email,
                    'permisos' => $permisosCompletos
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
                'username_buscado' => $username
            ]);
        }
    }

    // ==================== MÉTODO PARA GUARDAR PERMISOS EN LA BASE DE DATOS ====================
    /**
     * Guardar los permisos de un usuario en la tabla users
     */
    public function guardarPermisosUsuario(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'permisos' => 'required|array'
        ]);

        $usuario = User::where('username', $request->username)->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }

        // Actualizar los permisos del usuario
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
}