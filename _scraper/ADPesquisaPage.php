<?php

/**
 * Defines the HomePage page type
 */
class ADPesquisaPage extends Page {

    static $db = array(
    );
    static $has_one = array(
    );

}

class ADPesquisaPage_Controller extends Page_Controller {
    static $allowed_actions = array('view');

    function gridLinkRequest() {
        $gridLink = 'AdjudicacoesDirectasService/pesquisa/';

        $sk = $this->gridSK();

        //Controller::join_links($this->Link(), $rssRelativeLink);

        if (!empty($sk)) {
            //$sk = cleanValue($_REQUEST['sk']);
            $gridLink .= '&sk=' . urlencode($sk);
        }

        return $gridLink;
    }

    public function gridSK(){
        $sk = '';

         if (!empty($_REQUEST['sk'])) {
            $sk = $_REQUEST['sk'];
        }

        return $sk;
    }

    public function view(){
        $sk = 'dfsfs';

         if (!empty($_REQUEST['sk'])) {
            $sk = $_REQUEST['sk'];
        }

        //return $sk;
        return array(
			'Title' => "dsadasdas: ".$this->urlParams['ID']
		);
    }

}