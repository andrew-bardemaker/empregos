<?php
#PagSeguro deve receber cartao e cobrar do mesmo , se sucedido retornar success":true}
#caso contrario retornar error":true}  
 
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

include('../admin/inc/inc.configdb.php');
include('../admin/inc/inc.lib.php');

 
function calcula_percentagem($cemPercento,$array_percent){
    $array_percentages=array();
    for($i=0;$i<sizeof($array_percent);$i++){
        $array_percentages[]= number_format(($array_percent[$i]/$cemPercento)*100,2,'.','');
    }
    return $array_percentages;
}

#PEGA VALOR TOTAL DAS COLUNAS
$query_total_results="SELECT * FROM bejobs_pedidos";
$query100 = $dba->query($query_total_results);
$cemPercente = $dba->rows($query100);

#CONTA NUMERO DE COLUNAS EXISTENTES PARA CADA STATUS
$array_values=array();
for($index=1;$index<6;$index++){
    $query_valor = "SELECT * FROM bejobs_pedidos WHERE status=$index";
    $query = $dba->query($query_valor);
    $rows = $dba->rows($query);

    $array_values[]=$rows;
}
 
$final=calcula_percentagem($cemPercente,$array_values);  
?>
 
