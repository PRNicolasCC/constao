@extends('layouts.auth')

@section('content')
<h2 class="text-xl font-semibold text-primary mb-2 text-center">Verificar Código</h2>
<p class="text-last text-sm text-center mb-6">Ingresa el código enviado a {{ request('email') }} y tu nueva contraseña.</p>

<form action="{{ route('password.verify') }}" method="POST" class="space-y-4">
    @csrf
    <input type="hidden" name="email" value="{{ request('email') }}">

    <div>
        <label for="otp" class="block mb-2 text-sm font-medium text-primary">Código de 6 dígitos</label>
        <input type="text" name="otp" id="otp" required maxlength="6"
            class="bg-light border border-accent text-primary text-center tracking-[0.5em] text-lg rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5 placeholder-last" placeholder="000000">
        @error('otp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="block mb-2 text-sm font-medium text-primary">Nueva contraseña</label>
        <input type="password" name="password" id="password" required
            class="bg-light border border-accent text-primary text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5">
    </div>
    
    <div>
        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-primary">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required
            class="bg-light border border-accent text-primary text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5">
    </div>

    <button type="submit" class="w-full text-white bg-button hover:bg-hover focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
        Cambiar Contraseña e Ingresar
    </button>
</form>
@endsection