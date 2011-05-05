<?php

/**
 * Defines the HomePage page type
 */
class AjustesDirectosEstatisticasPage extends Page {

    static $db = array(
    );
    static $has_one = array(
    );

}

class AjustesDirectosEstatisticasPage_Controller extends Page_Controller {
    static $allowed_actions = array('');

     public function init() {
        parent::init();
    }
}