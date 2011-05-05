<?php

/***************************
*** Nao se pode converter o ficheiro para UTF8, caso contrario a expressao nao funciona: if(strtolower(utf8_decode(substr(trim($tds->eq(5)->html()), 0, 27))) == 'constitui��o de sociedade')
******************************/
set_time_limit (0);


require('config.php');

$url = 'http://publicacoes.mj.pt/pt/Pesquisa.asp?iNIPC=%s&sFirma=&dfDistrito=&dfConcelho=&dInicial=&dFinal=&iTipo=0&sCAPTCHA=&pesquisar=Pesquisar&dfConcelhoDesc=';
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
echo '<br>Inicio:'.date('d-m-Y H:i:s');
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
	$result = fakeUserAgentHttpGet($url_aux);

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
echo '<br>Fim:'.date('d-m-Y H:i:s');
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
		if(strtolower(utf8_decode(substr(trim($tds->eq(5)->html()), 0, 27))) == 'constitui��o de sociedade')
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