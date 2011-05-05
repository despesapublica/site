<?php

/**
 * @version
 * @author Joao Martins <jfmartins@portugalmail.pt>
 * @copyright	Copyright (C) 2011 Joao Martins
 * @license		GNU General Public License version 2 or later
 */
//$dirname = dirname(__FILE__);

//require_once ($dirname . '/../JPridRequest.php');


class AjustesDirectos_EmpNovas extends jqgridRequest {

    public $sk;
    public $dataMin;
    public $dataMax;
    public $precoMin;
    public $precoMax;
    public $difDatas;

    public $urlEntidade = 'entidades/View?ID=';
    
    public function __construct($params) {
        parent::__construct($params);
    }

    protected function parsingParams() {
        parent::parsingParams();

        $this->sk = $this->getParam('sk', CLEAN_STRING, '');
        $this->dataMin = $this->getParam('dataMin', CLEAN_NONE, '');
        $this->dataMax = $this->getParam('dataMax', CLEAN_NONE, '');
        $this->precoMin = $this->getParam('precoMin', CLEAN_FLOAT, '');
        $this->precoMax = $this->getParam('precoMax', CLEAN_FLOAT, '');
        $this->difDatas = $this->getParam('difDatas', CLEAN_INT, '');

        if(empty($this->difDatas))
            $this->difDatas = 1;
    }

    protected function setQuerys() {
        $where = ' WHERE 1 ';
        $this->queryParams = array();

        $this->autoAddOrderToQuery = false;
        
        $from = '';
        
        if(!empty($this->sk)){
            //$sk_aux = '%' . str_replace(' ', '%', $this->sk) . '%';

            $where .= " AND (MATCH (c.Descricao, c.LocalExecucao, c.EntidadesAdjudicantes, c.EntidadesContratadas) AGAINST (? IN BOOLEAN MODE)) >= 1 ";

//            $where .= " AND (c.Descricao like ? OR ea.Nif like ? OR ea.Nome like ? OR e.Nif like ? OR e.Nome like ?)";
            $this->queryParams[] = $this->sk;
            /*$this->queryParams[] = $sk_aux;
            $this->queryParams[] = $sk_aux;
            $this->queryParams[] = $sk_aux;
            $this->queryParams[] = $sk_aux;*/
        }
        if(!empty($this->dataMin)){
            $where .= " AND dataPublicacao >= STR_TO_DATE(?, '%d/%m/%Y')";
            $this->queryParams[] = $this->dataMin;
        }
        if(!empty($this->dataMax)){
            $where .= " AND dataPublicacao <=  STR_TO_DATE(?, '%d/%m/%Y')";
            $this->queryParams[] = $this->dataMax;
        }
        if(!empty($this->precoMin)){
            $where .= " AND Preco >= ?";
            $this->queryParams[] = $this->precoMin;
        }
        if(!empty($this->precoMax)){
            $where .= " AND Preco <= ?";
            $this->queryParams[] = $this->precoMax;
        }
        
        if (!empty($this->difDatas)) {
            $where .= "AND (diasDiffContrato < ? OR diasDiffPublicacao < ?)";//" AND (DATEDIFF( c.DataPublicacao, e.DataCriacao ) < ? OR DATEDIFF( c.DataContrato, e.DataCriacao ) < ?)";
            $this->queryParams[] = $this->difDatas*30.43685;//Numero de dias de 1 mes
            $this->queryParams[] = $this->difDatas*30.43685;
        }

        
        $from = " FROM ad_contrato c INNER JOIN ad_contrato_entidade ce ON ( c.id = ce.IdContrato AND ce.Tipo = 'C' ) INNER JOIN `entidade` e ON ce.IdEntidade = e.Id  left join entidade ea on c.IdEntidadeAdjudicante = ea.Id ";
       
        $this->queryCount = "Select count(*) as count ". $from . $where;

        $this->query = "SELECT HEX(c.Id) as Id, c.Url, c.Descricao, c.DataPublicacao, c.DataContrato, c.Preco, e.DataCriacao, HEX(e.Id) as ec_Id, e.Nif as ec_Nif, e.Nome as ec_Nome, diasDiffPublicacao as DiasP, diasDiffContrato as DiasC, HEX(ea.Id) as ea_Id, ea.Nif as ea_Nif, ea.Nome as ea_Nome, NumAdjudicantes ";
        $this->query .= $from . $where;
        $this->query .= ' ORDER BY '.$this->sortName.' '.$this->sortDir. ',  DataU desc  ';

    }
    
    protected function processRow($row, $indice){
         $result = array();

         $result['id'] = $row['Id'];
          $result['cell'] = array(
            '<a href="adjudicacoes-directas/view/?ID=' . $row['Id'] . '">' . $row['Descricao'] . '</a>',
            $row['Preco'],
            $row['DataPublicacao'],
            $row['DataContrato'],
            $row['DiasP'],
            $row['DiasC'],
            $row['DataCriacao'],
            '<a href="' . $this->urlEntidade . $row['ec_Id'] . '">' . $row['ec_Nome'] . '</a>',
            ($row['NumAdjudicantes'] == 1) ?
                    '<a href="' . $this->urlEntidade . $row['ea_Id'] . '">' . $row['ea_Nome'] . '</a>' : 'V&aacute;rias entidades (' . $row['NumAdjudicantes'] . ')'
        );
          
         return $result;
    }

}

?>
