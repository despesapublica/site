<?php

/**
 * Defines the HomePage page type
 */
class EntidadesPage extends Page {

    static $db = array(
    );
    static $has_one = array(
    );

}

class EntidadesPage_Controller extends Page_Controller {

    static $allowed_actions = array('');
    var $searchParamsKeys = array(
        'sk',
        'precoTotalMin',
        'precoTotalMax',
        'nTotalAdjudicacoesMin',
        'nTotalAdjudicacoesMax',
        'nomeStartWith'
    );
    var $_searchParams;
    var $tipoEntidades = array(
        '' => 'Todos',
        'A' => 'Adjudicantes',
        'C' => 'Contratadas');
    var $ano;

     public function init() {
        parent::init();

        $this->ano = date('Y');
    }

    function gridLinkRequest() {
        $gridLink = 'EntidadesService/pesquisa/?ano='.$this->ano;

        $params = $this->searchParams();


        //Controller::join_links($this->Link(), $rssRelativeLink);

        foreach ($this->searchParamsKeys as $k) {
            if ($params->hasField($k) && !empty($params->$k)) {
                $value = $params->$k;
                switch (get_class($value)) {
                    case 'Money':
                        $value = $value->getAmount();
                        break;
                    case 'Date':
                        $value = $value->Nice();
                        break;
                }
                if (!empty($value))
                    $gridLink .= "&$k=" . urlencode($value);
            }
        }

        return $gridLink;
    }

    public function searchParams() {
        $values = array();
        if (!empty($this->_searchParams))
            return $this->_searchParams;

        foreach ($this->searchParamsKeys as $k) {
            if (isset($_REQUEST[$k]))
                $values[$k] = $_REQUEST[$k];
            else
                $values[$k] = null;
        }

        if(empty($values['nTotalAdjudicacoesMin']))
            $values['nTotalAdjudicacoesMin'] = 0;
        if(empty($values['nTotalAdjudicacoesMax']))
            $values['nTotalAdjudicacoesMax'] = $this->getMaxTotalAdjudicacoes();

        $this->_searchParams = convertArrayToViewableData($values);

        //var_dump($this->_searchParams);
        return $this->_searchParams;
    }

    public function getMaxTotalAdjudicacoes(){
         if(!empty($this->MaxTotalAdjudicacoes))
                return $this->MaxTotalAdjudicacoes;

        $this->MaxTotalAdjudicacoes = intval(DB::query("SELECT MAX(NumRegistosAdAdjudicadas+NumRegistosAdContratadas) from entidade_estatisticas where ano = -1")->value());

        return $this->MaxTotalAdjudicacoes;
    }

    public function getMaxPrecoTotalAdjudicacoes(){
        if(!empty($this->PrecoMaxTotalAdjudicacoes))
                return $this->PrecoMaxTotalAdjudicacoes;

        $valor = DB::query("SELECT MAX(ValorTotalAdAdjudicadas+ValorTotalAdContratadas) from entidade_estatisticas where ano = -1")->value();
        $this->PrecoMaxTotalAdjudicacoes = DBField::create('Money', array('Currency' => 'EUR', 'Amount' => $valor));

        return $this->PrecoMaxTotalAdjudicacoes;
    }

    public function getTipoEntidadesSelect(){
        $params = $this->searchParams();
        
        $dropdown = new DropdownField('tipoEntidade', 'tipoEntidade', $this->tipoEntidades, $params->tipoEntidade);
        $dropdown->setEmptyString(' ');

        return $dropdown;
    }

    public function getAnoEstatistica(){
        return $this->ano;
    }

    public function getAno2Estatistica(){
        return $this->ano-1;
    }
}