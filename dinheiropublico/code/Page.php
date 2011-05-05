<?php

class Page extends SiteTree {

    public static $db = array(
    );
    public static $has_one = array(
    );

}

class Page_Controller extends ContentController {

    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * array (
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * );
     * </code>
     *
     * @var array
     */
    public static $allowed_actions = array('');
    public $MetaTitle_original;

    public function init() {
        parent::init();

        $this->MetaTitle_original = $this->MetaTitle;
        
        if(!empty($this->MetaTitle))
            $this->MetaTitle = $this->MetaTitle . ' - ' . $this->SiteConfig()->Title;
        else
            $this->MetaTitle = $this->SiteConfig()->Title;
        
        $this->_indicadoresGlobais = array();

        $this->combineFiles();
    }

    public function combineFiles(){
        //Set our theme's CSS folder
        $themeFolder = $this->ThemeDir();

        //Set the folder to our theme so that relative image paths work
        Requirements::set_combined_files_folder($themeFolder . '/combinedfiles');

        //Add all the files to combine into an array
        $CSSFiles = array(
            $themeFolder . '/css/jqueryui.css',
            'dinheiropublico/javascript/jqgrid/ui.jqgrid.css',
            $themeFolder . '/css/layout.css',
            $themeFolder . '/css/typography.css',
            $themeFolder . '/css/form.css',
            $themeFolder . '/css/format.css',
        );

        //Combine!
        Requirements::combine_files("combinedCSS.css", $CSSFiles);

        Validator::set_javascript_validation_handler('none');

        //Add all our files to combine into an array
        $JSFiles = array(
            "dinheiropublico/javascript/jquery.ba-bbq.min.js",
            "dinheiropublico/javascript/jqgrid/i18n/grid.locale-pt.js",
            "dinheiropublico/javascript/jqgrid/jquery.jqGrid.min.js",
            "dinheiropublico/javascript/jqgrid/grid.history.js",
            "dinheiropublico/javascript/jqgrid/grid.opendata.js",
            "dinheiropublico/javascript/superfish/js/hoverIntent.js",
            "dinheiropublico/javascript/superfish/js/superfish.js",
            "dinheiropublico/javascript/jquery.inputmask.js"
        );

        //Combine!
        Requirements::combine_files("combinedJS.js", $JSFiles);
    }

    public function siteRoot(){
        return Director::absoluteBaseURL();
    }

    public function getDomain(){
        return Director::protocolAndHost();
    }

    public function indicadoresDia(){
        $dataMin = $dataMax = date('d/m/Y');
        return $this->indicadoresGlobais($dataMin, $dataMax);
    }
    public function indicadoresOntem(){
        $dataMin = $dataMax = date('d/m/Y', strtotime("-1 day"));
        return $this->indicadoresGlobais($dataMin, $dataMax);
    }
    public function indicadoresSemana(){
        $dataMin = date('d/m/Y', strtotime("last Monday"));
        $dataMax = date('d/m/Y');
        return $this->indicadoresGlobais($dataMin, $dataMax, 'dataContrato');
    }
    public function indicadoresMes(){
        $dataMin = '01/'.date('m/Y');
        $dataMax = date('d/m/Y');
        return $this->indicadoresGlobais($dataMin, $dataMax, 'dataContrato');
    }
    public function indicadoresAno(){
        $dataMin = '01/01/'.date('Y', strtotime("last Sunday"));
        $dataMax = date('d/m/Y');
        return $this->indicadoresGlobais($dataMin, $dataMax, 'dataContrato');
    }

     public function indicadoresTotais(){
        return $this->indicadoresGlobais();
    }

    public function indicadoresGlobais($dataMin=null, $dataMax=null, $colunaPesquisa='dataPublicacao'){
        $where = '';
        $queryParams = array();
        if(!empty($dataMin)){
            $where .= " AND $colunaPesquisa >= STR_TO_DATE(?, '%d/%m/%Y')";
            $queryParams[] = $dataMin;
        }
        if(!empty($dataMax)){
            $where .= " AND $colunaPesquisa <=  STR_TO_DATE(?, '%d/%m/%Y')";
            $queryParams[] = $dataMax;
        }

        $queryt1 = "SELECT count(*) as NumRegistos, sum(Preco) as PrecoTotal, MAX(DataContrato) as DataContratoMax, MAX(DataPublicacao) as DataPublicacaoMax, MIN(DataPublicacao) as DataPublicacaoMin, MAX(Preco) as PrecoMax, MIN(Preco) as PrecoMin
            FROM ad_contrato Where 1 ".$where;

        $queryt2 = "SELECT count(*) as NumRegistos, MIN(DataContrato) as DataContratoMin, SUM(TIMESTAMP(DataPublicacao)-TIMESTAMP(DataContrato)) as NumDatasDiff
            FROM ad_contrato
			WHERE DataContrato is not null and DataContrato <> '0000-00-00' ".$where;

        $querytA = "SELECT count(*) as NumAdjudicantes from (Select distinct idEntidade
            FROM ad_contrato_entidade ce inner join ad_contrato c on ce.IdContrato = c.Id
			WHERE ce.Tipo = 'A' AND c.DataContrato is not null and c.DataContrato <> '0000-00-00' ".$where;
        $querytA .= ") as tt";

        $querytC = "SELECT count(*) as NumContratados from (Select distinct idEntidade
            FROM ad_contrato_entidade ce inner join ad_contrato c on ce.IdContrato = c.Id
			WHERE ce.Tipo = 'C' AND c.DataContrato is not null and c.DataContrato <> '0000-00-00' ".$where;
        $querytC .= ") as tt";

        if(!empty($queryParams))
        {
            $pqueryt1 = prepare_query($queryt1, -1, -1, $queryParams);
        }
        else{
            $pqueryt1 = $queryt1;
        }

        $hash = md5($pqueryt1);

        if (!empty($this->_indicadoresGlobais[$hash]))
            return $this->_indicadoresGlobais[$hash];

        if(!empty($queryParams))
        {
            $pqueryt2 = prepare_query($queryt2, -1, -1, $queryParams);
            $pquerytA = prepare_query($querytA, -1, -1, $queryParams);
            $pquerytC = prepare_query($querytC, -1, -1, $queryParams);
        }
        else{
            $pqueryt2 = $queryt2;
            $pquerytA = $querytA;
            $pquerytC = $querytC;
        }

        $rowt1 = DB::query($pqueryt1)->record();
        $rowt2 = DB::query($pqueryt2)->record();
        $rowtA = DB::query($pquerytA)->record();
        $rowtC = DB::query($pquerytC)->record();

        if(!empty($rowt1['NumRegistos']))
        {
            $rowt1['PrecoMedio'] = $rowt1['PrecoTotal']/$rowt1['NumRegistos'];
            $rowt1['NDatasDiffMedio'] = $rowt2['NumDatasDiff']/$rowt2['NumRegistos'];
        }
        $rowt1['DataContratoMin'] = $rowt2['DataContratoMin'];
        $rowt1['NumRegistoAdjudicantes'] = $rowtA['NumAdjudicantes'];
        $rowt1['NumRegistoContratados'] = $rowtC['NumContratados'];
        $rowt1['NumRegistosSemDataContrato'] = $rowt1['NumRegistos']-$rowt2['NumRegistos'];


        $this->_indicadoresGlobais[$hash] = convertArrayToViewableData($rowt1);
        return $this->_indicadoresGlobais[$hash];
    }

    public function indicadoresPrecoMaior0(){
        if (!empty($this->_indicadoresPrecoMaior0))
            return $this->_indicadoresPrecoMaior0;

        $queryt1 = "SELECT count(*) as NumRegistos, sum(Preco) as PrecoTotal, MAX(Preco) as PrecoMax, MIN(Preco) as PrecoMin
            FROM ad_contrato
			WHERE preco > 0";

        $rowt1 = DB::query($queryt1)->record();

        $rowt1['PrecoMedio'] = $rowt1['PrecoTotal']/$rowt1['NumRegistos'];
        $this->_indicadoresPrecoMaior0 = convertArrayToViewableData($rowt1);
        return $this->_indicadoresPrecoMaior0;
    }
    public function indicadoresPrecosBaixo(){
        if (!empty($this->_indicadoresPrecosBaixo))
            return $this->_indicadoresPrecosBaixo;

        $queryt1 = "SELECT count(*) as NumRegistos, sum(Preco) as PrecoTotal, MAX(Preco) as PrecoMax, MIN(Preco) as PrecoMin
            FROM ad_contrato
			WHERE preco < 5";

        $rowt1 = DB::query($queryt1)->record();

        $this->_indicadoresPrecosBaixo = convertArrayToViewableData($rowt1);
        return $this->_indicadoresPrecosBaixo;
    }

    public function getDataLastADUpdate(){
		//se quisermos forcar a gerar os indicadores, alteramos esta variavel, caso contrario eles so seram actualizados quando a cache expirar
        $this->_dataLastADUpdate = '3';

        if (!empty($this->_dataLastADUpdate))
            return $this->_dataLastADUpdate;

        $query = "SELECT COUNT(*) FROM ad_contrato";

        $this->_dataLastADUpdate = DB::query($query)->value();

        return $this->_dataLastADUpdate;
    }
}