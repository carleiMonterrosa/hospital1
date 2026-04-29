<?php

namespace App\Http\Controllers;

use App\Models\HistorialLlamado;
use Illuminate\Http\Request;

class HistorialLlamadoController extends Controller
{
    // GET /api/historial-llamados - Obtener todos los registros
    public function index()
    {
        $historial = HistorialLlamado::orderBy('fecha_llamado', 'desc')->get();
        return response()->json($historial);
    }

    // POST /api/historial-llamados - Guardar nuevo registro
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_turno' => 'required|string|max:20',
                'llamado_por' => 'required|string|max:20',
                'observaciones' => 'nullable|string'
            ]);
            
            $historial = HistorialLlamado::create([
                'id_turno' => $validated['id_turno'],
                'fecha_llamado' => now(),
                'llamado_por' => $validated['llamado_por'],
                'observaciones' => $validated['observaciones'] ?? null
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Registro guardado correctamente',
                'data' => $historial
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE /api/historial-llamados/{id} - Eliminar registro
    public function destroy($id)
    {
        try {
            $historial = HistorialLlamado::findOrFail($id);
            $historial->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}