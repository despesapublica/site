<?php

class AjustesDirectosService_Controller extends Controller {
    static $allowed_actions = array(
        'pesquisa',
        'empNovas',
        'gvTotaisMesAno',
        'gvTotaisDia',
        'top'
    );

    function pesquisa(){
        $pesquisa = new AjustesDirectos_Pesquisa($_REQUEST);

        return json_encode($pesquisa->process());
    }

    function empNovas(){
        $pesquisa = new AjustesDirectos_EmpNovas($_REQUEST);

        return json_encode($pesquisa->process());
    }

    function top(){
        $pesquisa = new AjustesDirectos_Top($_REQUEST);
        return json_encode($pesquisa->process());
    }

    function gvTotaisMesAno(){
        $gv = new AjustesDirectos_GVTotaisMesAno($_REQUEST);

        return json_encode($gv->process());
    }

    function gvTotaisDia(){
        $gv = new AjustesDirectos_GVTotaisDia($_REQUEST);

        return json_encode($gv->process());
    }

    function view(){

    }

}