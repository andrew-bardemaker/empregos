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



if ($obj) {
            $id_empresa = $obj->id_empresa;  

            $query_consulta = "SELECT  * FROM bejobs_vagas WHERE id_empresa='$id_empresa'";
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
                    $bairro = $vet['bairro'];
                    $estado = $vet['estado'];
                    $nome_empresa = $vet["nome_empresa"];
                    $status= $vet["status_pagamento"];
                    $link = "";
                    if (file_exists('../images/vagas/' . $id . '.jpg')) {
                        $link = $id.'.jpg';
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
                        "cidade" => $cidade,
                        "estado" => $estado,
                        "status" => $status,
                        "nome_empresa"=>$nome_empresa,
                        "bairro"=>$bairro

                    );
                };
            } 
         $array = array("success" => true, "vagas" => $array_vagas); 
} else {
    $array = array("error" => true, "msg" => "Não há parametros o suficiente");
}

$json = json_encode($array);
echo $json;
