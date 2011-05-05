<?php
set_time_limit (0);


$MY_HOST = 'localhost';
$MY_DB = 'opendata';
$MY_USER = 'root';
$MY_PASS = '';

$mc_debug = true;
require('includes/utils.php');
require('includes/uuid/lib.uuid.php');
require('includes/funcoes_mysql_inc.php');


$dados = mysql_lista("ad_contrato", '*', "LocalExecucao is not null", "");
echo count($dados).'<br/>';
foreach($dados as $d){
	if(empty($d["LocalExecucao"]))
		continue;

	$locais = str_replace(array(';'), ',', $d["LocalExecucao"]);
		
	$locais_a = explode(',', $locais);
	foreach ($locais_a as $l){		
		$l = trim($l);
		if(empty($l))
			continue;
			
		$dados_l = mysql_lista("localidade", '*', "nome = '".$l."'", "");
		
		if(empty($dados_l) || count($dados_l) == 0)
			echo utf8_encode($l).'<br/>';
		
		
		/*mysql_edita('localidade', array(
			'Id'=>UUID::mint(4)->hex
		)
		, "CodDistrito = '".$d['CodDistrito']."' AND CodConcelho = '".$d['CodConcelho']."' AND CodFreguesia is null");//= '".$d['CodConcelho']."' AND CodFreguesia = '".$d['CodFreguesia']."'
		*/
	}
}