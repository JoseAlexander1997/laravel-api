<?php

namespace App\Http\Controllers\Api;

use App\Exports\TareasPendientesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Usuario;

class TareaController extends Controller
{
    /**
     * Listar todas las tareas con su usuario asignado
     */
    public function index()
    {
        $tareas = Tarea::with('usuario:id,nombre,email')->get();

        return response()->json($tareas, 200);
    }

    /**
     * Crear nueva tarea y asignar a un usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|in:pendiente,en_progreso,completada',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $tarea = Tarea::create($validated);

        return response()->json([
            'message' => 'Tarea creada correctamente',
            'data' => $tarea
        ], 201);
    }

    /**
     * Mostrar una tarea específica
     */
    public function show($id)
    {
        $tarea = Tarea::with('usuario:id,nombre,email')->find($id);

        if (!$tarea) {
            return response()->json(['message' => 'Tarea no encontrada'], 404);
        }

        return response()->json($tarea, 200);
    }

    /**
     * Actualizar tarea
     */
    public function update(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);

        $validated = $request->validate([
            'usuario_id' => 'sometimes|exists:usuarios,id',
            'titulo' => 'sometimes|required|string|max:150',
            'descripcion' => 'nullable|string',
            'estado' => 'nullable|in:pendiente,en_progreso,completada',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $tarea->update($validated);

        return response()->json([
            'message' => 'Tarea actualizada correctamente',
            'data' => $tarea
        ], 200);
    }

    /**
     * Eliminar tarea
     */
    public function destroy($id)
    {
        $tarea = Tarea::find($id);

        if (!$tarea) {
            return response()->json(['message' => 'Tarea no encontrada'], 404);
        }

        $tarea->delete();

        return response()->json(['message' => 'Tarea eliminada correctamente'], 200);
    }

   

    public function exportPendientes()
    {
        // Forzar cabeceras correctas
        return Excel::download(new TareasPendientesExport, 'tareas_pendientes.xlsx');
    }
}


