<?php

/**
 * @version
 * @author Joao Martins <jfmartins@portugalmail.pt>
 * @copyright	Copyright (C) 2011 Joao Martins
 * @license		GNU General Public License version 2 or later
 */
abstract class JQGridRequest extends BaseRequest {

    public $maxLimit = 200;
    public $defaultLimit = 40;
    public $defaultSortName = '';
    public $defaultSortDir = '';
    /**
     * Get the requested page
     *
     * @var int
     */
    public $page;
    /**
     * Get how many rows we want to have into the grid
     *
     * @var int
     */
    public $limit;
    /**
     * Get sort column name
     *
     * @var string
     */
    public $sortName;
    /**
     * Get sort direction
     *
     * @var string
     */
    public $sortDir;
    /**
     * Total the records selected
     *
     * @var int
     */
    public $numTotalRecords;
    /**
     * Query para contar o numero de registos a retornar
     *
     * @var string
     */
    public $queryCount;
    /**
     * Query para seleccionar os registos a retornar
     *
     * @var string
     */
    public $query;

    /**
     * Parameters for query
     *
     * @var array
     */
    public $queryParams;

    /**
     * Automatically add Order to query
     *
     * @var bool
     */
    public $autoAddOrderToQuery;
    
    /**
     * Total de pages. Is automatic calculated from $numTotalRecords / $limit
     *
     * @var int
     */
    public $totalPages;
    /**
     * Index of First record. Is automatic calculated from $page * $limit
     *
     * @var int
     */
    public $start;

    /**
     * Object with data that will be sent in response
     *
     * @var object
     */
    public $dataResponse;

    /**
     * Resource for executed query
     *
     * @var resource
     */
    public $dbresultQuery;
    
    public function __construct($params) {
        parent::__construct($params);
        
        $this->autoAddOrderToQuery = true;
    }

    protected function parsingParams() {
        parent::parsingParams();

        $this->page = $this->getParam('page', CLEAN_INT, 0);
        $this->limit = $this->getParam('rows', CLEAN_INT, $this->defaultLimit);
        $this->sortName = $this->getParam('sidx', CLEAN_STRING, $this->defaultSortName);
        $this->sortDir = $this->getParam('sord', CLEAN_STRING, $this->defaultSortDir);

        if ($this->limit > $this->maxLimit)
            $this->limit = $this->defaultLimit;
    }

    public function process() {
        $this->setQuerys();

        if(!empty($this->queryCount)){
            $pQueryCount = prepare_query($this->queryCount, -1, -1, $this->queryParams);

            $this->numTotalRecords = intval(DB::query($pQueryCount)->value());
        }
        
        if ($this->numTotalRecords > 0) {
            $this->totalPages = ceil($this->numTotalRecords / $this->limit);
        } else {
            $this->totalPages = 0;
        }
        if ($this->page > $this->totalPages)
            $this->page = $this->totalPages;

        $this->start = $this->limit * $this->page - $this->limit; // do not put $limit*($page - 1)

        if ($this->start < 0)
            $this->start = 0;

        $query_aux = $this->query;
        if($this->autoAddOrderToQuery && !empty($this->sortName))
        {
            $query_aux .= ' ORDER BY '.$this->sortName.' '.$this->sortDir;
        }

        $pQuery = prepare_query($query_aux, $this->start, $this->limit, $this->queryParams);

        $this->dbresultQuery = DB::query($pQuery);

        $this->dataResponse = new stdClass();
        $this->dataResponse->page = $this->page;
        $this->dataResponse->total = $this->totalPages;
        $this->dataResponse->records = $this->numTotalRecords;
        $this->dataResponse->rows = array();

        $i = 0;
        foreach($this->dbresultQuery as $row) {
            $this->dataResponse->rows[$i] = $this->processRow($row, $i);
            $i++;
        }

        $this->saveToHistory();

        return $this->dataResponse;
    }

    protected abstract function setQuerys();

    protected abstract function processRow($row, $indice);

    public function prepareParamsToHistory(&$params){
        parent::prepareParamsToHistory($params);
        
        $params['NumTotal'] = $this->numTotalRecords;
        $params['PageIndex'] = $this->page;
        $params['SortName'] = $this->sortName;
        $params['SortDir'] = $this->sortDir;
    }
}

?>
