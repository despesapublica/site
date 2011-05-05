<?php

/**
 * Defines the HomePage page type
 */
class AjusteDirectoDetailPage extends Page {

    static $db = array(
    );
    static $has_one = array(
    );

}

class AjusteDirectoDetailPage_Controller extends Page_Controller {
    static $allowed_actions = array('');

    var $ID_ad;
    var $_detail;

     public function init() {
        parent::init();

        if(!empty($_REQUEST["ID"])){
            $this->ID_ad = $_REQUEST["ID"];
        }
        else if(!empty($this->urlParams['ID'])){
            $this->ID_ad = $this->urlParams['ID'];
        }

        if(empty($this->ID_ad))
            $this->dataRecord->Title = 'Detalhe da Adjudicação: Falta o ID';
        else{
            $this->dataRecord->Title = "Detalhe do Ajuste Directo";

            $detalhe = $this->detail();
            $this->MetaTitle = Convert::raw2att($detalhe->Preco->Nice() .' - '. $detalhe->Nome);
            $this->MetaDescription = Convert::raw2att($detalhe->Descricao);
        }
    }

    function Link($action = null) {
        $result = parent::Link($action);
        if(empty($action)){
            $result .= '?ID='.$this->ID_ad;
        }

        return $result;
    }

    public function detail(){
        if(empty($this->ID_ad)){
            return array('Title'=>'Detalhe do Contrato: Falta o Id');
        }

        if(!empty($this->_detail))
            return $this->_detail;
        
        $sql = "SELECT c.*, HEX(c.Id) as UId, e.Nome FROM ad_contrato c left join entidade e on c.IdEntidadeAdjudicante = e.Id WHERE c.Id = UNHEX(?)";
        
        $sqlP = prepare_query($sql, 0, 1, array($this->ID_ad));
        $dbresult = DB::query($sqlP);

        $row = $dbresult->record();
        $this->_detail = convertArrayToViewableData($row);

        $this->saveToHistory();
        
        return $this->_detail; 
    }

    public function adjudicantes()
    {
        if(empty($this->ID_ad)){
            return array('Title'=>'Detalhe do Contrato: Falta o Id');
        }

        $sql = "SELECT e.*, HEX(e.Id) as UId FROM ad_contrato_entidade ce inner join entidade e on ce.IdEntidade = e.Id WHERE ce.IdContrato = UNHEX(?) AND Tipo = ?";

        $sqlP = prepare_query($sql, -1, -1, array($this->ID_ad, 'A'));
        $dbresult = DB::query($sqlP);

        $row = $dbresult->record();
        $result = new DataObjectSet();

        foreach($dbresult as $row) {
            $result->push(convertArrayToViewableData($row));
        }
        return $result;
    }

    public function contratados()
    {
        if(empty($this->ID_ad)){
            return array('Title'=>'Detalhe do Contrato: Falta o Id');
        }

        $sql = "SELECT e.*, HEX(e.Id) as UId FROM ad_contrato_entidade ce inner join entidade e on ce.IdEntidade = e.Id WHERE ce.IdContrato = UNHEX(?) AND Tipo = ?";

        $sqlP = prepare_query($sql, -1, -1, array($this->ID_ad, 'C'));
        $dbresult = DB::query($sqlP);

        $row = $dbresult->record();
        $result = new DataObjectSet();

        foreach($dbresult as $row) {
            $result->push(convertArrayToViewableData($row));
        }

        return $result;
    }

    public function formatLocaisExecucao()
    {
        $detalhe = $this->detail();
        return str_replace('||', '<br/>', $detalhe->LocalExecucao);
    }

    function saveToHistory(){
        $query = "INSERT ad_contrato_view_h (Id, Data, Ip, HttpUserAgent)";
        $query .= " VALUES (UNHEX(?), STR_TO_DATE(?, '%d/%m/%Y %H:%i:%s'), ?, ?)";

        $params = array();
        $params['Id'] = $this->ID_ad;
        $params['Data'] = date('d/m/Y H:i:s');
        $params['Ip'] = '';//$_SERVER['REMOTE_ADDR'];
        $params['HttpUserAgent'] = $_SERVER['HTTP_USER_AGENT'];

        $pQuery = prepare_query($query, -1, -1, $params);
        DB::query($pQuery);
    }
}