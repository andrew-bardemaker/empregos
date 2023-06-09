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
    $id_vaga = $obj->id_vaga;
    $sql = "SELECT bejobs_candidaturas.data_candidatura,bejobs_usuarios.nome,bejobs_usuarios.id,bejobs_usuarios.profissao, bejobs_usuarios.portfolio FROM `bejobs_candidaturas` 
            INNER JOIN 
                `bejobs_usuarios` ON bejobs_usuarios.id = bejobs_candidaturas.id_usuario
                
            WHERE 
                    bejobs_candidaturas.id_vaga=$id_vaga";
    $query = $dba->query($sql);
    $qntd = $dba->rows($query);

    if ($qntd > 0) {
        for ($i = 0; $i < $qntd; $i++) {
            $vet = $dba->fetch($query);
            $nome = $vet["nome"];
            $data_candidatura = $vet["data_candidatura"];
            $id = $vet["id"];
            $portfolio = $vet["portfolio"];
            $profissao = $vet["profissao"];

            //imagem
            if (file_exists('../images/usuarios/candidatos/'.$id . "/profile.png")) {
                $link = 'https://dedstudio.com.br/bejobs/images/usuarios/candidatos/'.$id . "/profile.png";
            } else {
                $link = "https://dedstudio.com.br/bejobs/images/perfis/default.png";
            };


            $candidatos[] = array(
                "id" => utf8_encode($id),
                "nome" => utf8_encode($nome),
                "portfolio" => utf8_encode($portfolio), 
                "profissao" => utf8_encode($profissao), 
                "data_candidatura" => dataBR($data_candidatura),
                "img" => $link

            );
        }
    }

    $array = array("success" => "true", "candidatos" => $candidatos);
} else {
    $array = array("success" => "true", "candidatos" => NULL);
}
header('Content-type: application/json');
echo json_encode($array);
