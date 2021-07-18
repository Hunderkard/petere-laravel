@component('mail::message')
El usuario {{$data['nombre']}} quiere reservar el {{$data['fecha']}}, a las {{$data['hora']}}. <br>

Son {{$data['personas']}}. <br>

@if ($data['ruedas'])
    Viene gente con silla de ruedas. <br>
@endif

@if ($data['carrito'])
    Viene un carrito. <br>
@endif
@if($data['alergias'])
    Alergias: {{$data['alergia']}} <br>
@endif
<hr>
Contacto: <br>
    Teléfono: {{$data['telefono']}} <br>
    Correo electrónico: {{$data['email']}} <br>
  

@component('mail::button', 
            ['url' => 'http://localhost:8000/confirmarReserva?email=' . $data['email'],
            'color' => 'green'])
Confirmar
@endcomponent


@component('mail::button', 
            ['url' => 'http://localhost:8000/rechazarReserva?email=' . $data['email'],
            'color' => 'red'])
Declinar
@endcomponent