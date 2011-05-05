<?php

/**
 * @version
 * @author Joao Martins <jfmartins@portugalmail.pt>
 * @copyright	Copyright (C) 2011 Joao Martins
 * @license		GNU General Public License version 2 or later
 */
class AjustesDirectos_GVTotaisMesAno extends GVizRequest {

    public $arrMes;
    public $type;
    public $idEntidade;
    public $arr_mes = array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez');
    
    public function __construct($params) {
        parent::__construct($params);

        $this->arrMes = array();
    }

    protected function parsingParams() {
        parent::parsingParams();

        $this->type = $this->getParam('type', CLEAN_STRING, '');
        $this->idEntidade = $this->getParam('idEntidade', CLEAN_STRING, '');
    }

    protected function setQuerys() {
        $colunas = "Year(DataContrato) as Ano, MONTH(DataContrato) as Mes";
        $where = " WHERE dataContrato is not null AND datacontrato != '0000-00-00'";
        $this->queryParams = array();

        $from = '';

        switch($this->type)
        {
            case 'v':
                $colunas .= ', sum(Preco) as Total';
                break;
            default:
                $colunas .= ', count(*) as Total';
        }

        if (!empty($this->idEntidade)) {
            $from = " FROM ad_contrato c inner join ad_contrato_entidade ce on c.id = ce.IdContrato AND ce.IdEntidade = UNHEX(?) ";
            $this->queryParams[] = $this->idEntidade;
        }
        else
        {
            $from = " FROM ad_contrato ";
        }

        $this->query = "SELECT " . $colunas . $from . $where .
            "GROUP BY Year(DataContrato), MONTH(DataContrato) Order by MONTH(DataContrato), Year(DataContrato)";
    }

    protected function setColumns(){
        $this->gvizTable->addColumn('Mes', 'Mês', 'string');
        //$this->gvizTable->addColumn('Ano0', 'Sem data contrato', 'number');
        $this->gvizTable->addColumn('Ano2008', 'Ano 2008', 'number');
        $this->gvizTable->addColumn('Ano2009', 'Ano 2009', 'number');
        $this->gvizTable->addColumn('Ano2010', 'Ano 2010', 'number');
        $this->gvizTable->addColumn('Ano2011', 'Ano 2011', 'number');
    }

    protected function processRow($row, $indice) {
        if(!isset($this->arrMes['M'.$row['Mes']])){
            $rowId = $this->gvizTable->newRow();
            $this->arrMes['M'.$row['Mes']] = $rowId;

            $this->gvizTable->addCell($rowId, 'Mes', $this->arr_mes[$row['Mes']-1]);
        }
        else{
            $rowId = $this->arrMes['M'.$row['Mes']];
        }

        if($this->type == 'v')
        {
            $field = DBField::create('Money', array('Currency' => 'EUR', 'Amount' => str_replace(',', '.', $row['Total'])));
            $this->gvizTable->addCell($rowId, 'Ano'.$row['Ano'], $row['Total'], utf8_decode($field->NiceWithShortname()));//str_replace('?', utf8_decode('€'), utf8_decode($field->Nice()))
        }
        else {
            $this->gvizTable->addCell($rowId, 'Ano'.$row['Ano'], $row['Total']);
        }
    }

}