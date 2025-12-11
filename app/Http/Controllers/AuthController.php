<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    // --- VISTAS ---
    public function showRegister() { return view('auth.register'); }
    public function showLogin() { return view('auth.login'); }
    public function showForgot() { return view('auth.forgot-password'); }
    public function showVerifyOtp() { return view('auth.verify-otp'); }

    // --- ACCIONES ---

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed', // requiere campo password_confirmation
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('habits.index');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // --- RECUPERACIÓN CON OTP: Utilizaremos Cache para almacenar el código temporalmente (5 minutos) en lugar de la base de datos para mantenerlo simple y rápido. ---

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Generar código de 6 dígitos
        $otp = rand(100000, 999999);
        
        // Guardar en caché por 10 minutos asociado al email
        Cache::put('otp_' . $request->email, $otp, 600);

        // Enviar correo (Asegúrate de configurar .env con MAIL_MAILER=log para pruebas locales)
        try {
            Mail::to($request->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Error enviando el correo. Revisa tus logs.']);
        }

        // Redirigir a la vista de ingresar código pasando el email
        return redirect()->route('password.otp', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
            'password' => 'required|min:8|confirmed'
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return back()->withErrors(['otp' => 'El código es incorrecto o ha expirado.']);
        }

        // Cambiar contraseña
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Limpiar caché y loguear
        Cache::forget('otp_' . $request->email);
        Auth::login($user);

        return redirect()->route('habits.index');
    }
}