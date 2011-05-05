<?php

/**
 * @version
 * @author Joao Martins <jfmartins@portugalmail.pt>
 * @copyright	Copyright (C) 2011 Joao Martins
 * @license		GNU General Public License version 2 or later
 */

$dirname = dirname(__FILE__);

require_once ($dirname . '/gviz_api.php');

abstract class GVizRequest extends BaseRequest {

    public $query;

    public $queryParams;

    public $dbresultQuery;

    public $gvizTable;

    public $tqx;

    public $responseHandler;
    
    public function __construct($params) {
        parent::__construct($params);
        
        $this->gvizTable= new GvizDataTable($this->tqx, $this->responseHandler);        
    }

    protected function parsingParams() {
        parent::parsingParams();
        
        $this->tqx = $this->getParam('tqx', CLEAN_STRING, '');
        $this->responseHandler = $this->getParam('responseHandler', CLEAN_STRING, '');
    }

    public function process() {
        $this->setQuerys();
        $this->setColumns();

        $pQuery = prepare_query($this->query, -1, -1, $this->queryParams);
        $this->dbresultQuery = DB::query($pQuery);

        $i = 0;
        foreach($this->dbresultQuery as $row) {
            $this->processRow($row, $i);
            $i++;
        }

        //echo '<pre>';
        //print_r($this->gvizTable);

        echo $this->gvizTable->toJsonResponse();
    }

    protected abstract function setQuerys();

    protected abstract function setColumns();

    protected abstract function processRow($row, $indice);
}
?>
