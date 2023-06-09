<?php
/**
 * @author			Andrew Bardemaker - v 1.0 - mar/2019
 * @description 	Serviço que retorna os dados dos usuários
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
include('./verificatoken.php');

$array = array();

$json  = file_get_contents('php://input');

$obj   = json_decode($json); // var_dump($obj);

if ($obj === null) {
	$array = array("error" => "true", "type" => "format_json", "msg" => "format_json");
	echo json_encode($array);
	exit;
}

if (!empty($obj->id_usuario) && isset($obj->id_usuario) && is_numeric($obj->id_usuario) && !empty($obj->token) && isset($obj->token)) {

	$id_usuario = addslashes($obj->id_usuario);
	$token      = addslashes($obj->token);	

	$verificatoken = verificaToken($token, $id_usuario);
	if ($verificatoken === false) {
		$array = array("error" => "true", "type" => "token_invalido", "msg" => "Token inválido.");
		header('Content-type: application/json');
		echo json_encode($array);
		exit;
	}	
	
	$sql2 = "SELECT * FROM bejobs_usuarios WHERE id = $id_usuario";
	$query2 = $dba->query($sql2);
	$qntd2 = $dba->rows($query2);
	if ($qntd2 > 0) {
		$vet2 = $dba->fetch($query2);

		$id   = $vet2['id'];

		if (file_exists('../images/avatar/'.$id.'_120x120.jpg')) {
			$avatar = 'https://geladaemcasaapp.com.br/images/avatar/'.$id.'_120x120.jpg?v='.date('YmdHis');

		} else {
			$avatar = 'https://geladaemcasaapp.com.br/images/avatar/profile.png?v='.date('YmdHis');
		}

		$nome          = stripslashes($vet2['nome']);
		$primeiro_nome = explode(" ", $vet2['nome']);
		$primeiro_nome = $primeiro_nome[0];
		$cpf           = $vet2['cpf'];
		
		if ($vet2['nascimento']!='0000-00-00') {
			$nascimento = dataBR($vet2['nascimento']);
		} else {
			$nascimento = '';
		}					

		$telefone_celular = $vet2['telefone_celular'];
		$email            = stripslashes($vet2['email']);
		$senha            = $vet2['senha'];
		$status_onesignal = $vet2['status_onesignal'];

		$array = array( 
						"success" => "true",
						"id" => $id, 
						"avatar" => $avatar, 
						"nome" => $nome, 
						"primeiro_nome" => $primeiro_nome, 
						"cpf" => $cpf, 
						"nascimento" => $nascimento, 
						"telefone_celular" => $telefone_celular, 
						"email" => $email,
						"status_onesignal" => $status_onesignal);
		
	} else {
		$array = array("error" => "true", "type" => "usuario_invalido", "msg" => "Usuário inválido.");

	}

} else {
	$array = array("error" => "true", "type" => "parametros", "msg" => "Parâmetros inválidos.");
}

header('Content-type: application/json');
echo json_encode($array);

?>