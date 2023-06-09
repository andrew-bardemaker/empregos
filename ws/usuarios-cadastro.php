<?php
/**
 * @author			Andrew Bardemaker - v 1.0 - mar/2019
 * @description 	Serviço de cadastro usuários
 * @params

 */

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
include('../admin/inc/class.ValidaCpfCnpj.php');

// error_log(json_encode($_SERVER));


$array = array();

$json  = file_get_contents('php://input');

$obj   = json_decode($json); // var_dump($obj);

if ($obj === null) {
	$array = array("error" => "true", "type" => "format_json", "msg" => "format_json");
	header('Content-type: application/json');
	echo json_encode($array);
	exit;
}

if (empty($obj->nome)){
	$array = array("error" => "true", "type" => "nome", "msg" => "Preencha o nome corretamente."); 
    header('Content-type: application/json');
    echo json_encode($array);
	exit;
}

if (empty($obj->cpf)) { // Verifica se foi enviado cpf 
	$array = array("error" => "true", "type" => "cpf", "msg" => "Preencha o CPF corretamente."); 
	header('Content-type: application/json');
	echo json_encode($array);
	exit;
}          

$cpf = new ValidaCPFCNPJ($obj->cpf); // Cria um objeto sobre a classe
if (!$cpf->valida()) { // Verifica se o CPF é válido
	$array = array("error" => "true", "type" => "cpf_invalido", "msg" => "CPF inválido."); 
    header('Content-type: application/json');
    echo json_encode($array);
	exit;
}

$cpf = preg_replace("/[^0-9]/", "", $obj->cpf); // Retira formatação CPF

//verifica se cpf já está registrado no bd    
$sql2 = "SELECT * FROM bejobs_usuarios WHERE cpf LIKE '%$cpf%'";
$query2 = $dba->query($sql2);
$qntd2 = $dba->rows($query2);
if ($qntd2 > 0) {
	$array = array("error" => "true", "type" => "cpf_existe", "msg" => "CPF já cadastrado."); 
    header('Content-type: application/json');
    echo json_encode($array);
	exit;
}

if(empty($obj->email) || !validaEmail($obj->email)) {
	$array = array("error" => "true", "type" => "email_invalido", "msg" => "Email inválido."); 
	header('Content-type: application/json');
	echo json_encode($array);
	exit;
} 

$email = strtolower(addslashes($obj->email));       

$sql2 = "SELECT * FROM bejobs_usuarios WHERE email = '$email'";
$query2 = $dba->query($sql2);
$qntd2 = $dba->rows($query2);
if ($qntd2 > 0) {
	$array = array("error" => "true", "type" => "email_existe", "msg" => "Email já cadastrado."); 
    header('Content-type: application/json');
    echo json_encode($array);
	exit;
}

if(empty($obj->data_nascimento) || !validaData($obj->data_nascimento)) {
	$array = array("error" => "true", "type" => "data_nascimento", "msg" => "Preencha a data de nascimento corretamente."); 
    header('Content-type: application/json');
    echo json_encode($array);
	exit;
}

if(empty($obj->telefone_celular)) {
	$array = array("error" => "true", "type" => "telefone_celular", "msg" => "Preencha o telefone celular corretamente."); 
    header('Content-type: application/json');
    echo json_encode($array);
	exit;
}

// if(empty($obj->cnpj_loja)) {
// 	$array = array("error" => "true", "type" => "cnpj_loja");
//     header('Content-type: application/json');
//     echo json_encode($array);
// 	exit;
// }

if(empty($obj->senha)) {
	$array = array("error" => "true", "type" => "senha", "msg" => "Preencha a senha corretamente.");
    header('Content-type: application/json');
    echo json_encode($array);
	exit;
} 

if (!validaSenha($obj->senha)) {
	$array = array("error" => "true", "type" => "senha", "msg" => "Senha inválida.");
    header('Content-type: application/json');
    echo json_encode($array);
	exit;
}

if(empty($obj->senha_confirma)) {
	$array = array("error" => "true", "type" => "senha_confirma", "msg" => "Informe a senha novamente.");
    header('Content-type: application/json');
    echo json_encode($array);
	exit;
}

if($obj->senha != $obj->senha_confirma) {
	$array = array("error" => "true", "type" => "senhas_iguais", "msg" => "As senhas que você digitou não são iguais.");
    header('Content-type: application/json');
    echo json_encode($array);
	exit;
}

if(empty($obj->regulamento)) {
	$array = array("error" => "true", "type" => "regulamento", "msg" => "Informe que você leu e concorda com o regulamento.");
	 header('Content-type: application/json');
	echo json_encode($array);
	exit;
} 

$nome             = strtoupper(addslashes($obj->nome));
$data_nascimento  = dataMY($obj->data_nascimento);
$telefone_celular = addslashes($obj->telefone_celular);
$telefone_celular = preg_replace("/[^0-9]/", "", $telefone_celular);

// $cnpj_loja        = addslashes($obj->cnpj_loja);
// $cnpj_loja        = preg_replace("/[^0-9]/", "", $cnpj_loja);
$senha_tmp =addslashes($obj->senha);
$senha_tmp        = trim(md5($senha_tmp));

$data_cadastro    = date('Y-m-d H:i:s');
$ip_cadastro      = getIp();

// Grava usuário
$sql = "INSERT INTO bejobs_usuarios (nome, cpf, email, senha, telefone_celular, nascimento, data_cadastro, ip_cadastro, status) VALUES ('$nome', '$cpf', '$email', '$senha_tmp', '$telefone_celular', '$data_nascimento', '$data_cadastro', '$ip_cadastro', 1)";  

$dba->query($sql);

$id_usuario = $dba->lastid();


// Grava informação de registro do aceite do regulamento 
// $sql6 = "INSERT INTO app_usuarios_regulamento (id_usuario, tipo_usuario, ip_registro, data_registro) VALUES ($id_usuario, 2, '$ip_cadastro', NOW())";
// $dba->query($sql6);



// $array = array("success" => "true");

$token = geraToken();

$sql4  = "INSERT INTO bejobs_usuarios_token (id_usuarios, token, data_hora_registro) VALUES ('$id_usuario', '$token', NOW())";
$dba->query($sql4);

$array = array("success" => "true", "token" => $token, "user" => array("id" => $id_usuario, "nome" => $nome)); 

header('Content-type: application/json');
echo json_encode($array);

?>