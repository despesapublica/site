<?php

/**
 * Defines the HomePage page type
 */
class HomePage extends Page {

    static $db = array(
    );
    static $has_one = array(
    );

}

class HomePage_Controller extends Page_Controller {
    
    public function init() {
        parent::init();

        $this->MetaTitle = $this->MetaTitle_original;
    }

}