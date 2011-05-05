<?php

/**
 * Defines the HomePage page type
 */
class AjustesDirectosEmpNovasPage extends Page {

    static $db = array(
    );
    static $has_one = array(
    );

}

class AjustesDirectosEmpNovasPage_Controller extends Page_Controller {
    static $allowed_actions = array('');

    var $searchParamsKeys = array(
            'sk',
            'dataMin',
            'dataMax',
            'precoMin',
            'precoMax',
            'difDatas'
        );
    var $_searchParams;

    function gridLinkRequest() {
        $gridLink = 'AjustesDirectosService/empNovas/?';

        $params = $this->searchParams();
       
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

        if(empty($values['difDatas']))
            $values['difDatas'] = 12;
        
        $this->_searchParams = convertArrayToViewableData($values);

        return $this->_searchParams;
    }
}