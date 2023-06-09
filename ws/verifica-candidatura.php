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
$json  = file_get_contents('php://input');
$obj   = json_decode($json); // var_dump($obj);

if ($obj) {
    $id_usuario = $obj->id_usuario;
    $id_vaga = $obj->id_vaga;

    $select_vaga = "SELECT * FROM bejobs_candidaturas WHERE id_vaga=$id_vaga AND id_usuario=$id_usuario";
    $query = $dba->query($select_vaga);
    $rows = $dba->rows($query);
    if ($rows > 0) {
        $array_retorno = array("success" => true, "result" => true);
    } else {
        $array_retorno = array("success" => true, "result" => false);
    }
} else {
    $array_retorno = array("error" => true, "message" => "Dados insuficientes");
}

echo json_encode($array_retorno);