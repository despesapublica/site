<?php

/**
 * Defines the HomePage page type
 */
class EntidadeDetailPage extends Page {

    static $db = array(
    );
    static $has_one = array(
    );

}

class EntidadeDetailPage_Controller extends Page_Controller {
    static $allowed_actions = array('');

    var $ID_entidade;
    var $_detail;

     public function init() {
        parent::init();

        if(!empty($_REQUEST["ID"])){
            $this->ID_entidade = $_REQUEST["ID"];
        }
        else if(!empty($this->urlParams['ID'])){
            $this->ID_entidade = $this->urlParams['ID'];
        }

        if(empty($this->ID_entidade))
            $this->dataRecord->Title = 'Detalhe da Entidade: Falta o ID';
        else{
            $detalhe = $this->detail();
            
//           $this->dataRecord->Title = $detalhe->Nome;
            $this->dataRecord->Title = $detalhe->RAW_val("Nome");	   
            $this->MetaTitle = $detalhe->RAW_val("Nome");
			//$this->MetaTitle = Convert::raw2att($detalhe->Nome);
            //$this->MetaDescription = Convert::raw2att($detalhe->Descricao);
       }
    }

    function Link($action = null) {
        $result = parent::Link($action);
        if(empty($action)){
            $result .= '?ID='.$this->ID_entidade;
        }

        return $result;
    }

    function graphLinkRequest() {
        $graphLink = 'http://toshiba/opendata/site3/AdjudicacoesDirectasService/gvTotaisDia/pesquisa/?type=m';
         return $graphLink;
    }

    function gridADLinkRequest() {
        $gridADLinkRequest = 'AjustesDirectosService/pesquisa/?idEntidade='.$this->ID_entidade;
         return $gridADLinkRequest;
    }

    function gridEntidadesContratadasLinkRequest() {
        $link = 'EntidadesService/entidadesRelacionadas/?tipo=C&idEntidade='.$this->ID_entidade;
         return $link;
    }

    function gridEntidadesAdjudicantesLinkRequest() {
        $link = 'EntidadesService/entidadesRelacionadas/?tipo=A&idEntidade='.$this->ID_entidade;
         return $link;
    }

    public function detail(){
        if(empty($this->ID_entidade)){
            return array('Title'=>'Detalhe do Entidade: Falta o Id');
        }

        if(!empty($this->_detail))
            return $this->_detail;
        
        $sql = "SELECT e.*, HEX(e.Id) as UId FROM entidade e WHERE e.Id = UNHEX(?)";
        
        $sqlP = prepare_query($sql, 0, 1, array($this->ID_entidade));
        $dbresult = DB::query($sqlP);

        $row = $dbresult->record();
        $this->_detail = convertArrayToViewableData($row);

        $this->saveToHistory();

        return $this->_detail;
    }

    function estatisticas(){
        if(empty($this->ID_entidade)){
            return array('Title'=>'Detalhe do Entidade: Falta o Id');
        }

        if(!empty($this->_estatisticas))
            return $this->_estatisticas;

        $sql = "SELECT * FROM entidade_estatisticas WHERE IdEntidade = UNHEX(?)";

        $sqlP = prepare_query($sql, -1, -1, array($this->ID_entidade));
        $dbresult = DB::query($sqlP);

        $row = $dbresult->record();
        $this->_estatisticas = new DataObjectSet();

        foreach($dbresult as $row) {
            $this->_estatisticas->push(convertArrayToViewableData($row));
        }

        return $this->_estatisticas;
    }

    function totalAD()
    {
        $this->estatisticas();
        foreach($this->_estatisticas as $value)
        {
            if($value->Ano == -1)
                return $value;
        }
    }

    function numRegistosAdAdjudicadas(){
        return $this->totalAD()->NumRegistosAdAdjudicadas;
    }
    function valorTotalAdAdjudicadas(){
        return $this->totalAD()->ValorTotalAdAdjudicadas;
    }

    function numRegistosAdContratadas(){
        return $this->totalAD()->NumRegistosAdContratadas;
    }
    function valorTotalAdContratadas(){
        return $this->totalAD()->ValorTotalAdContratadas;
    }

    function saveToHistory(){
        $query = "INSERT entidade_view_h (Id, Data, Ip, HttpUserAgent)";
        $query .= " VALUES (UNHEX(?), STR_TO_DATE(?, '%d/%m/%Y %H:%i:%s'), ?, ?)";

        $params = array();
        $params['Id'] = $this->ID_entidade;
        $params['Data'] = date('d/m/Y H:i:s');
        $params['Ip'] = '';//$_SERVER['REMOTE_ADDR'];
        $params['HttpUserAgent'] = $_SERVER['HTTP_USER_AGENT'];

        $pQuery = prepare_query($query, -1, -1, $params);
        DB::query($pQuery);
    }
}