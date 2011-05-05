<?php

/**
 * @version
 * @author Joao Martins <jfmartins@portugalmail.pt>
 * @copyright	Copyright (C) 2011 Joao Martins
 * @license		GNU General Public License version 2 or later
 */
//$dirname = dirname(__FILE__);
//require_once ($dirname . '/../JPridRequest.php');


class Entidades_Top extends jqgridRequest {

    public $tipo;
    
    public function __construct($params) {
        parent::__construct($params);
    }

    protected function parsingParams() {
        parent::parsingParams();

        $this->tipo = $this->getParam('tipo', CLEAN_STRING, '');
    }

    protected function setQuerys() {
        $where = ' WHERE 1 ';
        $colunas = 'SELECT HEX(e.Id) as Id, e.Nif, e.Nome, e.DataCriacao ';
        $this->queryParams = array();

        $from = " FROM entidade e left join entidade_estatisticas ee on (e.Id = ee.IdEntidade AND ee.Ano = -1) ";

        if (!empty($this->tipo)) {
            if ($this->tipo == 'C') {
                 $colunas .= ', ee.NumRegistosAdContratadas as NumRegistos, ee.ValorTotalAdContratadas as ValorTotal';

                 if($this->sortName == 'ValorTotal')
                     $this->sortName = 'ValorTotalAdContratadas';
                 if($this->sortName == 'NumRegistos')
                     $this->sortName = 'NumRegistosAdContratadas';
            } else if ($this->tipo == 'A') {
                 $colunas .= ', ee.NumRegistosAdAdjudicadas as NumRegistos, ee.ValorTotalAdAdjudicadas as ValorTotal';
                 if($this->sortName == 'ValorTotal')
                     $this->sortName = 'ValorTotalAdAdjudicadas';
                 if($this->sortName == 'NumRegistos')
                     $this->sortName = 'NumRegistosAdAdjudicadas';
            }
        }

        $this->queryCount = "";
        $this->numTotalRecords = $this->limit;

        $this->query .= $colunas . $from . $where;
    }

    protected function processRow($row, $indice) {
        $result = array();

        $cssClassContratado = '';
        if (!empty($row['DataCriacao'])) {
            $t_dataCriacao = strtotime($row['DataCriacao']);
            if ((time() - $t_dataCriacao) <= 31556926) {
                $cssClassContratado = 'class="empresaRecente"';
            }
        }

        $result = array();
        $result['id'] = $row['Id'];

        $result['cell'] = array(
            '<a href="entidades/View?ID=' . $row['Id'] . '" '.$cssClassContratado.'>' . $row['Nif'] . '</a>',
            '<a href="entidades/View?ID=' . $row['Id'] . '">'.$row['Nome'].'</a>',
            $row['NumRegistos'],
            $row['ValorTotal']
        );

        return $result;
    }

}

?>
