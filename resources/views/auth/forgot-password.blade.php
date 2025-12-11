@extends('layouts.auth')

@section('content')
<h2 class="text-xl font-semibold text-primary mb-2 text-center">Recuperar Contraseña</h2>
<p class="text-last text-sm text-center mb-6">Te enviaremos un código de seguridad a tu correo.</p>

<form action="{{ route('password.email') }}" method="POST" class="space-y-6">
    @csrf
    <div>
        <label for="email" class="block mb-2 text-sm font-medium text-primary">Correo electrónico</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required
            class="bg-light border border-accent text-primary text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5 placeholder-last" placeholder="nombre@correo.com">
        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <button type="submit" class="w-full text-white bg-button hover:bg-hover focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
        Enviar Código
    </button>
    
    <div class="text-center">
        <a href="{{ route('login') }}" class="text-sm text-secondary hover:underline">Volver al inicio</a>
    </div>
</form>
@endsection