<?php
set_time_limit (0);
$row['Foto'] ="eewrwrrew.JPG";
echo	strtolower(substr($row['Foto'], -3, 3));
					/*if(strtolower(substr($row['Foto'], -3, -3)) == "bmp"){
						
					}
					else if(strtolower(substr($row['Foto'], -3, -3)) == "jpg"){
						$img = imagecreatefromjpeg($row['Foto']);		
					}*/
					
				exit();	
$MY_HOST = 'localhost';
$MY_DB = 'opendata';
$MY_USER = 'root';
$MY_PASS = '';

$mc_debug = true;
require('includes/utils.php');
require('includes/funcoes_mysql_inc.php');

//Obter o ultimo id
$contratos = mysql_lista("ad_contrato", '*, hex(Id) as uid', "", "");
foreach($contratos as $c){
	mysql_edita('ad_contrato', array(
		'Descricao'=>utf8_decode($c['Descricao']),
		'Criterio'=>utf8_decode($c['Criterio']),
		'PrazoExecucao'=>utf8_decode($c['PrazoExecucao']),
		'LocalExecucao'=>utf8_decode($c['LocalExecucao'])
	)
	, "Id = UNHEX('".$c['uid']."')");
}