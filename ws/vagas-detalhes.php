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
$array_produtos = array();
$json  = file_get_contents('php://input');
$obj   = json_decode($json); // var_dump($obj);

$id = $obj->id;
if ($id != NULL) {
    $query_consulta = "SELECT  * FROM bejobs_vagas where id=" . $id;
    $query = $dba->query($query_consulta);
    $rows = $dba->rows($query);
    if ($rows > 0) {
        $vet = $dba->fetch($query);
        $id = $vet["id"];
        $titulo = $vet["titulo"];
        $descricao = $vet["descricao"];
        $status = $vet["status"]; 
        $profissao = $vet["profissao"];
        $id_empresa = $vet["id_empresa"];
        $nome_empresa = $vet["nome_empresa"];
        $pagamento = $vet["pagamento"];
        $id_contratado = $vet["id_contratado"];
        $estado = $vet["estado"];
        $bairro = $vet["bairro"];
        $status = $vet["status_pagamento"];
        $cidade=$vet["cidade"];
        $link = "";
        if (file_exists('../images/vagas/' . $id . '.jpg')) {
            $link = $id.'.jpg';
        } else {
            $link = "default.jpg";
        }
         
        $array_vagas[] = array(
            "id_vaga" => utf8_encode($id),
            "titulo" => utf8_encode($titulo),
            "descricao" => utf8_encode($descricao),
            "status" => utf8_encode($status), 
            "profissao" => utf8_encode($profissao),
            "id_empresa" => utf8_encode($id_empresa),
            "nome_empresa" => utf8_encode($nome_empresa),
            "pagamento" => utf8_encode($pagamento),
            "id_contratado" => utf8_encode($id_contratado),
            "bairro" => utf8_encode($bairro),
            "estado" => utf8_encode($estado),
            "cidade" => utf8_encode($cidade),
            "status" => utf8_encode($status),
            "imagem" => $link
        );
        $array = array("success" => true, "vaga" => $array_vagas);
    } else {
        $array = array("error" => true, "msg" => "ID inexistente");
    }
} else {
    $array = array("error" => true, "msg" => "Parâmetros ruins");
}


header('Content-type: application/json');
echo json_encode($array);
