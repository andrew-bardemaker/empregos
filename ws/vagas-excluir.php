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
$array_produtos = array();
$json  = file_get_contents('php://input');
$obj   = json_decode($json); // var_dump($obj);


if ($obj) {
    $sql_delete = "DELETE FROM bejobs_vagas WHERE id = $obj->id_vaga";
    $dba->query($sql_delete);

    unlink("../images/vagas/$obj->id_vaga.jpg");
    $array_retorno = array("success" => true, "message" => "Vaga exlcuída com sucesso!");
} else {
    $array_retorno = array("error" => true, "message" => "parametros");
};

$json = json_encode($array_retorno);
echo $json;
