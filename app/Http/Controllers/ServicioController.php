<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    // Obtener todos los servicios
    public function index()
    {
        try {
            $servicios = Servicio::orderBy('id_servicio', 'asc')->get();
            return response()->json($servicios);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    // Guardar nuevo servicio
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre_servicio' => 'required|string|max:100',
                'descripcion' => 'nullable|string',
                'requiere_orden_medica' => 'boolean',
                'activo' => 'boolean',
                'id_modulo' => 'nullable|integer',
                'id_area' => 'nullable|integer'
            ]);
            
            $servicio = Servicio::create([
                'nombre_servicio' => $request->nombre_servicio,
                'descripcion' => $request->descripcion ?? '',
                'requiere_orden_medica' => $request->requiere_orden_medica ?? false,
                'activo' => $request->activo ?? true,
                'id_modulo' => $request->id_modulo ?? null,
                'id_area' => $request->id_area ?? null
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $servicio,
                'message' => 'Servicio creado exitosamente'
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    // Actualizar servicio
    public function update(Request $request, $id)
    {
        try {
            $servicio = Servicio::findOrFail($id);
            
            $request->validate([
                'nombre_servicio' => 'required|string|max:100',
                'descripcion' => 'nullable|string',
                'requiere_orden_medica' => 'boolean',
                'activo' => 'boolean'
            ]);
            
            $servicio->update([
                'nombre_servicio' => $request->nombre_servicio,
                'descripcion' => $request->descripcion ?? $servicio->descripcion,
                'requiere_orden_medica' => $request->requiere_orden_medica ?? $servicio->requiere_orden_medica,
                'activo' => $request->activo ?? $servicio->activo
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $servicio,
                'message' => 'Servicio actualizado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    // Eliminar servicio (eliminación física)
    public function destroy($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);
            $nombre = $servicio->nombre_servicio;
            $servicio->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Servicio \"{$nombre}\" eliminado permanentemente"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}