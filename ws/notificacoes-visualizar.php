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

if (!empty($_GET['id'])) {
	$id_notificacao   = $_GET['id'];
	$id_usuario   = $_GET['user_id'];
	$array_notificacao = array();

	$sql1 = "UPDATE bejobs_notificacoes_usuarios AS notificacoes
			 SET notificacoes.status = 2 
			 WHERE notificacoes.id = $id_notificacao 
			 AND notificacoes.id_usuario = $id_usuario 
			 AND status = '1'";
	$dba->query($sql1);

	$sql2 = "SELECT notificacoes.id, notificacoes.titulo, notificacoes.status, notificacoes.data_registro, notificacoes.texto, notificacoes.id_mensagens_usuarios
			 FROM bejobs_notificacoes_usuarios AS notificacoes
			 WHERE notificacoes.id = $id_notificacao 
			 AND notificacoes.id_usuario = $id_usuario";
	$query2 = $dba->query($sql2);
	$qntd2 = $dba->rows($query2);

	if ($qntd2 > 0) {
		$vet2 = $dba->fetch($query2);
		$id_notificacao = $vet2[0];
		$titulo = stripslashes($vet2[1]);
		$texto = stripslashes(nl2br($vet2[4]));

		$data_registro = datetime_date_ptbr($vet2[3]) . ' ' . datetime_time_full_ptbr($vet2[3]);

		$id_mensagens_usuarios = $vet2[5];

		$array_notificacao[] = array(

			"id" => $id_notificacao,
			"titulo" => $titulo,
			/* "imagem" => 0, */
			"texto" => $texto

		);

		$array = array("success" => "true", "notificacao" => $array_notificacao);
	} else {
		$array = array("error" => "true", "msg" => 'Essa notificação não contém informações.');
	}
} else {
	$array = array("error" => "true", "msg" => 'Parâmetros');
}

header('Content-Type: application/json');
echo json_encode($array);
