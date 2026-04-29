<?php

namespace App\Http\Controllers;

use App\Models\Persona;
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
            // Validar los datos
            $validator = Validator::make($request->all(), [
                'identificacion' => 'required|string|max:20|unique:personas,identificacion',
                'primer_nombre' => 'required|string|max:50',
                'segundo_nombre' => 'nullable|string|max:50',
                'primer_apellido' => 'required|string|max:50',
                'segundo_apellido' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Crear la persona
            $persona = Persona::create([
                'identificacion' => $request->identificacion,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
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
     */
    public function buscarPersona(Request $request)
    {
        try {
            $identificacion = $request->input('identificacion');
            
            $persona = Persona::where('identificacion', $identificacion)->first();
            
            if ($persona) {
                return response()->json([
                    'success' => true,
                    'persona' => $persona
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Persona no encontrada'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar persona: ' . $e->getMessage()
            ], 500);
        }
    }
}