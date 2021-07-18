@component('mail::message')
Reserva aceptada.



@component('mail::button', ['url' => 'http://localhost:4200/carta', 'color' => 'green'])
Ver el menú
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
