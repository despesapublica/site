<?php

/**
 * @version
 * @author Joao Martins <jfmartins@portugalmail.pt>
 * @copyright	Copyright (C) 2011 Joao Martins
 * @license		GNU General Public License version 2 or later
 */
//$dirname = dirname(__FILE__);

//require_once ($dirname . '/../JPridRequest.php');


class AjustesDirectos_Pesquisa extends jqgridRequest {

    public $sk;
    public $dataContratoMin;
    public $dataContratoMax;
    public $dataPublicacaoMin;
    public $dataPublicacaoMax;
    public $precoMin;
    public $precoMax;
    public $idEntidade;
    public $last;
    public $idEntidadeRelacionada;
    public $tipoRelacao;
    public $urlEntidade = 'entidades/View?ID=';
    
    public function __construct($params) {
        parent::__construct($params);

        $this->historyTable = 'ad_contrato_pesquisa_h';
        $this->historyColumns = array('sk', 'dataContratoMin', 'dataContratoMax', 'dataPublicacaoMin', 'dataPublicacaoMax', 'precoMin', 'precoMax', 'idEntidade', 'idEntidadeRelacionada', 'tipoRelacao');
    }

    protected function parsingParams() {
        parent::parsingParams();

        $this->sk = $this->getParam('sk', CLEAN_STRING, '');
        $this->dataContratoMin = $this->getParam('dataContratoMin', CLEAN_NONE, '');
        $this->dataContratoMax = $this->getParam('dataContratoMax', CLEAN_NONE, '');
        $this->dataPublicacaoMin = $this->getParam('dataPublicacaoMin', CLEAN_NONE, '');
        $this->dataPublicacaoMax = $this->getParam('dataPublicacaoMax', CLEAN_NONE, '');
        $this->precoMin = $this->getParam('precoMin', CLEAN_FLOAT, '');
        $this->precoMax = $this->getParam('precoMax', CLEAN_FLOAT, '');
        $this->idEntidade = $this->getParam('idEntidade', CLEAN_STRING, '');
        $this->idEntidadeRelacionada = $this->getParam('idEntidadeRelacionada', CLEAN_STRING, '');
        $this->tipoRelacao = $this->getParam('tipoRelacao', CLEAN_STRING, '');

        $this->last = $this->getParam('last', CLEAN_INT, '');
        if(empty($this->dataPublicacaoMin) && !empty($this->last))
            $this->dataPublicacaoMin = date('d/m/Y', time()-345600);//4 dias
    }

    protected function setQuerys() {
        $where = ' WHERE 1 ';
        $this->queryParams = array();

        $this->autoAddOrderToQuery = false;

        $from = '';
        
        if(!empty($this->sk)){
            $sk_aux = '%'.str_replace(' ', '%', $this->sk).'%';
            $where .= " AND (MATCH (c.Descricao, c.LocalExecucao, c.EntidadesAdjudicantes, c.EntidadesContratadas) AGAINST (? IN BOOLEAN MODE)) >= 1 ";
            //$where .= " OR c.LocalExecucao like ? OR ea.Nif like ? OR ea.Nome like ? OR ec.Nif like ? OR ec.Nome like ?)";
            //$where .= " AND (c.Descricao like ? OR c.LocalExecucao like ? OR ea.Nif like ? OR ea.Nome like ? OR ec.Nif like ? OR ec.Nome like ?)";
            $this->queryParams[] = $this->sk;
            /*$this->queryParams[] = $sk_aux;
            $this->queryParams[] = $sk_aux;
            $this->queryParams[] = $sk_aux;
            $this->queryParams[] = $sk_aux;
            $this->queryParams[] = $sk_aux;*/
        }
        if(!empty($this->dataContratoMin)){
            $where .= " AND dataContrato >= STR_TO_DATE(?, '%d/%m/%Y')";
            $this->queryParams[] = $this->dataContratoMin;
        }
        if(!empty($this->dataContratoMax)){
            $where .= " AND dataContrato <=  STR_TO_DATE(?, '%d/%m/%Y')";
            $this->queryParams[] = $this->dataContratoMax;
        }
        if(!empty($this->dataPublicacaoMin)){
            $where .= " AND dataPublicacao >= STR_TO_DATE(?, '%d/%m/%Y')";
            $this->queryParams[] = $this->dataPublicacaoMin;
        }
        if(!empty($this->dataPublicacaoMax)){
            $where .= " AND dataPublicacao <=  STR_TO_DATE(?, '%d/%m/%Y')";
            $this->queryParams[] = $this->dataPublicacaoMax;
        }
        if(!empty($this->precoMin)){
            $where .= " AND Preco >= ?";
            $this->queryParams[] = $this->precoMin;
        }
        if(!empty($this->precoMax)){
            $where .= " AND Preco <= ?";
            $this->queryParams[] = $this->precoMax;
        }

        if(!empty($this->idEntidadeRelacionada))
        {
            $where .= " AND EXISTS (SELECT * FROM ad_contrato_entidade cer where cer.IdContrato = c.Id and cer.IdEntidade = UNHEX(?)";
            if (!empty($this->tipoRelacao)) {
                if ($this->tipoRelacao == 'C') {
                    $where .= " AND cer.Tipo = 'A' ";
                } else if ($this->tipoRelacao == 'A') {
                    $where .= " AND cer.Tipo = 'C' ";
                }
            }
            $where .= " )";
            $this->queryParams[] = $this->idEntidadeRelacionada;
        }
        
        if (empty($this->idEntidade)) {
            $from = " FROM ad_contrato c left join entidade ea on c.IdEntidadeAdjudicante = ea.Id left join entidade ec on c.IdEntidadeContratada = ec.Id ";
        } else {
            $from = " FROM ad_contrato c inner join ad_contrato_entidade ce on c.id = ce.IdContrato AND ce.IdEntidade = UNHEX(?) left join entidade ea on c.IdEntidadeAdjudicante = ea.Id left join entidade ec on c.IdEntidadeContratada = ec.Id";
            $this->queryParams[] = $this->idEntidade;
        }
        
        $this->queryCount = "Select count(*) as count ". $from . $where;

        $this->query = "SELECT HEX(c.Id) as Id, c.Url, c.Descricao, c.DataPublicacao, c.DataContrato, c.Preco, c.NumAdjudicantes, c.NumContratados, HEX(ea.Id) as ea_Id, ea.Nif as ea_Nif, ea.Nome as ea_Nome, HEX(ec.Id) as ec_Id, ec.Nif as ec_Nif, ec.Nome as ec_Nome, ec.DataCriacao as ec_DataCriacao ";
        $this->query .= $from . $where;
        $this->query .= ' ORDER BY '.$this->sortName.' '.$this->sortDir. ',  DataU desc  ';
    }
    
    protected function processRow($row, $indice){
         $result = array();

         $cssClassContratado = '';
         if(!empty($row['ec_DataCriacao'])){
             $t_dataCriacao = strtotime($row['ec_DataCriacao']);
             $t_dataPublicacao = strtotime($row['DataPublicacao']);
             $t_dataContrato = strtotime($row['DataContrato']);
             if(($t_dataContrato - $t_dataCriacao) <= 31556926 || ($t_dataPublicacao - $t_dataCriacao) <= 31556926)
             {
                 $cssClassContratado = 'class="empresaRecente"';
             }
         }

         $result['id'] = $row['Id'];
         $result['cell'] = array(
                '<a href="adjudicacoes-directas/view/?ID=' . $row['Id'] . '">' . $row['Descricao'] . '</a>',
                $row['Preco'],
                $row['DataPublicacao'],
                $row['DataContrato']
                    /* ,
                      $row['DiasP'],
                      $row['DiasC'],
                      $row['DataCriacao'],
                      '<a href="http://publicacoes.mj.pt/pt/Pesquisa.asp?iNIPC='.$row['Nif'].'&sFirma=&dfDistrito=&dfConcelho=&dInicial=&dFinal=&iTipo=0&sCAPTCHA=&pesquisar=Pesquisar&dfConcelhoDesc=" target="_blank">'.$row['Nif'].'</a>',
                      $row['Nome'] */                    );

         if(empty($this->idEntidade)){
            if($row['NumAdjudicantes'] == 1)
                $result['cell'][4] = '<a href="'. $this->urlEntidade . $row['ea_Id'] . '">' . $row['ea_Nome'] . '</a>';
            else
                $result['cell'][4] = 'V&aacute;rias entidades (' . $row['NumAdjudicantes'] . ')';

            if($row['NumContratados'] == 1)
                $result['cell'][5] = '<a href="'. $this->urlEntidade . $row['ec_Id'] . '" '.$cssClassContratado.'>' . $row['ec_Nome'] . '</a>';
            else
                $result['cell'][5] = '<span>V&aacute;rias entidades (' . $row['NumContratados'] . ')</span>';
         }
         else{
             $str_entidade = '';
             if($row['NumAdjudicantes'] > 1){
                 $str_entidade .= '<div class="Adjudicantes">V&aacute;rias entidades (' . $row['NumAdjudicantes'] . ')</div>';
             }
             else if($row['ea_Id'] != $this->idEntidade){
                  $str_entidade .= '<div class="Adjudicantes"><a href="'. $this->urlEntidade . $row['ea_Id'] . '">' . $row['ea_Nome'] . '</a></div>';
             }
             if($row['NumContratados'] > 1){
                 $str_entidade .= '<div class="Contratados">V&aacute;rias entidades (' . $row['NumAdjudicantes'] . ')</div>';
             }
             else if($row['ec_Id'] != $this->idEntidade){
                  $str_entidade .= '<div class="Contratados"><span '.$cssClassContratado.'><a href="'. $this->urlEntidade . $row['ec_Id'] . '">' . $row['ec_Nome'] . '</span></a></div>';
             }
             $result['cell'][4] = $str_entidade;
         }

         return $result;
    }

}

?>
