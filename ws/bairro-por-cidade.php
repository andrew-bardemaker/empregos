<?php

/**
 * @author			Andrew Bardemaker - v 1.0 - jan/2023 
 * @params

 */
// session_start();
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Credentials: true');
header('Content-type: application/json');
header('X-Content-Type-Options: nosniff');

include('../admin/inc/inc.configdb.php');
include('../admin/inc/inc.lib.php');

$array           = array();
$array_vagas = array();
$json  = file_get_contents('php://input');
$obj   = json_decode($json); // var_dump($obj);

if ($obj) { 
    $acentuacoes = array('á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ç' => 'c');
    $cidade=$obj->cidade; 
    $cidade = strtr($cidade, $acentuacoes);
    $cidade = str_replace("'", "", $obj->cidade);  
    $query_bairros = "SELECT bairro FROM bejobs_vagas WHERE cidade='$cidade'";
    $query = $dba->query($query_bairros);
    $rows = $dba->rows($query); 
    if ($rows > 0) {
        for ($i = 0; $i < $rows; $i++) {
            $vet = $dba->fetch($query);

            $bairros[] =utf8_encode($vet["bairro"]);        
        }
        $array = array("success" => true, "bairros" => $bairros);
    } else {
        $array = array("success" => true, "bairros" => NULL);
    }
}else {
    $array = array("success" => true, "bairros" => NULL);
}

header('Content-type: application/json');
echo json_encode($array);
