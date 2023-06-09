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

$array = array();

$json  = file_get_contents('php://input');

$obj   = json_decode($json); // var_dump($obj);

if ($obj === null) {
	$array = array("error" => "true", "type" => "format_json", "msg" => "format_json");
	header('Content-Type: application/json');
	echo json_encode($array);
	exit;
};

if (!empty($obj->user) && isset($obj->user) && !empty($obj->senha) && isset($obj->senha)) {

	$cpf   = preg_replace("/[^0-9]/", "", $obj->user); // Retira formatação do CPF ou CNPJ
	$email = addslashes($obj->user); // Retira formatação do CPF ou CNPJ
	$senha = trim(md5($obj->senha));
	// $senha = password_hash($senha, PASSWORD_DEFAULT);
	
	$sql1   = "SELECT * FROM bejobs_usuarios WHERE  email = '$email'";
	$query1 = $dba->query($sql1);
	$qntd1  = $dba->rows($query1);

	if ($qntd1 > 0) {
		$vet1 = $dba->fetch($query1);

		$senha_usuario = $vet1['senha'];

		$array_user = array(
			"nome"          => $vet1['nome'],
			"telefone"      => $vet1['telefone_celular'],
			"logradouro"    => utf8_encode($vet1['logradouro']),
			"id"            => $vet1['id'],
			"numero"        => $vet1['numero'],
			"complemento"   => $vet1['complemento'],
			"bairro"        => $vet1['bairro'],
			"cep"           => $vet1['CEP'],
			"cidade"        => $vet1['cidade'],
			"estado"        => $vet1['estado'],
			"nascimento"    => $vet1['nascimento'],
			"sexo"          => $vet1['sexo'],
			"cpf"           => $vet1['cpf'],
			"rg"            => $vet1['rg'],
			"email"		    => $vet1['email'],
			"telefone_celular" => $vet1['telefone_celular'],
			"tipo_usuario" => $vet1["tipo_usuario"],
			"portfolio" => $vet1["portfolio"],
			"plano_empresa" => $vet1["plano_empresa"],
			"instagram" => $vet1["instagram"],
			"facebook" => $vet1["facebook"],
			"linkedin" => $vet1["linkedin"],
			"token" => $vet1["token"]
		);

		if ($senha = $senha_usuario) {

			$array = array("success" => true, "user" => $array_user);
		} else {
			$array = array("error" => "true", "type" => "senha", "msg" => "Usuário ou senha incorretos! Tente novamente.");
		};
	} else {
		$array = array("error" => "true", "type" => "cpf", "msg" => "Usuário ou senha incorretos! Tente novamente.");
	};
} else {
	$array = array("error" => "true", "type" => "parametros", "msg" => "Parâmetros inválidos.");
};

// error_log(json_encode($_SERVER));

header('Content-Type: application/json');
echo json_encode($array);
