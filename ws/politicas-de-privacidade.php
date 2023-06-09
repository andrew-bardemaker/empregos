<?php

/**
 * @author			Andrey Willian - v 1.0 - abr/2022
 * @description 	Serviço de retorno de Políticas de Privacidade
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


$array = array();

$sql   = "SELECT * FROM bejobs_termos_privacidade"; // print_r($sql);

$query = $dba->query($sql);

$qntd  = $dba->rows($query);

if ($qntd > 0) {

	$vet = $dba->fetch($query);

	$texto = stripslashes($vet['texto']);
}

$array = array("success" => "true", "termos" => $texto);


header('Content-type: application/json');
echo json_encode($array);
