@component('mail::message')
No se pudo reservar ese día a esa hora.

@component('mail::button', ['url' => 'http://localhost:4200/reserva', 'color' => 'red'])
Intentarlo Otra Vez
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
