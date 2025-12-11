@extends('layouts.auth')

@section('content')
<h2 class="text-xl font-semibold text-primary mb-6 text-center">Crear Cuenta</h2>

<form action="{{ route('register') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label for="name" class="block mb-2 text-sm font-medium text-primary">Nombre completo</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required
            class="bg-light border border-accent text-primary text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5 placeholder-last" placeholder="Tu nombre">
    </div>

    <div>
        <label for="email" class="block mb-2 text-sm font-medium text-primary">Correo electrónico</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required
            class="bg-light border border-accent text-primary text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5 placeholder-last" placeholder="nombre@correo.com">
        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="block mb-2 text-sm font-medium text-primary">Contraseña</label>
        <input type="password" name="password" id="password" required
            class="bg-light border border-accent text-primary text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5 placeholder-last" placeholder="••••••••">
    </div>

    <div>
        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-primary">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required
            class="bg-light border border-accent text-primary text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5 placeholder-last" placeholder="••••••••">
    </div>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <button type="submit" class="w-full text-white bg-button hover:bg-hover focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
        Registrarse
    </button>

    <div class="text-sm font-medium text-last text-center">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-secondary hover:underline">Inicia sesión</a>
    </div>
</form>
@endsection