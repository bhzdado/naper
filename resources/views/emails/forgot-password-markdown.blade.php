@component('mail::message')
<?php

$tmp = explode(" ", $data->name);
$name = $tmp[0] . " " . $tmp[count($tmp) - 1];
?>

# Caro(a) <?= $name; ?>,

Esqueceu-se da sua senha?

<style>
a:link, a:visited {
  background-color: #FFFFFF;
  color: black;
  padding: 14px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
}

a:hover, a:active {
  background-color: #4CAF50;
}
.button {
    background-color: white;
    color: black;
    border: 2px solid #4CAF50;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin-left: 20px;
}
</style>

<table border="0" cellpadding="0" width="100%" cellspacing="0" style="margin:0;padding:0;max-width:612px;border:1px solid #edf1f1;color:#5f5f5f" class="m_-55372175003722518container">
    <tbody>
        <tr style="background:#edf1f1">
            <td style="text-align:center;vertical-align:top;font-size:0;padding:15px 0">
                <div style="width:300px;display:inline-block;vertical-align:middle">
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td style="font-size:14px;vertical-align:middle;">
                                    <a class="button button1" style="" href="<?=$url; ?>">Redefinir Senha</a>   
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<br>
Se não pretender alterar a sua senha ou não tiver efetuado este pedido, ignore e elimine esta mensagem.
<br><br>Obrigado,<br>
A Equipa da {{ $company }}


@endcomponent