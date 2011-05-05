<?php

/**
 * @version
 * @author Joao Martins <jfmartins@portugalmail.pt>
 * @copyright	Copyright (C) 2011 Joao Martins
 * @license		GNU General Public License version 2 or later
 */
//$dirname = dirname(__FILE__);
//require_once ($dirname . '/../JPridRequest.php');


class Entidades_Pesquisa extends jqgridRequest {

    public $sk;
    public $nomeStartWith;
    public $idEntidade;
    public $tipo;
    public $precoMinTotal;
    public $precoMaxTotal;
    public $nTotalAdjudicacoesMin;
    public $nTotalAdjudicacoesMax;
    public $ano;

    public function __construct($params) {
        parent::__construct($params);

        $this->historyTable = 'entidade_pesquisa_h';
        $this->historyColumns = array('sk', 'nomeStartWith', 'idEntidade', 'tipo', 'precoTotalMin', 'precoTotalMax', 'nTotalAdjudicacoesMin', 'nTotalAdjudicacoesMax', 'ano');
    }

    protected function parsingParams() {
        parent::parsingParams();

        $this->sk = $this->getParam('sk', CLEAN_STRING, '');
        $this->nomeStartWith = $this->getParam('nomeStartWith', CLEAN_STRING, '');
        $this->idEntidade = $this->getParam('idEntidade', CLEAN_STRING, '');
        $this->tipo = $this->getParam('tipo', CLEAN_STRING, '');
        $this->precoTotalMin = $this->getParam('precoTotalMin', CLEAN_FLOAT, '');
        $this->precoTotalMax = $this->getParam('precoTotalMax', CLEAN_FLOAT, '');
        $this->nTotalAdjudicacoesMin = $this->getParam('nTotalAdjudicacoesMin', CLEAN_INT, '');
        $this->nTotalAdjudicacoesMax = $this->getParam('nTotalAdjudicacoesMax', CLEAN_INT, '');
        $this->ano = $this->getParam('ano', CLEAN_INT, '');

        if(empty($this->ano))
                $this->ano = date('Y');
    }

    protected function setQuerys() {
        $where = ' WHERE 1 ';
        $this->queryParams = array();

        $from = " FROM entidade e left join entidade_estatisticas ee on (e.Id = ee.IdEntidade AND ee.Ano = ?) left join entidade_estatisticas ee1 on (e.Id = ee1.IdEntidade AND ee1.Ano = -1) left join entidade_estatisticas ee2 on (e.Id = ee2.IdEntidade AND ee2.Ano = ?) ";
        $this->queryParams[] = $this->ano;
        $this->queryParams[] = $this->ano-1;
        
        if (!empty($this->sk)) {
            $sk_aux = '%' . str_replace(' ', '%', $this->sk) . '%';
            $where .= " AND (e.Nif like ? OR e.Nome like ?) ";
            $this->queryParams[] = $sk_aux;
            $this->queryParams[] = $sk_aux;
        }

        if (!empty($this->nomeStartWith)) {
            $where .= " AND Nome like ? ";
            $this->queryParams[] = $this->nomeStartWith . '%';
        }

        if (!empty($this->tipo)) {
            if ($this->tipo == 'C') {
                $where .= " AND EXISTS (SELECT * FROM ad_contrato_entidade ce WHERE ce.IdEntidade = e.Id AND ce.Tipo = 'C' ) ";
            } else if ($this->tipo == 'A') {
                $where .= " AND EXISTS (SELECT * FROM ad_contrato_entidade ce WHERE ce.IdEntidade = e.Id AND ce.Tipo = 'A' ) ";
            }
        }
        if(!empty($this->nTotalAdjudicacoesMin)){
            $where .= " AND ee1.NumRegistosAdAdjudicadas+ee1.NumRegistosAdContratadas >= ? ";
            $this->queryParams[] = $this->nTotalAdjudicacoesMin;
        }
        if(!empty($this->nTotalAdjudicacoesMax)){
            $where .= " AND ee1.NumRegistosAdAdjudicadas+ee1.NumRegistosAdContratadas <= ? ";
            $this->queryParams[] = $this->nTotalAdjudicacoesMax;
        }
        if(!empty($this->precoTotalMin)){
            $where .= " AND ee1.ValorTotalAdAdjudicadas+ee1.ValorTotalAdContratadas >= ? ";
            $this->queryParams[] = $this->precoTotalMin;
        }
        if(!empty($this->precoTotalMax)){
            $where .= " AND ee1.ValorTotalAdAdjudicadas+ee1.ValorTotalAdContratadas <= ? ";
            $this->queryParams[] = $this->precoTotalMax;
        }

        $this->queryCount = "Select count(*) as count " . $from . $where;

        $this->query = "SELECT HEX(e.Id) as Id, e.Nif, e.Nome, e.DataCriacao, ee.NumRegistosAdAdjudicadas+ee.NumRegistosAdContratadas as AdNumTotalAno, ee.ValorTotalAdAdjudicadas+ee.ValorTotalAdContratadas as AdValorTotalAno, ee1.NumRegistosAdAdjudicadas+ee1.NumRegistosAdContratadas as AdNumTotal, ee1.ValorTotalAdAdjudicadas+ee1.ValorTotalAdContratadas as AdValorTotal, ee2.NumRegistosAdAdjudicadas+ee2.NumRegistosAdContratadas as AdNumTotal2Ano, ee2.ValorTotalAdAdjudicadas+ee2.ValorTotalAdContratadas as AdValorTotal2Ano ";
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
            '<a href="entidades/View?ID=' . $row['Id'] . '">'.$row['Nome'].'</a>',
            $row['DataCriacao'],
            $row['AdNumTotal2Ano'],
            $row['AdValorTotal2Ano'],
            $row['AdNumTotalAno'],
            $row['AdValorTotalAno'],
            $row['AdNumTotal'],
            $row['AdValorTotal']
        );

        return $result;
    }

}

?>
