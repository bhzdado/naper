@component('mail::message')
<?php
    $tmp = explode(" ", $data->name);
    $name = $tmp[0]. " " . $tmp[count($tmp) - 1];
?>

# Olá <?=$name; ?>,

Por favor confirme abaixo o seu cadastro <br>no sistema da <b>{{ $company }} </b>

@component('mail::button', ['url' => $url])
Confirmar
@endcomponent
    
@component('mail::panel')
Caso o botão não funcione copie e cole o seguinte endereço no seu navegador:
<br><br>
<b>{{$url }}</b>

@endcomponent


Obrigado,<br>
A Equipa da {{ $company }}


@endcomponent
