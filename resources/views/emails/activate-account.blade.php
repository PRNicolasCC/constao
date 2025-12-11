@component('mail::message')
# Hola {{ $usuario->name }}

Gracias por registrarte. Para activar tu cuenta, haz clic en el siguiente botón:

@component('mail::button', ['url' => route('activar.cuenta', $token)])
Activar Cuenta
@endcomponent

Si tú no creaste esta cuenta, ignora este mensaje.

Gracias,<br>
{{ config('app.name') }}
@endcomponent
