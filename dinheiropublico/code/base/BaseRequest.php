<?php

/**
 * @version
 * @author Joao Martins <jfmartins@portugalmail.pt>
 * @copyright	Copyright (C) 2011 Joao Martins
 * @license		GNU General Public License version 2 or later
 */
abstract class BaseRequest {
     /**
     * Input Params
     *
     * @var mixed
     */
    public $params;
    public $historyColumns;
    public $historyTable;
    public $historyHash;
    public $ip;
    public $httpUserAgent;
    public $dataInicio;
    public $dataFim;
    
    public function __construct($params) {
        $this->params = $params;

        $this->dataInicio = date('d/m/Y H:i:s');
        $this->ip = '';//$_SERVER['REMOTE_ADDR'];
        $this->httpUserAgent = $_SERVER['HTTP_USER_AGENT'];

        $this->parsingParams();
    }

    protected function parsingParams() {
    }

    public function getParam($name, $type = null, $defaultValue = null) {
        return getParam($this->params, $name, $type, $defaultValue);
    }

    public abstract function process();

    public function saveToHistory(){
        if(empty($this->dataFim))
            $this->dataFim = date('d/m/Y H:i:s');

        if(!empty($this->historyColumns) && !empty($this->historyTable)){
            $params = $this->getParamsToHistory();
            if(empty($this->historyHash))
                $this->historyHash = md5(implode('-', $params).'-'.$this->ip);

            $this->prepareParamsToHistory($params);

            $pQuery = $this->prepareQuery($params);

            DB::query($pQuery);
        }
    }

    public function getParamsToHistory(){
        $params = array();

        foreach ($this->historyColumns as $c){
            $params[$c] = $this->$c;
        }

        return $params;
    }

    public function prepareParamsToHistory(&$params){
        $params['Ip'] = $this->ip;
        $params['httpUserAgent'] = $this->httpUserAgent;
    }

    public function prepareQuery($params){

        $query = "INSERT ".$this->historyTable." (". implode(', ', array_keys($params));
        $query .= ", Hash, DataPesquisaInicio, DataPesquisaFim)";
        $query .= " VALUES (".  str_repeat('?, ', count($params)) . " UNHEX(?), STR_TO_DATE(?, '%d/%m/%Y %H:%i:%s'), STR_TO_DATE(?, '%d/%m/%Y %H:%i:%s'))";

        $params['Hash'] = $this->historyHash;
        $params['DataPesquisaInicio'] = $this->dataInicio;
        $params['DataPesquisaFim'] = $this->dataFim;
        

        $pQuery = prepare_query($query, -1, -1, $params);

        return $pQuery;
    }
}

?>
