@component('mail::message')
<?php
    $tmp = explode(" ", $data->name);
    $name = $tmp[0]. " " . $tmp[count($tmp) - 1];
?>

# Olá <?=$name; ?>,

Sua senha foi alterada com sucesso.


Obrigado,<br>
A Equipa da {{ $company }}


@endcomponent
