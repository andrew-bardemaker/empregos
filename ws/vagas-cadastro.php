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
include('../admin/inc/m2brimagem.class.php');

$array           = array();
$array_produtos = array();
$json  = file_get_contents('php://input');
$obj   = json_decode($json); // var_dump($obj);


if ($obj) {
    $titulo = utf8_encode($obj->titulo);
    $descricao = utf8_encode($obj->descricao);
    $profissao = utf8_encode($obj->profissao);
    $cidade = utf8_encode(str_replace("'", "", $obj->cidade));
    $estado = utf8_encode($obj->estado);
    $bairro = utf8_encode($obj->bairro);
    $confidencial = $obj->confidencial;

    $nome_empresa_sql = "SELECT nome FROM bejobs_usuarios WHERE id=$obj->id_empresa";

    $query = $dba->query($nome_empresa_sql);

    if ($confidencial) {
        $nome_empresa = "CONFIDENCIAL";
    } elseif ($dba->rows($query) > 0) {
        $vet = $dba->fetch($query);
        $nome_empresa = $vet["nome"];
    };


    $pagamento = utf8_encode($obj->pagamento);
    $sql_insert = "INSERT INTO `dedstudio12`.`bejobs_vagas` (`titulo`, `descricao`, `nome_empresa`, `profissao`, `id_empresa`, `pagamento`, `status`, `cidade`, `estado`, `bairro`,`status`) 
    VALUES ('$titulo', '$descricao', '$nome_empresa','$profissao', '$obj->id_empresa', '$pagamento','0', '$cidade', '$estado', '$bairro',1);";

    $query = $dba->query($sql_insert);

    $id_vaga = $dba->lastid();

    $image_base64 = $obj->imagem;
    if (!empty($image_base64)) {
        $file_chunks = explode(";base64,", $image_base64);
        $image_file = base64_decode($file_chunks[1]);
        file_put_contents("../images/vagas/" . $id_vaga . ".jpg", $image_file);
    };

    $array_retorno = array("success" => true, "message" => "Vaga criada com sucesso!", "id_vaga" => "$id_vaga");
} else {
    $array_retorno = array("error" => true, "message" => "parametros");
};

$json = json_encode($array_retorno);
echo $json;
