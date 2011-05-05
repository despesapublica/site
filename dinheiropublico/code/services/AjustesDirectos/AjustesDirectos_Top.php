<?php

/**
 * @version
 * @author Joao Martins <jfmartins@portugalmail.pt>
 * @copyright	Copyright (C) 2011 Joao Martins
 * @license		GNU General Public License version 2 or later
 */
//$dirname = dirname(__FILE__);

//require_once ($dirname . '/../JPridRequest.php');


class AjustesDirectos_Top extends jqgridRequest {
   public $urlEntidade = 'entidades/View?ID=';
    
    public function __construct($params) {
        parent::__construct($params);
    }

    protected function parsingParams() {
        parent::parsingParams();
    }

    protected function setQuerys() {
        $where = ' WHERE 1 ';
        $this->queryParams = array();

        $this->autoAddOrderToQuery = false;

        $from = " FROM ad_contrato c left join entidade ea on c.IdEntidadeAdjudicante = ea.Id left join entidade ec on c.IdEntidadeContratada = ec.Id ";
        
        $this->queryCount = "";
        $this->numTotalRecords = $this->limit;

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
