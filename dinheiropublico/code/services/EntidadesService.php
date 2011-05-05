<?php

class EntidadesService_Controller extends Controller {
    static $allowed_actions = array(
        'pesquisa',
        'entidadesRelacionadas',
        'top'
    );

    function pesquisa(){
        $pesquisa = new Entidades_Pesquisa($_REQUEST);

        return json_encode($pesquisa->process());
    }

    function top(){
        $pesquisa = new Entidades_Top($_REQUEST);
        return json_encode($pesquisa->process());
    }

    function entidadesRelacionadas(){
        $pesquisa = new Entidades_Relacionadas($_REQUEST);

        return json_encode($pesquisa->process());
    }

}