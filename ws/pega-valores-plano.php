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


$SELECT_DIAMANTE = "SELECT * FROM bejobs_valores WHERE plano LIKE '%Diamante%'";
$query = $dba->query($SELECT_DIAMANTE);
$rows = $dba->rows($query);

if ($rows > 0) {
    for ($i = 0; $i < $rows; $i++) {
        $vet = $dba->fetch($query);
        $plano = str_replace(' ', '_', $vet["plano"]);
        $valor = $vet["valor"];
        $planos_diamante[$plano] = $valor * 100;
    }
} else {
    $planos_diamante = array();
}


$SELECT_QUARTZO = "SELECT * FROM bejobs_valores WHERE plano LIKE '%Quartzo%'";
$query = $dba->query($SELECT_QUARTZO);
$rows = $dba->rows($query);

if ($rows > 0) {
    for ($i = 0; $i < $rows; $i++) {
        $vet = $dba->fetch($query);
        $plano = str_replace(' ', '_', $vet["plano"]);
        $valor = $vet["valor"];
        $planos_quartzo[$plano] = $valor * 100;
    }
} else {
    $planos_quartzo = array();
}

if ($planos_quartzo == array()) {
    if ($planos_diamante == array()) {
        $array = array("error" => true, "message" => "Não há valores de planos no banco de dados");
    }
} else {
    $array = array("success" => true, "valores" => array("Diamante" => $planos_diamante, "Quartzo" => $planos_quartzo));
}


echo json_encode($array);
header('Content-Type: application/json');
header('Accept: application/json');
