<?php
setlocale(LC_ALL, 'pt_PT');

$dirname = dirname(__FILE__);
require_once ($dirname . '/code/utils.php');

//SS_Log::add_writer(new SS_LogEmailWriter('geral@despesapublica.com'), SS_Log::ERR);
//SS_Log::add_writer(new SS_LogFileWriter('ss_errors.txt'), SS_Log::ERR);

//SS_Cache::set_cache_lifetime('any', 21600, 2);
Director::set_environment_type("dev");
if (Director::isDev()) {
	SSViewer::flush_template_cache();
	$_GET['flush'] = 1; //resize images
}

//acho que nao é preciso
//Requirements::set_combined_files_enabled(true);

global $project;
$project = 'dinheiropublico';

global $databaseConfig;
$databaseConfig = array(
	"type" => 'MySQLDatabase',
	"server" => 'localhost',
	"username" => 'root',
	"password" => '',
	"database" => 'despesapublica',
	"path" => '',
);

MySQLDatabase::set_connection_charset('utf8');

// This line set's the current theme. More themes can be
// downloaded from http://www.silverstripe.org/themes/
SSViewer::set_theme('dinheiropublico');

// Set the site locale
i18n::set_locale('pt_PT');

// enable nested URLs for this site (e.g. page/sub-page/)
SiteTree::enable_nested_urls();

SpamProtectorManager::set_spam_protector("RecaptchaProtector");
RecaptchaField::$public_api_key = '';
RecaptchaField::$private_api_key = '';

Director::addRules(100, array(
 'AjustesDirectosService/$Action' => 'AjustesDirectosService_Controller',
 'EntidadesService/$Action' => 'EntidadesService_Controller'
));