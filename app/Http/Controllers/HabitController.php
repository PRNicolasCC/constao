<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    public function index()
    {
        // Obtenemos todos los hábitos
        $habits = Habit::all();
        return view('habits.index', compact('habits'));
    }

    public function store(Request $request)
    {
        // Validamos y creamos el hábito
        $request->validate(['name' => 'required|max:255']);
        Habit::create(['name' => $request->name]);
        
        return back();
    }

    public function toggle(Habit $habit)
    {
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

        return back();
    }
    
    public function destroy(Habit $habit)
    {
        $habit->delete();
        return back();
    }
}