<?php

/**
 * @author			Andrew Bardemaker - v 1.0 - mar/2019
 * @description 	Serviço de login usuário
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
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

include('../admin/inc/inc.configdb.php');
include('../admin/inc/inc.lib.php');

$query_consulta = "SELECT * FROM bejobs_termos_privacidade WHERE id = 1";
$query = $dba->query($query_consulta);
$vet = $dba->fetch($query);

$array = array("success" => true, "termos" => $vet['texto']);

header('Content-Type: application/json');
echo json_encode($array);
