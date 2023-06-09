<?php

/**
 * @author			Andrew Bardemaker - v 1.0 - mar/2023 
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
    $id_tokenized = $obj->token;

    $select_query = "SELECT * FROM bejobs_usuarios WHERE token = '$id_tokenized'";

    $query = $dba->query($select_query);
    $rows = $dba->rows($query);

    if($rows>0){
        $vet1 = $dba->fetch($query);
        
        $array_response= array(
            
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

        $array =array("success"=>true, "user"=>$array_response);
    }else{
        $array = array("error" => true, "user"=>array());
    }
}

header('Content-Type: application/json');
echo json_encode($array);