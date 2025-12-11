@extends('layouts.auth')

@section('content')
<h2 class="text-xl font-semibold text-primary mb-6 text-center">Bienvenido de nuevo</h2>

<form action="{{ route('login') }}" method="POST" class="space-y-6">
    @csrf
    
    <div>
        <label for="email" class="block mb-2 text-sm font-medium text-primary">Correo electrónico</label>
        <input type="email" name="email" id="email" required
            class="bg-light border border-accent text-primary text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5 placeholder-last" placeholder="nombre@correo.com">
        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="block mb-2 text-sm font-medium text-primary">Contraseña</label>
        <input type="password" name="password" id="password" required
            class="bg-light border border-accent text-primary text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5 placeholder-last" placeholder="••••••••">
        
        <div class="flex justify-end mt-1">
            <a href="{{ route('password.request') }}" class="text-xs text-secondary hover:underline">¿Olvidaste tu contraseña?</a>
        </div>
    </div>

    <button type="submit" class="w-full text-white bg-button hover:bg-hover focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
        Entrar
    </button>

    <div class="text-sm font-medium text-last text-center">
        ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-secondary hover:underline">Regístrate</a>
    </div>
</form>
@endsection