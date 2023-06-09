<?php

/**
 * @author			Andrew Bardemaker - v 1.0 - mar/2019
 * @description 	Serviço cadastro chamado
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
include('../admin/inc/phpmailer/PHPMailerAutoload.php');


$array           = array();
$array_produtos = array();
$json  = file_get_contents('php://input');
$obj   = json_decode($json); // var_dump($obj);


if ($obj) {
    $id_usuario = $obj->id_usuario;

    $select_user = "SELECT * FROM bejobs_usuarios WHERE id=$id_usuario";
    $query = $dba->query($select_user);
    $rows = $dba->rows($query);
    if ($rows > 0) {
        $vet = $dba->fetch($query);
        $nome = $vet["nome"];
        $tipo_usuario = $vet["tipo_usuario"];
        $nascimento = $vet["nascimento"];
        $sexo = $vet["sexo"];
        $cpf = $vet["cpf"];
        $rg = $vet["rg"];
        $portfolio = $vet["portfolio"];
        $insta = $vet["instagram"];
        $face = $vet["facebook"];
        $linkedin = $vet["linkedin"];
        $endereco_res = $vet["logradouro"];
        $nro = $vet["numero"];
        $complemento = $vet["complemento"];
        $bairro = $vet["bairro"];
        $cep = $vet["CEP"];
        $cidade = $vet["cidade"];
        $estado = $vet["estado"];
        $celular = $vet["telefone_celular"];
        $profissao = $vet["profissao"];
        $email1 = $vet["email"];
        $doc_estrangeiro = $vet["doc_estrangeiro"];


        //imagem 
        if (file_exists('../images/usuarios/candidatos/' . $id_usuario . "/profile.png")) {
            $link = 'https://dedstudio.com.br/bejobs/images/usuarios/candidatos/' . $id_usuario . "/profile.png";
        } else {
            $link = "https://dedstudio.com.br/bejobs/images/perfis/default.jpg";
        };

        $array_gallery = array();
        for ($i = 1; $i <= 6; $i++) {
            if (file_exists('../images/usuarios/candidatos/' . $id_usuario . "/galeria/$i.png")) {
                array_push($array_gallery, 'https://dedstudio.com.br/bejobs/images/usuarios/candidatos/' . $id_usuario . "/galeria/$i.png");
            }
        }
        if ($tipo_usuario == 0) {
            if (empty($doc_estrangeiro)) {
                $array_detalhes[] = array(
                    "nome" => $nome,
                    "nascimento" => $nascimento,
                    "sexo" => $sexo,
                    "cpf" => $cpf,
                    "rg" => $rg,
                    "portfolio" => $portfolio,
                    "instagram" => $insta,
                    "facebook" => $face,
                    "linkedin" => $linkedin,
                    "endereco" => $endereco_res,
                    "numero" => $nro,
                    "complemento" => $complemento,
                    "bairro" => $bairro,
                    "cep" => $cep,
                    "cidade" => $cidade,
                    "estado" => $estado,
                    "celular" => $celular,
                    "email" => $email1,
                    "imagem" => $link,
                    "galerias" => $array_gallery
                );
            } else {

                $array_detalhes[] = array(
                    "nome" => $nome,
                    "nascimento" => $nascimento,
                    "sexo" => $sexo,
                    "documentoEstrangeiro" => $doc_estrangeiro,
                    "portfolio" => $portfolio,
                    "instagram" => $insta,
                    "facebook" => $face,
                    "linkedin" => $linkedin,
                    "endereco" => $endereco_res,
                    "numero" => $nro,
                    "complemento" => $complemento,
                    "bairro" => $bairro,
                    "cep" => $cep,
                    "cidade" => $cidade,
                    "estado" => $estado,
                    "celular" => $celular,
                    "email" => $email1,
                    "imagem" => $link,
                    "galerias" => $array_gallery,
                    "profissao" => $profissao
                );
            }
        } else {
            $array_detalhes[] = array(
                "nome" => $nome, 
                "endereco" => $endereco_res,
                "numero" => $nro,
                "complemento" => $complemento,
                "bairro" => $bairro,
                "cep" => $cep,
                "cidade" => $cidade,
                "estado" => $estado,
                "celular" => $celular,
                "email" => $email1,
                "imagem" => $link,
                "galerias" => $array_gallery
            );
        }

        $array = array("success" => true, "detalhes" => $array_detalhes);
    } else {
        $array = array("error" => true, "msg" => "Usuario inexistente");
    }
} else {
    $array = array("error" => true, "msg" => "Parâmetros ruins");
}

header('Content-type: application/json');
echo json_encode($array);
