<?php

namespace App\Http\Controllers;

// ...existing code...

use Illuminate\Http\Request;
use App\Models\Tarea;

class TareaController extends Controller
{
    // Listar todas las tareas
    public function index()
    {
        return Tarea::with('usuario')->get();
    }

    // Crear una nueva tarea
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:pendiente,en_progreso,completada',
            'fecha_vencimiento' => 'nullable|date',
        ]);
        $tarea = Tarea::create($validated);
        return response()->json($tarea, 201);
    }
}
