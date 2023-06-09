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

if ($obj) {
    $acentuacoes = array('á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ç' => 'c');
    $profissao = $obj->profissao;
    $profissao = str_replace(
        array('á', 'à', 'ã', 'â', 'ä', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î', 'ï', 'ó', 'ò', 'õ', 'ô', 'ö', 'ú', 'ù', 'û', 'ü', 'ç', 'ñ'),
        array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'c', 'n'),
        $profissao
    ); // string sem acentos
    if (empty($profissao)) {
        $query_bairros = "SELECT * FROM bejobs_profissoes";
        $query = $dba->query($query_bairros);
        $rows = $dba->rows($query);
        if ($rows > 0) {
            for ($i = 0; $i < $rows; $i++) {
                $vet = $dba->fetch($query);

                $profissoes[] = utf8_encode($vet["profissao"]);
            }
            $array = array("success" => true, "profissoes" => $profissoes);
        } else {
            $array = array("success" => true, "profissoes" => NULL);
        }
    } else {
        $query_bairros = "SELECT * FROM bejobs_profissoes WHERE profissao='$profissao'";
        $query = $dba->query($query_bairros);
        $rows = $dba->rows($query);
        if ($rows > 0) {
            for ($i = 0; $i < $rows; $i++) {
                $vet = $dba->fetch($query);

                $especialidades[] = utf8_encode($vet["especialidade"]);
            }
            $array = array("success" => true, "especialidades" => $especialidades);
        } else {
            $array = array("success" => true, "especialidades" => NULL);
        }
    }
} else {
    $array = array("success" => true, "profissoes" => array());
}
header('Content-type: application/json');
echo json_encode($array);
