<?php
/**
 * A redirector page redirects when the page is visited.
 *
 * @package cms
 * @subpackage content
 */
class MyRedirectorPage extends RedirectorPage {
	function onBeforeWrite() {
        $tmp_url = $this->ExternalURL;
        
		parent::onBeforeWrite();

        $this->ExternalURL = $tmp_url;
	}
}

/**
 * Controller for the {@link RedirectorPage}.
 * @package cms
 * @subpackage content
 */
class MyRedirectorPage_Controller extends RedirectorPage_Controller {
}