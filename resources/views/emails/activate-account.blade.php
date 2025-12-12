@component('mail::message')
# Hola {{ $user->name }}

Gracias por registrarte. Para activar tu cuenta, haz clic en el siguiente botón:

@component('mail::button', ['url' => route('activate-account-email', ['userId' => $user->id, 'token' => $token])])
Activar Cuenta
@endcomponent

Si tú no creaste esta cuenta, ignora este mensaje.

Gracias,<br>
{{ config('app.name') }}
@endcomponent
