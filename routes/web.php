<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HabitController;
use Illuminate\Support\Facades\Route;

// Rutas para invitados (Guest)
Route::middleware('guest')->group(function () {
    // Registro
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Recuperación de Contraseña (Flujo OTP)
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');
    
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('password.otp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify');
    
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Rutas protegidas (Auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [HabitController::class, 'index'])->name('habits.index');
    Route::post('/habits', [HabitController::class, 'store'])->name('habits.store');
    Route::post('/habits/{habit}/toggle', [HabitController::class, 'toggle'])->name('habits.toggle');
    Route::delete('/habits/{habit}', [HabitController::class, 'destroy'])->name('habits.destroy');
});