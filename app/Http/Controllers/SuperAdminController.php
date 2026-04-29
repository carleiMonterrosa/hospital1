<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SuperAdminController extends Controller
{
    // Mostrar el panel
    public function index()
    {
        return view('super-admin.panel');
    }

    // Buscar usuario - Busca en name, username o email
    public function buscarUsuario(Request $request)
    {
        $term = $request->input('username');
        
        \Log::info('Buscando usuario: ' . $term);
        
        // Buscar en name, username o email
        $usuario = User::where('name', 'like', "%{$term}%")
                       ->orWhere('username', 'like', "%{$term}%")
                       ->orWhere('email', 'like', "%{$term}%")
                       ->first();
        
        if ($usuario) {
            // Obtener permisos existentes o crear estructura por defecto
            $permisos = $usuario->permisos;
            
            // Si los permisos son un string (formato incorrecto), convertirlos a array
            if (is_string($permisos)) {
                $permisos = json_decode($permisos, true);
                if (!$permisos) {
                    $permisos = [
                        'login' => 0,
                        'agregar_paciente' => 0,
                        'usuarios' => 0,
                        'servicios' => 0,
                        'reportes' => 0,
                        'atender_turnos' => 0
                    ];
                }
            }
            
            if (!$permisos || is_null($permisos) || empty($permisos)) {
                $permisos = [
                    'login' => 0,
                    'agregar_paciente' => 0,
                    'usuarios' => 0,
                    'servicios' => 0,
                    'reportes' => 0,
                    'atender_turnos' => 0
                ];
            }
            
            return response()->json([
                'success' => true,
                'usuario' => [
                    'id' => $usuario->id,
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'username' => $usuario->username,
                    'permisos' => $permisos
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado'
        ]);
    }

    // Obtener permisos de un usuario
    public function obtenerPermisos($id)
    {
        $usuario = User::find($id);
        
        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado']);
        }
        
        $permisos = $usuario->permisos;
        
        if (is_string($permisos)) {
            $permisos = json_decode($permisos, true);
        }
        
        if (empty($permisos) || is_null($permisos)) {
            $permisos = [
                'login' => 0,
                'agregar_paciente' => 0,
                'usuarios' => 0,
                'servicios' => 0,
                'reportes' => 0,
                'atender_turnos' => 0
            ];
        }
        
        return response()->json([
            'success' => true,
            'permisos' => $permisos
        ]);
    }

    // Guardar permisos de un usuario
    public function guardarPermisosUsuario(Request $request)
    {
        \Log::info('=== INICIO DE GUARDAR PERMISOS ===');
        \Log::info('user_id: ' . $request->user_id);
        \Log::info('permisos: ' . json_encode($request->permisos));
        
        try {
            if (!$request->has('user_id')) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Falta el ID del usuario'
                ], 400);
            }
            
            $usuario = User::find($request->user_id);
            
            if (!$usuario) {
                \Log::error('Usuario no encontrado con ID: ' . $request->user_id);
                return response()->json([
                    'success' => false, 
                    'message' => 'Usuario no encontrado'
                ], 404);
            }
            
            \Log::info('Usuario encontrado: ' . $usuario->name);
            
            // Guardar los permisos como JSON válido
            $usuario->permisos = $request->permisos;
            $usuario->save();
            
            \Log::info('Permisos guardados EXITOSAMENTE');
            \Log::info('=== FIN DE GUARDAR PERMISOS ===');
            
            return response()->json([
                'success' => true,
                'message' => 'Permisos guardados correctamente'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('EXCEPCIÓN: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false, 
                'message' => 'Error del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    // Obtener permisos del usuario autenticado
    public function obtenerMisPermisos()
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false, 
                'message' => 'No autenticado'
            ], 401);
        }
        
        $permisos = $user->permisos ?? [];
        
        if (is_string($permisos)) {
            $permisos = json_decode($permisos, true);
        }
        
        $permisosUsuario = [
            'servicios' => $permisos['servicios'] ?? 0,
            'usuarios' => $permisos['usuarios'] ?? 0,
            'reportes' => $permisos['reportes'] ?? 0,
            'agregar_paciente' => $permisos['agregar_paciente'] ?? 0,
            'atender_turnos' => $permisos['atender_turnos'] ?? 0,
        ];
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
            ],
            'permisos' => $permisosUsuario
        ]);
    }
}