<?php

/**
 * @version
 * @author Joao Martins <jfmartins@portugalmail.pt>
 * @copyright	Copyright (C) 2011 Joao Martins
 * @license		GNU General Public License version 2 or later
 */
//$dirname = dirname(__FILE__);
//require_once ($dirname . '/../JPridRequest.php');


class Entidades_Relacionadas extends jqgridRequest {

    public $idEntidade;
    public $tipo;

    public function __construct($params) {
        parent::__construct($params);
    }

    protected function parsingParams() {
        parent::parsingParams();

        $this->idEntidade = $this->getParam('idEntidade', CLEAN_STRING, '');
        $this->tipo = $this->getParam('tipo', CLEAN_STRING, '');
    }

    protected function setQuerys() {
        $where = ' WHERE 1 ';
        $this->queryParams = array();

        //$from = " FROM ad_contrato c inner join ad_contrato_entidade ce on c.id = ce.idcontrato inner join entidade e on ce.identidade = e.id ";
        $from = " FROM entidade_relacao c inner join entidade e on c.IdEntidadeRelacionada = e.id ";

        if (!empty($this->idEntidade)) {
            //$where .= " AND EXISTS(SELECT * FROM ad_contrato_entidade ce2 WHERE c.id = ce2.Idcontrato and ce2.IdEntidade=UNHEX(?) and ce.identidade <> ce2.identidade ";
            $where .= " AND c.IdEntidade = UNHEX(?) ";
            $this->queryParams[] = $this->idEntidade;

            if (!empty($this->tipo)) {
                $where .= " AND c.Tipo = ? ";
                $this->queryParams[] = $this->tipo;
            }
        }

        //$this->queryCount = "Select count(*) as count FROM ( Select DISTINCT ce.identidade " . $from . $where.' group by e.id ) as tt';
        $this->queryCount = "Select count(*) as count " . $from . $where;

        //$this->query = "SELECT HEX(e.id) as Id, e.Nome, e.Nif, e.DataCriacao, count(*) as Total, sum(preco) as ValorTotal, max(preco) as ValorMax, max(dataContrato) as DataMax ";
        //$this->query .= $from . $where . "group by e.id, e.nome, e.DataCriacao, e.Nif";
        $this->query = "SELECT HEX(e.id) as Id, e.Nome, e.Nif, e.DataCriacao, Total, ValorTotal, ValorMax, DataMax ";
        $this->query .= $from . $where;
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
            '<a href="entidades/View?ID=' . $row['Id'] . '" '.$cssClassContratado.'>' . $row['Nome']. '</a>',
            $row['DataCriacao'],
            $row['Total'],
            $row['ValorTotal'],
            $row['ValorMax'],
            $row['DataMax']
        );

        return $result;
    }

}

?>
