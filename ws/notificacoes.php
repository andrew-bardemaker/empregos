<?php

/**
 * @author			Andrew Bardemaker - v 1.0 - abr/2019
 * @description 	Serviço de retorno das perguntas/respostas FAQ
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
 


$json  = file_get_contents('php://input');
$obj   = json_decode($json); // var_dump($obj);

if ($obj) {
	$id_usuario = $obj->user_id;
	$sql2 = "SELECT id, titulo, status, data_registro FROM bejobs_notificacoes_usuarios WHERE id_usuario = $id_usuario ORDER BY status,data_registro DESC";
	$query2 = $dba->query($sql2);
	$qntd2 = $dba->rows($query2);
	$total_notificacoes = 0;
	if ($qntd2 > 0) {
		for ($j = 0; $j < $qntd2; $j++) {
			$vet2 = $dba->fetch($query2);
			$id = $vet2[0];
			$titulo = $vet2[1];

			$status = $vet2[2];
			if ($status == 1) {
				$total_notificacoes++;
			}

			$data_hora_registro = datetime_date_ptbr($vet2[3]) . " " . datetime_time_full_ptbr($vet2[3]);

			$array_notificacoes[] = array('id' => $id, 'titulo' => $titulo, 'status' => $status, 'data_hora_registro' => $data_hora_registro);
		}
		$array = array("success" => "true", "notificacoes" => $array_notificacoes, "total" => $total_notificacoes,);
	} else {
		$array = array("success" => "true", "notificacoes" => null, "total" => 0,);
	}
}else{
	$array = array("success" => "true", "notificacoes" => null, "total" => 0,);
}

header('Content-type: application/json');
echo json_encode($array);
