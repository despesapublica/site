<?php

/**
 * @version
 * @author Joao Martins <jfmartins@portugalmail.pt>
 * @copyright	Copyright (C) 2011 Joao Martins
 * @license		GNU General Public License version 2 or later
 */

class AjustesDirectos_GVTotaisDia extends GVizRequest {

    public $onlyNumTotal;
    public $idEntidade;

    public function __construct($params) {
        parent::__construct($params);
    }

    protected function parsingParams() {
        parent::parsingParams();

        $this->onlyNumTotal = $this->getParam('onlyNumTotal', CLEAN_STRING, '');
        $this->idEntidade = $this->getParam('idEntidade', CLEAN_STRING, '');
    }

    protected function setQuerys() {
        $colunas = "DataContrato as Data, ";
        if(empty($this->onlyNumTotal))
                $colunas .= "sum(Preco) as ValorTotal, ";

        $colunas .= "count(*) as NumTotal";

        if (!empty($this->idEntidade)) {
            $from = " FROM ad_contrato c inner join ad_contrato_entidade ce on c.id = ce.IdContrato AND ce.IdEntidade = UNHEX(?) ";
            $this->queryParams[] = $this->idEntidade;
        }
        else
        {
             $from = " FROM ad_contrato ";
        }
        
        $this->query = "SELECT " . $colunas . $from . "
            WHERE dataContrato is not null AND datacontrato != '0000-00-00'
            GROUP BY DataContrato";
    }

    protected function setColumns(){
        $this->gvizTable->addColumn('Data', 'Data', 'date');
        if(empty($this->onlyNumTotal))
            $this->gvizTable->addColumn('ValorTotal', 'Valor Total', 'number');
        $this->gvizTable->addColumn('NumTotal', 'Num. Total', 'number');
    }

    protected function processRow($row, $indice) {
        $rowId = $this->gvizTable->newRow();
        $this->gvizTable->addCell($rowId, 'Data', $row['Data']);
        if(empty($this->onlyNumTotal)){
            $field = DBField::create('Money', array('Currency' => 'EUR', 'Amount' => str_replace(',', '.', $row['ValorTotal'])));
            $this->gvizTable->addCell($rowId, 'ValorTotal', $row['ValorTotal'], utf8_decode($field->NiceWithShortname()));//str_replace('?', utf8_decode('€'), utf8_decode($field->Nice()))
        }
        $this->gvizTable->addCell($rowId, 'NumTotal', $row['NumTotal']);
    }

}