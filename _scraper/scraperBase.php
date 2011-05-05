<?php
require('config.php');

$site = 'base.gov.pt';
$url = 'http://www.base.gov.pt/_layouts/ccp/AjusteDirecto/Detail.aspx?idAjusteDirecto=%s';
$appType = 'base.gov.pt Total';
$maxPesquisa = 5000;

require('includes/phpQuery/phpQuery.php');
require('includes/uuid/lib.uuid.php');
require('includes/utils.php');
require('includes/funcoes_mysql_inc.php');

if (isset($_REQUEST['Id'])) {
    $id = intval($_REQUEST['Id']);
    $maxPesquisa = 1; //so quero este Id
} else if (isset($_REQUEST['FromId'])) {
    $id = intval($_REQUEST['FromId']);
} else if (isset($_REQUEST['Cronjob'])) {
    $cronjobsName = $_REQUEST['Cronjob'];

    $job = getCronjob($cronjobsName);

    $id = $job['LastId'];
}
else {
    echo "Parametros Válidos:<br/>";
    echo "Id<br/>";
    echo "FromId<br/>";
    echo "Cronjob<br/>";
    exit();
    //Obter o ultimo id
    /* $id = getLastId();
      $id = $id-400;//verifica-se de forma constante se os ultimos ainda sao validos, porque sao os mais propicios a serem alterados
      if($id<0)
      $id = 0;

     */
}

$runSeconds = 600; //10 minutos

/*
 echo "Next Run: ".$job['NextRun'];
            echo "<br/>Now is: ".date('Y-m-d H:i:s  O  I');
            echo "<br/>".date('d/m/Y H:i:s e  O  T  I');
            echo "<br/><br/>";
        echo "Nextwww Run: ".strtotime($job['NextRun']);
            echo "<br/>Now is: ".time();
            exit();*/

if (!empty($job)){
    if(!empty($job['NextRun'])){
        
        if(strtotime($job['NextRun']) > time())
        {
            echo "Next Run: ".$job['NextRun'];
            echo "<br/>Now is: ".date('Y-m-d H:i:s');
            exit();
        }

    }
    if (!empty($job["RunMinutes"])) {
        $runSeconds = $job["RunMinutes"] * 60;
    }
}


set_time_limit($runSeconds + 120); //da-se sempre 2 minutos de margem;


if ($id < 0) {
    echo "Id invalido";
    exit();
}



///// Get Content
$lastFound = $id;
$curlErros = 0;
$i = 0;
$numRemovidos = 0;
$startTime = time();
$maxTime = $startTime + $runSeconds;
$timeElapsed = false;

$htmlText = "";
if (isset($_REQUEST['Cronjob']))
    $htmlText .= "CronJob: " . $_REQUEST['Cronjob'];
$htmlText .= "\n<br/>Inicio: " . date('d-m-Y H:i:s');
$htmlText .= "\n<br/>Primeiro Id a pesquisar:" . $id;


while ($id < $lastFound + 40 && $curlErros < 10) { //parte-se do principio que nao existem mais se nao encontramos nenhum registo depois de 40 tentativas
    
    if ($maxTime < time()) {
        $timeElapsed = true;
        break;

    }

    if ($i >= $maxPesquisa) {
        break;
    }

    if ($i > 0)//so incrementa o id se nao for a primeira vez
        $id++;

    $i++;

    if (($id % 40) == 0) {
        my_sleep(8);
    } else if (($id % 10) == 0) {
        my_sleep(4);
    }

    $url_aux = sprintf($url, $id);
    $result = fakeUserAgentHttpGet($url_aux);

    if (!empty($result['curl_error'])) {
        mysql_insere('errors', array(
            'AppType' => $appType,
            'url' => $url_aux,
            'Header' => $result['header'],
            'Body' => $result['body'],
            'ErrorMsg' => $result['curl_error'],
            'HttpCode' => $result['http_code'],
            'DataI' => array('NOW()'),
            'DataU' => array('NOW()')
                )
        );

        $curlErros++;
        continue;
    }

    $curlErros = 0; //limpa os erros curl

    if (!empty($cronjobsName))
        mysql_edita('cronjobs', array('LastId' => $id, 'LastRun' => array('NOW()')), "Name = '" . $cronjobsName . "'");

    if ($result['http_code'] != 200) {
        if ($result['http_code'] != 302) {
            mysql_insere('errors', array(
                'AppType' => $appType,
                'url' => $url_aux,
                'Header' => $result['header'],
                'Body' => $result['body'],
                'ErrorMsg' => $result['curl_error'],
                'HttpCode' => $result['http_code'],
                'DataI' => array('NOW()'),
                'DataU' => array('NOW()')
                    )
            );
        } else {
            //Verificar se existe o contrato na BD, se existir remover
            $dbRecord = mysql_ver('ad_contrato', "IdSite = " . $id . " AND Site='" . $site . "'");
            if (!empty($dbRecord)) {
                $dbRecord['IsDeleted'] = 1;
                saveHistory($dbRecord);

                mysql_apaga('ad_contrato_entidade', "IdContrato = UNHEX('" . $dbRecord['HexId'] . "')");
                mysql_apaga('ad_contrato_localidade', "IdContrato = UNHEX('" . $dbRecord['HexId'] . "')");
                mysql_apaga('ad_contrato', "IdSite = " . $id . " AND Site='" . $site . "'");
                $numRemovidos++;
            }
        }
        continue;
    }

    $lastFound = $id;

    $resultParsing = processContent($result['body'], $id, $url_aux);
}

$htmlText .= "\n<br/>Data Fim:" . date('d-m-Y H:i:s');
$htmlText .= "\n<br/>Ultimo Id processado:" . $lastFound;
$htmlText .= "\n<br/>Numero Ids Pesquisados " . $i;
$htmlText .= "\n<br/>Numero Removidos:" . $numRemovidos;
$htmlText .= "\n<br/>Erros de pesquisa (curl):" . $curlErros;


if (!empty($job)){
    $nextRun='';
    $nextId = $lastFound;
    if ($timeElapsed) {
        $nextRun = time()+($job['SleepElapsedMinutes']*60);
    }
    else{
        $nextRun = time()+($job['SleepCompletedMinutes']*60);
        if ($job['restart'] >= 0)
            $nextId = $job['restart'];
        else
            $nextId = $lastFound + $job['restart'];
    }
    mysql_edita('cronjobs', array('LastId'=>$nextId,'NextRun' =>array("FROM_UNIXTIME(".$nextRun.")")), "Name = '" . $cronjobsName . "'");
}

if (!empty($job) && $job['RunTotais'] > 0) {

    mysql_mc_query("INSERT INTO entidade_estatisticas (IdEntidade, Ano, `NumRegistosAdAdjudicadas`, `ValorTotalAdAdjudicadas`)
(select e.Id, -1, count(*) as num, sum(c.Preco) as preco
from entidade e inner join ad_contrato_entidade ce on e.id = ce.IdEntidade inner join ad_contrato c on ce.IdContrato = c.Id AND ce.Tipo='A'
group By e.Id)
ON DUPLICATE KEY UPDATE NumRegistosAdAdjudicadas = VALUES(NumRegistosAdAdjudicadas), ValorTotalAdAdjudicadas=VALUES(ValorTotalAdAdjudicadas)");
    mysql_mc_query("INSERT INTO entidade_estatisticas (IdEntidade, Ano, `NumRegistosAdContratadas`, `ValorTotalAdContratadas`)
(select e.Id, -1, count(*) as num, sum(c.Preco) as preco
from entidade e inner join ad_contrato_entidade ce on e.id = ce.IdEntidade inner join ad_contrato c on ce.IdContrato = c.Id AND ce.Tipo='C'
group By e.Id)
ON DUPLICATE KEY UPDATE NumRegistosAdContratadas = VALUES(NumRegistosAdContratadas), ValorTotalAdContratadas=VALUES(ValorTotalAdContratadas)");


    mysql_mc_query("INSERT INTO entidade_estatisticas (IdEntidade, Ano, `NumRegistosAdAdjudicadas`, `ValorTotalAdAdjudicadas`)
(select e.Id, YEAR(c.datacontrato), count(*) as num, sum(c.Preco) as preco
from entidade e inner join ad_contrato_entidade ce on e.id = ce.IdEntidade inner join ad_contrato c on ce.IdContrato = c.Id AND ce.Tipo='A'
group By e.Id, YEAR(c.datacontrato))
ON DUPLICATE KEY UPDATE NumRegistosAdAdjudicadas = VALUES(NumRegistosAdAdjudicadas), ValorTotalAdAdjudicadas=VALUES(ValorTotalAdAdjudicadas)");
    mysql_mc_query("INSERT INTO entidade_estatisticas (IdEntidade, Ano, `NumRegistosAdContratadas`, `ValorTotalAdContratadas`)
(select e.Id, YEAR(c.datacontrato), count(*) as num, sum(c.Preco) as preco
from entidade e inner join ad_contrato_entidade ce on e.id = ce.IdEntidade inner join ad_contrato c on ce.IdContrato = c.Id AND ce.Tipo='C'
group By e.Id, YEAR(c.datacontrato))
ON DUPLICATE KEY UPDATE NumRegistosAdContratadas = VALUES(NumRegistosAdContratadas), ValorTotalAdContratadas=VALUES(ValorTotalAdContratadas)");

    mysql_mc_query("INSERT INTO entidade_relacao (IdEntidade, Tipo, IdEntidadeRelacionada, Total, ValorTotal, ValorMax, ValorMin, DataMax, DataMin)
(SELECT Entidade, Tipo, Relacionada, count(*) as Total, sum(preco) as ValorTotal, max(preco) as ValorMax, min(preco) as ValorMin, max(dataContrato) as DataMax, min(dataContrato) as DataMin
FROM
(
SELECT A.IdEntidade Entidade, B.tipo, B.IDENTIDADE Relacionada, A.IdContrato
FROM ad_contrato_entidade A
INNER JOIN ad_contrato_entidade B
ON A.IDCONTRATO = B.IDCONTRATO
WHERE A.ID <> B.ID
AND A.TIPO <> B.TIPO
) A
INNER JOIN ad_contrato B
ON A.IDCONTRATO = B.ID
GROUP BY Entidade, Tipo, Relacionada)
ON DUPLICATE KEY UPDATE Total = VALUES(Total), ValorTotal=VALUES(ValorTotal), ValorMax=VALUES(ValorMax), ValorMin=VALUES(ValorMin), DataMax=VALUES(DataMax), DataMin=VALUES(DataMin)");

    mysql_mc_query("update `ad_contrato_entidade` ce inner join ad_contrato c on ce.idContrato = c.id inner join entidade e on ce.IdEntidade = e.Id
set diasDiffContrato = DATEDIFF( c.DataContrato, e.DataCriacao )
where e.DataCriacao is not null AND e.DataCriacao <> '0000-00-00' AND c.DataContrato is not null AND c.DataContrato <> '0000-00-00'");


    mysql_mc_query("update `ad_contrato_entidade` ce inner join ad_contrato c on ce.idContrato = c.id inner join entidade e on ce.IdEntidade = e.Id
set diasDiffPublicacao = DATEDIFF( c.DataContrato, e.DataCriacao )
where e.DataCriacao is not null AND e.DataCriacao <> '0000-00-00'");
}

echo $htmlText;
echo "<br/><br/><a href=".$_SERVER["REQUEST_URI"].">correr novamente</a>";
mail('geral@despesapublica.com', "scraper base", $htmlText);

exit();

function processContent($content, $idSite, $url) {
    global $site, $appType;

    if (empty($content)) {
        mysql_insere('errors', array(
            'AppType' => $appType,
            'url' => $url,
            'ErrorMsg' => 'Empty body',
            'DataI' => array('NOW()'),
            'DataU' => array('NOW()')
                )
        );

        return false;
    }

    $novo = true;
    $dados = array(
        'DataU' => array('NOW()'),
        'DataP'=> array('NOW()')
    );

    $dbRecord = mysql_ver('ad_contrato', "IdSite = " . $idSite . " AND Site='" . $site . "'");
    if (!empty($dbRecord)) {
        $novo = false;
        $dados['Id'] = $dbRecord['HexId'];
    } else {
        $dados['Id'] = UUID::mint(4)->hex;
        $dados['DataI'] = array('NOW()');
        $dados['Site'] = $site;
        $dados['IdSite'] = $idSite;
        $dados['Url'] = $url;
    }
    $doc = phpQuery::newDocumentHTML($content);

    $dados['DataPublicacao'] = $doc['.centercol span[id*=lblDataRegisto]']->html();
    $dados['NProcesso'] = trim($doc['.centercol span[id*=lblIdProc]']->html());
    $dados['Descricao'] = utf8_decode($doc['.centercol span[id*=lblDescription]']->html());
    $dados['DataContrato'] = $doc['.centercol span[id*=lblDataDecisaoCtr]']->html();
    $dados['Preco'] = $doc['.centercol span[id*=lblPrecoContrato]']->html();
    $dados['PrazoExecucao'] = utf8_decode($doc['.centercol span[id*=lblPrazoExecucao]']->html());
    $dados['Criterio'] = utf8_decode($doc['.centercol span[id*=lblCriterioMaterial]']->html());

    $dados['Preco'] = floatvalue($dados['Preco']);
    $dados['DataPublicacao'] = datevalue($dados['DataPublicacao']);
    $dados['DataContrato'] = datevalue($dados['DataContrato']);

    //Local Execucao
    $localPrecricao = $doc['.centercol span[id*=lblLocalExecucao]'];
    $localPrecricao_dom = pq($localPrecricao);
    $listaLocais = $localPrecricao_dom['ul li'];

    if (!empty($listaLocais->elements)) {
        $dados['LocalExecucao'] = '';
        foreach ($localPrecricao_dom['ul li'] as $li) {
            $dados['LocalExecucao'] .= pq($li)->html() . '||';
        }

        if (strlen($dados['LocalExecucao']) > 2)
            $dados['LocalExecucao'] = substr($dados['LocalExecucao'], 0, strlen($dados['LocalExecucao']) - 2);
    }
    else {
        $dados['LocalExecucao'] = $doc['.centercol span[id*=lblLocalExecucao]']->html();
    }
    $dados['LocalExecucao'] = utf8_decode($dados['LocalExecucao']);

    //Tratar as entidades
    //Primeiro apaga se existir alguma
    mysql_apaga('ad_contrato_entidade', "IdContrato = UNHEX('" . $dados['Id'] . "')");
    $num_a = 0;
    $last_a = null;
    $first = true;
    $dados['EntidadesAdjudicantes'] = '';
    //echo $idSite.'<br/>';
    foreach ($doc['table[id*=gvEntidadesAdjudicantes] tr'] as $tr) {
        if ($first) {
            $first = false;
            continue;
        }

        $tds = pq($tr)->children();

        $entidade = findInsertEntidade($tds->eq(0)->html(), $tds->eq(1)->html(), $url);
        $dados['EntidadesAdjudicantes'] .= $entidade['Nome'] . '||';

        $dados_entidade = array(
            'Id' => UUID::mint(4)->hex,
            'IdContrato' => $dados['Id'],
            'IdEntidade' => $entidade['HexId'],
            'Tipo' => 'A', //Adjudicante
            'Nome' => $entidade['Nome']
        );
        mysql_insere('ad_contrato_entidade', $dados_entidade);
        $last_a = $dados_entidade['IdEntidade'];
        $num_a++;
    }
    if (strlen($dados['EntidadesAdjudicantes']) > 2)
        $dados['EntidadesAdjudicantes'] = substr($dados['EntidadesAdjudicantes'], 0, strlen($dados['EntidadesAdjudicantes']) - 2);

    $first = true;
    $num_c = 0;
    $last_c = null;
    $dados['EntidadesContratadas'] = '';
    foreach ($doc['table[id*=gvEntidadesAdjudicatarias] tr'] as $tr) {
        if ($first) {
            $first = false;
            continue;
        }

        $tds = pq($tr)->children();

        $entidade = findInsertEntidade($tds->eq(0)->html(), $tds->eq(1)->html(), $url);
        $dados['EntidadesContratadas'] .= $entidade['Nome'] . '||';

        $dados_entidade = array(
            'Id' => UUID::mint(4)->hex,
            'IdContrato' => $dados['Id'],
            'IdEntidade' => $entidade['HexId'],
            'Tipo' => 'C', //Contratado
            'Nome' => $entidade['Nome']
        );
        mysql_insere('ad_contrato_entidade', $dados_entidade);
        $last_c = $dados_entidade['IdEntidade'];
        $num_c++;
    }
    if (strlen($dados['EntidadesContratadas']) > 2)
        $dados['EntidadesContratadas'] = substr($dados['EntidadesContratadas'], 0, strlen($dados['EntidadesContratadas']) - 2);

    $dados['NumAdjudicantes'] = $num_a;
    if ($num_a == 1)
        $dados['IdEntidadeAdjudicante'] = $last_a;
    else
        $dados['IdEntidadeAdjudicante'] = null;

    $dados['NumContratados'] = $num_c;
    if ($num_c == 1)
        $dados['IdEntidadeContratada'] = $last_c;
    else
        $dados['IdEntidadeContratada'] = null;

    if ($novo) {
        mysql_insere('ad_contrato', $dados, true);
    } else if (hasChanged($dbRecord, $dados)) {
        mysql_edita('ad_contrato', $dados, "Id = UNHEX('" . $dados['Id'] . "')");

        saveHistory($dbRecord);
    }
    else{
        mysql_edita('ad_contrato', array('DataP'=>array('NOW()')), "Id = UNHEX('" . $dados['Id'] . "')");
    }

    return true;
}

function findInsertEntidade($nif, $nome, $url) {
    global $appType;
    //echo $nif;

    $nif = utf8_decode(trim($nif));
    $nome = utf8_decode(trim($nome));
    if ($nif[0] == '?')
        $nif[0] = '';

    $nif = trim($nif);

    if (!empty($nif))
        $dados = mysql_ver('entidade', "Nif = '" . $nif . "'");
    else if (!empty($nome))
        $dados = mysql_ver('entidade', "Nome = '" . $nome . "'");
    else {
        mysql_insere('errors', array(
            'AppType' => $appType,
            'url' => $url,
            'ErrorMsg' => 'Empty nif e nome',
            'DataI' => array('NOW()'),
            'DataU' => array('NOW()')
                )
        );
        exit();
    }

    if (empty($dados) || empty($nif)) {
        $dados = array(
            'Id' => UUID::mint(4)->hex,
            'Nif' => $nif,
            'Nome' => $nome,
            'DataI' => array('NOW()')
        );
        mysql_insere('entidade', $dados);

        $dados['HexId'] = $dados['Id'];

        return $dados;
    } else {
        return $dados;
    }
}

function hasChanged($currentData, $newData) {
    if ($currentData['DataContrato'] == '0000-00-00')
        $currentData['DataContrato'] = null;

    if ($currentData['DataPublicacao'] != $newData['DataPublicacao'])
        return true;
    if ($currentData['NProcesso'] != $newData['NProcesso'])
        return true;
    if ($currentData['Descricao'] != $newData['Descricao'])
        return true;
    if ($currentData['DataContrato'] != $newData['DataContrato'])
        return true;
    if ($currentData['Preco'] != $newData['Preco'])
        return true;
    if ($currentData['Criterio'] != $newData['Criterio'])
        return true;
    if ($currentData['LocalExecucao'] != $newData['LocalExecucao'])
        return true;
    if ($currentData['EntidadesAdjudicantes'] != $newData['EntidadesAdjudicantes'])
        return true;
    if ($currentData['EntidadesContratadas'] != $newData['EntidadesContratadas'])
        return true;
    if ($currentData['NumAdjudicantes'] != $newData['NumAdjudicantes'])
        return true;
    //nao e necessario comparar e um esta em binario e o outro em hex
    //if($currentData['IdEntidadeAdjudicante'] != $newData['IdEntidadeAdjudicante'])
    //	return true;
    if ($currentData['NumContratados'] != $newData['NumContratados'])
        return true;
    //nao e necessario comparar e um esta em binario e o outro em hex
    //if($currentData['IdEntidadeContratada'] != $newData['IdEntidadeContratada'])
    //	return true;

    return false;
}

function saveHistory($dbRecord) {
    $dbRecord['DataH'] = array('NOW()');
    if (empty($dbRecord['IdContrato'])) {
        $dbRecord['IdContrato'] = $dbRecord['HexId'];
    }
    if(empty($dbRecord['DataP']))
        unset($dbRecord['DataP']);
    
    unset($dbRecord['Id']);
    unset($dbRecord['NumAdjudicantes']);
    unset($dbRecord['NumContratados']);
    unset($dbRecord['IdEntidadeAdjudicante']);
    unset($dbRecord['IdEntidadeContratada']);
    unset($dbRecord['DataI']);
    unset($dbRecord['HexId']);

    mysql_insere('ad_contrato_history', $dbRecord, false);
}

function getCronjob($jobName) {
    $cRecord = mysql_ver('cronjobs', "Name = '" . $jobName . "'", false);
    if (empty($cRecord)) {
        $cRecord = array('Name' => $jobName, 'LastId' => 0);
        mysql_insere('cronjobs', $cRecord);
    }

    return $cRecord;
}

function getLastId() {
    $contratos = mysql_lista("ad_contrato", 'IdSite', "", "IdSite desc", 0, 1);

    if (count($contratos) == 0) {
        return 0;
    } else {
        return $contratos[0]['IdSite'];
    }
}

?>