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
    $id = $obj->id;  

    $sql_update = "UPDATE `bejobs_vagas` SET `titulo`='$obj->titulo',`descricao`='$obj->descricao',`profissao`='$obj->profissao',`id_empresa`='$obj->id_empresa',`pagamento`='$obj->pagamento',`cidade`='$obj->cidade',`estado`='$obj->estado' WHERE id=$id";
    
    $query = $dba->query($sql_update); 

    $image = $obj->imagem; 

    $image_base64 = $obj->imagem;
    if (!empty($image_base64)) {
        $file_chunks = explode(";base64,", $image_base64);
        $image_file = base64_decode($file_chunks[1]); 
        file_put_contents("../images/vagas/" . $id . ".jpg", $image_file);
    };
    $array_retorno = array("success" => true, "message" => "Vaga atualizada com sucesso!");

} else {
    $array_retorno = array("error" => true, "message" => "parametros");
};

$json = json_encode($array_retorno);
echo $json;
