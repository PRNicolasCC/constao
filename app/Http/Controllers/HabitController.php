<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    public function index()
    {
        // Obtenemos todos los hábitos
        $habits = Auth::user()->habits()->with('logs')->get();
        return view('habits.index', compact('habits'));
    }

    public function store(Request $request)
    {
        // Validamos y creamos el hábito
        $request->validate(['name' => 'required|max:255']);
        Auth::user()->habits()->create(['name' => $request->name]);
        
        return back()->with('success', 'Hábito creado correctamente.');
    }

    public function toggle(Habit $habit)
    {
        $this->validateHabitOwnership($habit->user_id);

        // Buscamos si ya existe un log para hoy
        $log = $habit->logs()->whereDate('completed_at', now()->today())->first();

        if ($log) {
            // Si existe, lo borramos (desmarcar)
            $log->delete();
        } else {
            // Si no existe, lo creamos (marcar)
            $habit->logs()->create([
                'completed_at' => now()->today()
            ]);
        }

        return back()->with('success', 'Hábito actualizado correctamente.');
    }
    
    public function destroy(Habit $habit)
    {
        // Verificamos que el hábito pertenece al usuario actual
        $this->validateHabitOwnership($habit->user_id);
        
        $habit->delete();
        return back()->with('success', 'Hábito eliminado correctamente.');
    }

    private function validateHabitOwnership(int $habitUserId): void
    {
        if ($habitUserId !== Auth::id()) {
            abort(403, 'No tienes permiso para realizar esta acción en este hábito.');
        }
    }
}