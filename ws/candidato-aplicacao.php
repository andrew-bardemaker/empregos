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
    $id_usuario = $obj->id_usuario;
    $id_vaga = $obj->id_vaga;

    $sql_select = "SELECT id_empresa FROM bejobs_candidaturas WHERE id_vaga=$obj->id_vaga";
    $query = $dba->query($sql_select);
    $rows = $dba->rows($query);
    $today= date("Y-m-d");
    if ($rows > 0) {
        $vet = $dba->fetch($query);

        $sql_insert = "INSERT INTO `dedstudio12`.`bejobs_candidaturas` (`id_usuario`,`id_vaga`,`id_empresa`,`status`,`data_candidatura`) 
        VALUES ('$obj->id_usuario','$obj->id_vaga', '" . $vet['id_empresa'] . "', '1',' $today');";
        $query2 = $dba->query($sql_insert);
        $id_candidatura = $dba->lastid();
        $array_retorno = array("success" => true, "message" => "Dados adicionados com sucesso", "id_candidatura" => "$id_candidatura");
    }else{
        $array_retorno = array("error" => true, "message" => "Dados insuficientes");
    
    }
} else {
    $array_retorno = array("error" => true, "message" => "Dados insuficientes");
}

$json = json_encode($array_retorno);
echo $json;
