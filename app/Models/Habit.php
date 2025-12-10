<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relación: Un hábito tiene muchos registros
    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }

    // Helper para saber si el hábito se completó HOY
    public function isCompletedToday()
    {
        return $this->logs()
                    ->whereDate('completed_at', now()->today())
                    ->exists();
    }
}