<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonaController extends Controller
{
    /**
     * Store a newly created person in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validar los datos - AGREGADO CAMPO ZONA
            $validator = Validator::make($request->all(), [
                'identificacion' => 'required|string|max:20|unique:personas,identificacion',
                'primer_nombre' => 'required|string|max:50',
                'segundo_nombre' => 'nullable|string|max:50',
                'primer_apellido' => 'required|string|max:50',
                'segundo_apellido' => 'nullable|string|max:50',
                'zona' => 'required|string|in:U,R',  // <--- AGREGADO
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear la persona - AGREGADO CAMPO ZONA
            $persona = Persona::create([
                'identificacion' => $request->identificacion,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'zona' => $request->zona,  // <--- AGREGADO
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Persona registrada exitosamente',
                'persona' => $persona
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la persona: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search for a person by identification.
     * Ahora también genera un nombre de usuario automático y trae la zona
     */
    public function buscarPersona(Request $request)
    {
        try {
            $identificacion = $request->input('identificacion');
            
            // Buscar por identificación en tabla personas
            $persona = Persona::where('identificacion', $identificacion)->first();
            
            if ($persona) {
                // Buscar si ya existe un usuario con esta identificación
                $user = User::where('username', $identificacion)->first();
                
                // Generar nombre de usuario automático: primer_nombre.primer_apellido
                $usuarioGenerado = '';
                if ($persona->primer_nombre && $persona->primer_apellido) {
                    $usuarioGenerado = strtolower(
                        trim($persona->primer_nombre) . '.' . trim($persona->primer_apellido)
                    );
                    // Reemplazar espacios y caracteres especiales
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
                        'primer_nombre' => $persona->primer_nombre,
                        'segundo_nombre' => $persona->segundo_nombre,
                        'primer_apellido' => $persona->primer_apellido,
                        'segundo_apellido' => $persona->segundo_apellido,
                        'usuario' => $user ? $user->username : $usuarioGenerado,
                        'zona' => $persona->zona ?? '',      // Valor original: U o R
                        'zona_texto' => $zonaTexto,           // Texto: URBANO o RURAL
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
                'message' => 'Error al buscar persona: ' . $e->getMessage()
            ], 500);
        }
    }
}