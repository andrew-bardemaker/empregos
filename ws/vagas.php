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
$acentuacoes = array('á'=>'a', 'à'=>'a', 'ã'=>'a', 'â'=>'a', 'é'=> 'e', 'ê'=> 'e', 'í'=>'i', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ú' => 'u', 'ç'=> 'c');
if ($obj) {
    $cidade = str_replace("'", "", $obj->cidade);  
    $query_consulta = "SELECT  * FROM bejobs_vagas WHERE 1=1";
    if ($obj->estado) {
        $query_consulta .= " AND estado = '$obj->estado'";
    }
    if ($obj->cidade) {
        $query_consulta .= " AND cidade ='$cidade'"; 
    }
    if ($obj->profissao) {
        $query_consulta .= " AND profissao = '$obj->profissao'";
    }
    if ($obj->bairro) {
        $query_consulta .= " AND bairro = '$obj->bairro'"; 
    }  
    $query_consulta .= " AND status_pagamento=1";
 
} else {
    $query_consulta = "SELECT  * FROM bejobs_vagas WHERE 1=1 AND status_pagamento=1";
} 
$query_consulta= strtr($query_consulta,$acentuacoes);
 

$query = $dba->query($query_consulta);
$rows = $dba->rows($query);
if ($rows > 0) {
    for ($i = 0; $i < $rows; $i++) {
        $vet = $dba->fetch($query);
        $id = $vet["id"];
        $titulo = $vet["titulo"];
        $local = $vet["local"];
        $instituicao = $vet["instituicao"];
        $profissao = $vet["profissao"]; 
        $pagamento = $vet["pagamento"];
        $cidade = $vet['cidade'];
        $empresa_nome = $vet["nome_empresa"];
        $estado = $vet['estado'];
        $bairro = $vet['bairro'];
        $especialidade = $vet['especialidade'];
        $link = "";
        if (file_exists('../images/vagas/' . $id . '.jpg')) {
            $link = $id . ".jpg";
        } else {
            $link = "default.jpg";
        };


        $array_vagas[] = array(
            "id_vaga" => utf8_encode($id),
            "titulo" => utf8_encode($titulo),
            "local" => utf8_encode($local),
            "instituicao" => utf8_encode($instituicao),
            "profissao" => utf8_encode($profissao),
            "pagamento" => utf8_encode($pagamento),
            "imagem" => $link,
            "cidade" => utf8_encode($cidade),
            "estado" => $estado,
            "bairro" => utf8_encode($bairro),
            "nome_empresa" => utf8_encode($empresa_nome),
            "especialidade"=> utf8_encode($especialidade)

        );
    };

    $array = array("success" => true, "vagas" => $array_vagas, "total" => $rows);
} else {
    $array = array("success" => true, "vagas" => NULL, "total" => $rows);
}

header('Content-type: application/json');
echo json_encode($array);
// echo $query_consulta;
