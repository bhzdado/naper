<div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
    <h5>Erro 401</h5>
</div>
<div class="widget-content">
    <div class="error_ex">
        <h2>401</h2>
        <h4>Opps, você não pode prosseguir.</h4>
        <p><b><?php
            $name = explode(" ", $userAuth->name);
            echo $name[0];
            ?></b>, infelizmente você não tem permissão para acessar essa parte do sistema.</p>
        <!--
        <a class="btn btn-warning btn-big"  href="/admin">Voltar ao início</a> </div>
        -->
</div>