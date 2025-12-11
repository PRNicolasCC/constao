<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

use App\Models\Token;

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
            'email' => 'required|email:rfc,dns|unique:users', // El ':rfc,dns' se añade al email para validar mejor el formato RFC y verificar que el dominio exista
            'password' => 'required|min:8|confirmed', // 'confirmed' requiere campo password_confirmation
        ],
        [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo no tiene un formato válido.',
            'email.unique' => 'El correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Token::create([
            'token' => bin2hex(random_bytes(32)),
            'user_id' => $user->id,
            'expires_at' => now()->addMinutes(60),
        ]);

        Mail::to($request->email)->send(new OTPMail($token->token));

        return redirect()->route('login')->with('success', 'Registro exitoso. Por favor, verifica tu correo para activar tu cuenta.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo no tiene un formato válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credentials)) {
            if (is_null(Auth::user()->email_verified_at)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Por favor, verifica tu dirección de correo electrónico para iniciar sesión.'])->withInput($request->only('email'));
            }
            
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->withInput($request->only('email'));
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
        $request->validate(
            ['email' => 'required|email:rfc,dns|exists:users,email'],
            [
                'email.required' => 'El correo es obligatorio.',
                'email.email' => 'El correo no tiene un formato válido.',
                'email.exists' => 'El correo no está registrado.'
            ]
        );

        // Generar código de 6 dígitos
        $otp = rand(100000, 999999);
        
        // Guardar en caché por 10 minutos asociado al email
        Cache::put('otp_' . $request->email, $otp, 600);

        // Enviar correo (Asegúrate de configurar .env con MAIL_MAILER=log para pruebas locales)
        Mail::to($request->email)->send(new OTPMail($otp));

        // Redirigir a la vista de ingresar código pasando el email
        return redirect()->route('password.otp', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns',
            'otp' => 'required|numeric',
            'password' => 'required|min:8|confirmed'
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo no tiene un formato válido.',
            'otp.required' => 'El código es obligatorio.',
            'otp.numeric' => 'El código debe ser numérico.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
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