<?php
# Funções PHP para utilização do mysql
#
# Versão: 0.0.1
# Ultima revisão: 2004/02/12
# Desenvolvidas por [Dragon]
#
# Nota: Funções em estado de desenvolvimento. Utilize a seu próprio risco.


# Changelog
# 0.0.2
#		stripslashes nas funcoes mysql_ver html_entities;



#ligação ao mysql
$mysql_link = mysql_connect($MY_HOST, $MY_USER, $MY_PASS) or die("Erro ao ligar à BD.");
$ar=mysql_select_db($MY_DB);

mysql_mc_query("SET CHARACTER SET 'latin1'");
mysql_mc_query("SET NAMES 'latin1'");

# Cria um array associativo utilizando valores de variaveis previamente definidas
#
# Ex.:
# $nome='Vasco Pinheiro';
# $nick='[Dragon];
#
# $ar=criar_array('nome','nick');
#
# Resultado:
# $ar = array ('nome'=> 'Vasco Pinheiro', 'nick'=>'[Dragon]');


function criar_array($variaveis, $local=null)
{
	global $mc_debug;

   $baseArray=array();

   foreach ($variaveis as $var)
   {
   		if($local != null){
	  		if (isset($local[$var])){
	        	$baseArray[$var]=$local[$var];
	  		}
	  		else{
	  			if($mc_debug){
					echo "<b>Variavel desconhecida:</b>".$var.'<br/>';
				}
	  		}
   		}
	  	else{
	  		if(isset($_REQUEST[$var])){
	  			$baseArray[$var]=$local[$var];
	  		}
	  		else{
	  			if(isset($GLOBALS[$var])){
	  				$baseArray[$var]=$local[$var];
	  			}
	  			else{
	  				if($mc_debug){
						echo "<b>Variavel desconhecida:</b>".$var.'<br/>';
					}
	  			}
	  		}
	  	}
   }

	return $baseArray;
}




# Insere registos na tabela
# $tabela = nome de tabela
# $parametros= Array associativo em que as chaves sao os campos, e os valores são os valores a inserir nesses campos
#
# $nome='Vasco Pinheiro';
# $nick='[Dragon];
# $tab_nicks='tabela_nicks';
#
# $param = criar_array('nome','nick');
#
# mysql_insere ($tab_nicks, $param);
#

function mysql_insere($tabela, $parametros, $replace=false, $return_id=false)
{
	global $mc_debug;

   $id=-1;

   $parm_keys=array_keys($parametros);
   $parm_values=array_values($parametros);

   $num = count($parm_keys);
   for  ($c=0; $c<$num; $c++)
   {
    	if(is_int($parm_keys[$c]))
        {
  	    unset($parm_keys[$c]);
            unset($parm_values[$c]);
        }        
   }
   $parm_keys=array_values($parm_keys);
   $parm_values=array_values($parm_values);



   if($replace)
		$sql='REPLACE INTO ' .$tabela.' (';
   else
		$sql='INSERT INTO ' .$tabela.' (';

   #adiciona os parametros
   for  ($c=0; $c<count($parm_keys); $c++)
   {
    	$sql.=$parm_keys[$c];
  	    if ($c<count($parm_keys)-1) $sql.=", ";
   }

   #adiciona os valores
   $sql.=') VALUES (';

   for  ($c=0; $c<count($parm_values); $c++)
   {

	   if($parm_values[$c]==='null')
	   	$sql.="null";
	   else if(is_array($parm_values[$c]))
	   {
	   		$sql.=$parm_values[$c][0];
	   }
	   else if(substr($parm_keys[$c], 0, 2) == 'Id' && strlen($parm_values[$c])==32)
	   {
	   		$sql.="UNHEX('".addslashes($parm_values[$c])."')";
	   }
	   else{
	   		$sql.="'".addslashes($parm_values[$c])."'";
	   }

      if ($c<count($parm_values)-1) $sql.=", ";
   }

   $sql.=')';

   //echo '<br><br>'.$sql ."<br>";
   $result=mysql_query($sql);
  if (!$result){
  	if($mc_debug){
	  	die ("ERRO ". $sql . " ". mysql_error());
	}
	else{
		die ("ERRO no SQL para a $tabela .");
	}
  }
  else if($return_id)
  {
		$id=mysql_insert_id();
  }

return ($id);
}


# Edita registos na tabela
# $tabela = nome de tabela
# $parametros= Array associativo em que as chaves sao os campos a ser actualizados, e os valores são os valores a actualizar nesses campos
# $condicao = Condição para que os registos sejam actualizados;
#
# $tab_nicks='tabela_nicks';
#
# $id = 1;
# $nome='Vasco Pinheiro';
# $nick='[Dragon];
#
#
# $param = criar_array('nome','nick');
#
# mysql_edita ($tab_nicks, $param, 'id='.$id);
#

function mysql_edita($tabela, $parametros, $condicao)
{
	global $mc_debug;

   $parm_keys=array_keys($parametros);
   $parm_values=array_values($parametros);

   $sql='UPDATE '.$tabela.' SET ';

   #adiciona os parametros
   for  ($c=0; $c<count($parm_keys); $c++)
   {
	   if($parm_values[$c]==='null')
	   		$sql.=$parm_keys[$c]."=null";
	   else if(is_array($parm_values[$c]))
	   {
	   		$sql.= $parm_keys[$c]."=".$parm_values[$c][0];
	   }
	   else if(substr($parm_keys[$c], 0, 2) == 'Id' && strlen($parm_values[$c])==32)
	   {
	   		$sql.=$parm_keys[$c]."=UNHEX('".addslashes($parm_values[$c])."')";
	   }
	   else{
	      $sql.=$parm_keys[$c]."='".addslashes($parm_values[$c])."'";
	   }

       if ($c<count($parm_keys)-1) $sql.=", ";
   }


   if (strlen($condicao)>0) $sql.=' WHERE '.$condicao;

	#echo '<br><br>'.$sql.'<br>';
   $result=mysql_query($sql);
#   if (!$result) die ("ERRO ". $sql . " ". mysql_error());
   if (!$result)
   {
  	if($mc_debug){
	  	die ("ERRO ". $sql . " ". mysql_error());
	}
	else{
		die ("ERRO no SQL para a $tabela .");
	}
  }
}




# Consulta um registo na tabela
# $tabela = nome de tabela
# $condicao = condição de pesquisa
#
# $tab_nicks='tabela_nicks';
#
# $id=1
#
# $dados=mysql_ver ($tab_nicks, 'id='.$id);
#
# Resultado:
# $dados = array ('id'=>1, 'nome'=> 'Vasco Pinheiro', 'nick'=>'[Dragon]');
#
function mysql_ver($tabela, $condicao, $uid = true)
{
	global $mc_debug;

	if($uid)
   		$sql='SELECT *, LOWER(HEX(Id)) as HexId FROM '.$tabela;
   	else
   		$sql='SELECT * FROM '.$tabela;

   if (strlen($condicao)>0) $sql.=' WHERE '.$condicao;

   $sql.=" LIMIT 1";
   #print $sql. "<br>";
   $result=mysql_query($sql);
   if (!$result){
  	if($mc_debug){
	  	die ("ERRO ". $sql . " ". mysql_error());
	}
	else{
		die ("ERRO no SQL para a $tabela .");
	}
  }

   $ar=mysql_fetch_array($result);

if (count($ar)>1) foreach($ar as $key=>$val) $ar[$key]=stripslashes($val);

return ($ar);
}



# Devolve uma matrix de 'campos' consoante a 'condicao' de uma determinada 'ordem'
# $tabela = nome de tabela
# $campos = campos a ser retornados da tabela
# $condicao = condição de pesquisa
# $ordem = ordenacao dos dados
# $inicio= indice de inicio
# $total = total de dados devolvidos
#
# $tab_nicks='tabela_nicks';
#
#
# $matrix=mysql_lista($tab_nicks, 'id, nome, nick', 'id>0','',0,5);
#
# Resultado:
# $matrix = array(
#                   0 => array ('id'=>1, 'nome'=> 'Vasco Pinheiro', 'nick'=>'[Dragon]'),
#                   1 => array ('id'=>2, 'nome'=> 'VPinheiro', 'nick'=>'[Drn]')
#                 )
#
# echo $matrix[0]['nome']; // Vasco Pinheiro
# echo $matrix[1]['nick']; // [Drn]



function mysql_lista($tabela, $campos, $condicao="", $ordem='id desc', $inicio=0, $total=0)
{

	   $matrix=array();
	   $ar=array();

	   $sql_select="SELECT $campos FROM $tabela ";
	   if (strlen($condicao)>0) $sql_select.=" WHERE $condicao ";

       if (strlen($ordem)>0) $sql_select.=" ORDER BY $ordem ";
	   if ($total) $sql_select.=" LIMIT $inicio, $total";

       //echo "<br> $sql_select <br>";
	   $result_select=mysql_query($sql_select);


   	   if (!$result_select)
		   {
	    	     echo "Erro a retornar lista da $tabela <br>";
				 echo mysql_error();
				 echo "<br>$sql_select";
				 die;
	   	   }


	  while($ar=mysql_fetch_array($result_select))
	  {
            foreach($ar as $key=>$val) $ar[$key]=stripslashes($val);
 	    $matrix[]=$ar;
	  }
	return $matrix;
}



# Apaga registo da tabela
# $tabela = nome de tabela
# $condicao = condição para apagar
#
# $tab_nicks='tabela_nicks';
#
# $id=1
#
# mysql_apaga ($tab_nicks, 'id='.$id);
#

function mysql_apaga($tabela, $condicao)
{
	global $mc_debug;

	   $sql_select="DELETE $tabela FROM $tabela ";
	   if (strlen($condicao)>0) $sql_select.=" WHERE $condicao ";
       else {echo "Tem de especificar uma condição para apagar registos."; die();}

	   $result_select=mysql_query($sql_select);
#echo $sql_select.'<br>';

	 if (!$result_select)
	   {
	  	if($mc_debug){
		  	die ("ERRO SQL ". mysql_error()."\n<br/>".$sql_select);
		}
		else{
			die ("ERRO no SQL para a $tabela .");
		}
	  }
}

function mysql_mc_query($query){
	global $mc_debug;

	$result_select=mysql_query($query);

	if (!$result_select){
  		if($mc_debug){
	  		die ("ERRO SQL ". mysql_error()."\n<br/>".$query);
	}
	else{
		die ("ERRO no SQL para a $query .");
	}	
  }
  return $result_select;
}

?>
