<?php

use App\Http\Controllers\HabitController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HabitController::class, 'index'])->name('habits.index');
Route::post('/habits', [HabitController::class, 'store'])->name('habits.store');
Route::post('/habits/{habit}/toggle', [HabitController::class, 'toggle'])->name('habits.toggle');
Route::delete('/habits/{habit}', [HabitController::class, 'destroy'])->name('habits.destroy');