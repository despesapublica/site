<?php

set_time_limit(0);


$MY_HOST = 'localhost';
$MY_DB = 'opendata';
$MY_USER = 'root';
$MY_PASS = '';

$mc_debug = true;
require('includes/utils.php');
require('includes/uuid/lib.uuid.php');
require('includes/funcoes_mysql_inc.php');


$result_select = mysql_mc_query("SELECT distinct id, dd, cc, localidade, llll
FROM localidade_ctt_dist");

while ($d = mysql_fetch_array($result_select)) {
    mysql_apaga('localidade_ctt_dist', "dd = '".$d['dd']."' AND cc = '".$d['cc']."' AND localidade = '".addslashes($d['localidade'])."' AND llll = '".$d['llll']."' AND id <> ".$d['id']);
    //echo $d['tt']."&nbsp;&nbsp;&nbsp;&nbsp;ART_DESIG is null AND dd = '".$d['dd']."' AND cc = '".$d['cc']."' AND localidade = '".$d['localidade']."'<br/>";
}