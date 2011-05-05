<?php

/**
 * Defines the HomePage page type
 */
class AjustesDirectosPage extends Page {

    static $db = array(
    );
    static $has_one = array(
    );

}

class AjustesDirectosPage_Controller extends Page_Controller {
    static $allowed_actions = array('');

    var $searchParamsKeys = array(
            'sk',
            'dataContratoMin',
            'dataContratoMax',
            'dataPublicacaoMin',
            'dataPublicacaoMax',
            'precoMin',
            'precoMax'
        );
    var $_searchParams;

    function gridLinkRequest() {
        $gridLink = 'AjustesDirectosService/pesquisa/?';

        $params = $this->searchParams();
       

        //Controller::join_links($this->Link(), $rssRelativeLink);

        foreach ($this->searchParamsKeys as $k){
            if ($params->hasField($k) && !empty($params->$k))
            {
                $value = $params->$k;
                switch(get_class($value)){
                    case 'Money':
                        $value = $value->getAmount();
                        break;
                    case 'Date':
                        $value = $value->Nice();
                        break;
                }
                if(!empty($value))
                    $gridLink .= "&$k=" . urlencode($value);
            }
        }

        return $gridLink;
    }

    public function searchParams(){
        $values = array();
        if(!empty($this->_searchParams))
            return $this->_searchParams;

        foreach ($this->searchParamsKeys as $k){
            if (isset ($_REQUEST[$k]))
                $values[$k] = $_REQUEST[$k];
            else
                $values[$k] = null;
        }
        $this->_searchParams = convertArrayToViewableData($values);

        return $this->_searchParams;
    }
}