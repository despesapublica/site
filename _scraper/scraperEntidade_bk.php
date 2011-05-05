<?php

/***************************
*** Nao se pode converter o ficheiro para UTF8, caso contrario a expressao nao funciona: if(strtolower(utf8_decode(substr(trim($tds->eq(5)->html()), 0, 27))) == 'constituição de sociedade')
******************************/
set_time_limit (0);
$dirname = dirname(__FILE__);

if(empty($_REQUEST["Teste"]))
	return;

require('config.php');

//$url = 'http://publicacoes.mj.pt/pt/Pesquisa.asp?iNIPC=%s&sFirma=&dfDistrito=&dfConcelho=&dInicial=&dFinal=&iTipo=0&sCAPTCHA=&pesquisar=Pesquisar&dfConcelhoDesc=';
$url = 'http://publicacoes.mj.pt/Pesquisa.aspx';
$cookiesFile = $dirname.'/tmp/entidadecookie.txt';
$postFields = array(
	'ctl00$ContentPlaceHolderMain$rblTipoPub'=>'0',
	'ctl00$ContentPlaceHolderMain$txtDadosPubNif'=>'509507654',
	'ctl00$ContentPlaceHolderMain$btSearch'=>'Pesquisar');/*
	'aspnetForm'=>'',
	'__VIEWSTATE'=>'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',
	'__EVENTVALIDATION'=>'	PFwMIngcsWuaWfxk2OqJwXpc9QSfKgGjo9ZaY2OCptLTrpPE9VgyadcN1uSjWNnkKLSpt/0td/LmFOVng0b46oPnGQZYMXCM/gUsagkkHf5kz/TAbacR2NsPn4gVc58qZd6hrPMEHaRV21vIB4TTN1inRn0BOLosBhAk+OXhgP5TJQS4i8cdZHVdvqQdbeyCBtJ98GUspHacF0AV5CgLfvJufWw9iGvATM1QfaklYWVQ1qDSsssShqW8nI5bC/sozfFOMv8wedBVXTXTYZBkZIWjW1b/UdoA5H29FUczXvGK/nioDHa3QtzPxLyD5U4IUZm8GjF51+7JIl3luZ6OXA==',
	'ctl00$ContentPlaceHolderMain$NoBot1$NoBot1_NoBotExtender_ClientState'=>'95',
	'ctl00$ContentPlaceHolderMain$txtDadosPubEntidade'=>'',
	'ctl00$ContentPlaceHolderMain$comboDadosPubDistrito'=>'',
	'ctl00$ContentPlaceHolderMain$comboDadosPubConcelho'=>'',
	'ctl00$ContentPlaceHolderMain$txtDataInit'=>'',
	'ctl00$ContentPlaceHolderMain$txtDataFim'=>'',
	'__VIEWSTATEENCRYPTED'=>'',
	'__EVENTARGUMENT'=>'',
	'__EVENTTARGET'=>'',
	'__EVENTTARGET'=>'',
	''=>'',
	''=>'');*/
$appType = 'publicacoes.mj.pt Criacao empresa';

require('includes/phpQuery/phpQuery.php');
require('includes/uuid/lib.uuid.php');
require('includes/utils.php');
require('includes/funcoes_mysql_inc.php');

///// Get Content
$i = 0;
$lastFound = $i;
$curlErros = 0;
//SUBSTR(Nif, 1, 1) = '1' OR SUBSTR(Nif, 1, 1) = '2' OR 
$entidades = mysql_lista("entidade", 'Hex(Id) as Id, Nif', " nif is not NULL AND (SUBSTR(Nif, 1, 1) = '5') AND  DataCriacao is NULL AND DataProcessado is NULL", "", 0,500);
//$entidades = mysql_lista("entidade", 'Hex(Id) as Id, Nif', " nif = '508969263'", "", 0,500);
echo count($entidades);
echo '<br>Inicio:'.date('d-m-Y h:i:s');
if(count($entidades)==0)
{
	echo "Tudo feito";
	exit();
}
foreach($entidades as $entidade)
{	
	if(!is_numeric($entidade['Nif'])){
		continue;
	}

	if($i > $lastFound+10)	
	{
		//Depois de 10 erros termina	
		mysql_insere('errors', array(
				'AppType'=>$appType,
				'ErrorMsg'=>'mais de 10 erros seguidos', 
				'DataI'=>array('NOW()'),
				'DataU'=>array('NOW()')
			)
		);
		echo "ERROS";
		exit();
	}	
	
	$i++;
		
	if(($i%30) == 0)
	{
		my_sleep(10);
	}else if(($i%10) == 0)
	{
		my_sleep(4);	
	}
	
	$url_aux = sprintf($url, $entidade['Nif']);
	$result = fakeUserAgentHttpGet($url_aux, true, $postFields, $cookiesFile);
	echo '<pre>';
	print_r($result);
	exit();
	if(!empty($result['curl_error']))
	{		
		mysql_insere('errors', array(
				'AppType'=>$appType,
				'url'=>$url_aux, 
				'Header'=>$result['header'], 
				'Body'=>utf8_decode($result['body']), 
				'ErrorMsg'=>$result['curl_error'], 
				'HttpCode'=>$result['http_code'],
				'DataI'=>array('NOW()'),
				'DataU'=>array('NOW()')
			)
		);
		
		$curlErros++;
		continue;
	}
	
	$curlErros = 0;//limpa os erros curl
	
	if($result['http_code'] != 200)
	{
		mysql_insere('errors', array(
			'AppType'=>$appType,
			'url'=>$url_aux, 
			'Header'=>$result['header'], 
			'Body'=>utf8_decode($result['body']), 
			'ErrorMsg'=>$result['curl_error'], 
			'HttpCode'=>$result['http_code'],
			'DataI'=>array('NOW()'),
			'DataU'=>array('NOW()')
			)
		);
		continue;
	}
	
	$lastFound = $i;
	
	$resultParsing = processContent($result['body'], $entidade);	
}
echo '<br>Fim:'.date('d-m-Y h:i:s');
exit();

function processContent($content, $entidade){
	global $site, $appType;
		
	if(empty($content))
	{
		mysql_insere('errors', array(
			'AppType'=>$appType, 
			'ErrorMsg'=>'Empty body, nif:'.$entidade['Nif'], 
			'DataI'=>array('NOW()'),
			'DataU'=>array('NOW()')
			)
		);
		
		return false;
	}
	
	$dados = array('DataProcessado' => array('NOW()'));
	
	$doc = phpQuery::newDocumentHTML($content);
	
	$first = true;
	foreach($doc['table.searchHeader_pesquisa tr'] as $tr)
	{		
		if($first)
		{
			$first = false;
			continue;
		}
			
		$tds = pq($tr)->children();
		if(strtolower(utf8_decode(substr(trim($tds->eq(5)->html()), 0, 27))) == 'constituição de sociedade')
		{
			$dados['DataCriacao'] = datevalue($tds->eq(1)->html());				
			mysql_edita('entidade', $dados, "Id = UNHEX('".$entidade['Id']."')");
			return true;	
		}
		
		mysql_insere('publicacoes_mj', array(
			'NIF'=>$entidade['Nif'], 
			'tipo'=>utf8_decode(trim($tds->eq(5)->html())),
			'Data'=>datevalue($tds->eq(1)->html())
			)
		);
	}
	mysql_edita('entidade', $dados, "Id = UNHEX('".$entidade['Id']."')");
		
	return false;
}
?>