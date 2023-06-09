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
    $user_type = $obj->userType;
    $is_foreign = $obj->estrangeiro;
    $userData = $obj->userData;

    if ($user_type == 0) {
        $nome = $userData->nome;
        $nascimento = $userData->nascimento;
        $sexo = $userData->sexo;
        $email = $userData->email;
        $phone = $userData->phone;
        $address = $userData->address;
        $complement = $userData->complement;
        $number = $userData->number;
        $location = $userData->location;
        $internationalDoc = $userData->docInternacional;
        $cpf = $userData->CPF;
        $rg = $userData->RG;
        $cep = $userData->CEP;
        $portfolio = $userData->portfoil;
        $face = $userData->facebook;
        $insta = $userData->instagram;
        $linkedin = $userData->linkedin;
        $state = $userData->state;
        $city = $userData->city;
        $profissao = $userData->profession; 
        $senha = $userData->senha;
        $data_cadastro    = date('Y-m-d H:i:s');
        $ip_cadastro      = getIp();

        $senha_md5 = md5($senha);

        if ($is_foreign) {
            $sql_insert = "INSERT INTO bejobs_usuarios 
                                  ( nome,   nascimento,   sexo,    doc_estrangeiro  , email,    telefone_celular,      complemento,    numero,   bairro,    estado,  cidade, cep, senha,ip_cadastro,data_cadastro,tipo_usuario,linkedin,instagram,facebook,profissao)
                            VALUES('$nome','$nascimento','$sexo', '$internationalDoc','$email','$telefone_celular', '$complemento', '$numero', '$bairro','$estado','$cidade','$cep','$senha_md5','$ip_cadastro','$data_cadastro','$user_type','$linkedin','$insta','$face','$profissao')";
        } else {
            $sql_insert = "INSERT INTO bejobs_usuarios 
                                  ( nome,   nascimento,   sexo,     cpf,  rg,   email,    telefone_celular,       complemento,    numero,   bairro,    estado,  cidade,    cep,     senha,    ip_cadastro,   data_cadastro,  tipo_usuario,  linkedin,  instagram, facebook ,profissao)
                            VALUES('$nome','$nascimento','$sexo', '$cpf','$rg','$email',  '$telefone_celular',  '$complemento', '$numero', '$bairro','$estado','$cidade','$cep','$senha_md5','$ip_cadastro','$data_cadastro','$user_type','$linkedin','$insta','$face','$profissao')";
        }  

        // echo $sql_insert;
        // exit;

        $dba->query($sql_insert);

        //Alterações para id MD5 de usuario a fins de guardar dados
        $id_inserido= $dba->lastId();
        $md5_id= md5($id_inserido.$nome);
        $update = "UPDATE bejobs_usuarios SET token = '$md5_id' WHERE id = $id_inserido";
         
        $dba->query($update);
 
        
        mkdir("../images/usuarios/candidatos/" . $id_inserido);
        $default = "../images/usuarios/candidatos/default.png";
        $profile_default = "../images/usuarios/candidatos/" . $id_inserido . "/";
        rename($default, $profile_default . basename($default));


        mkdir("../images/usuarios/candidatos/" . $id_inserido . "/galeria");

        $perfil = $userData->profile;
        if (!empty($perfil)) {
            $file_chunks = explode(";base64,", $perfil);
            $image_file = base64_decode($file_chunks[1]);
            file_put_contents($profile_default . "profile.png", $image_file);
        }

        $gallery = $userData->gallery;
        $idx = 1;
        foreach ($gallery as $image) {
            if (!empty($image)) {
                $file_chunks = explode(";base64,", $image);
                $image_files = base64_decode($file_chunks[1]);
                file_put_contents($profile_default . "/galeria/" . $idx . ".png", $image_files);
                $idx += 1;
            }
        }
        $array = array("success" => true, "id_usuario" => $id_inserido);
    } else {
        $nome = $userData->nome;
        $razao_social = $userData->razao_social; 
        $cnpj = $userData->CNPJ;
        $email = $userData->email;
        $senha = $userData->senha;
        $telefone_celular = $userData->phone;
        $address = $userData->address;
        $complemento = $userData->complement;
        $number = $userData->number;
        $logradouro = $userData->location;
        $cep = $userData->cep;
        $estado = $userData->state;
        $cidade = $userData->city;

        $data_cadastro    = date('Y-m-d H:i:s');
        $ip_cadastro      = getIp();

        $senha_md5 = md5($senha);

        $sql_insert = "INSERT INTO bejobs_usuarios 
                                  ( nome,   
                                    razao_social,
                                    cnpj,
                                    email,
                                    telefone_celular,  
                                    logradouro,
                                    complemento,
                                    numero,
                                    bairro,
                                    estado,
                                    cidade,
                                    cep,
                                    senha,
                                    ip_cadastro,
                                    data_cadastro,
                                    tipo_usuario)
                            VALUES('$nome',
                                   '$razao_social',
                                   '$cnpj',
                                   '$email',
                                   '$telefone_celular',
                                   '$logradouro',
                                   '$complemento',
                                   '$numero',
                                   '$bairro',
                                   '$estado',
                                   '$cidade',
                                   '$cep',
                                   '$senha_md5',
                                   '$ip_cadastro',
                                   '$data_cadastro',
                                   '$user_type')";

        $dba->query($sql_insert); 
        $id_inserido= $dba->lastId();
    
        mkdir("../images/usuarios/empresas/" . $id_inserido);
        $default = "../images/usuarios/empresas/default.png";
        $profile_default = "../images/usuarios/empresas/" . $id_inserido . "/";
        rename($default, $profile_default . basename($default));

        //Alterações para id MD5 de usuario a fins de guardar dados 
        $md5_id= md5($id_inserido.$nome);
        $update = "UPDATE bejobs_usuarios SET token = '$md5_id' WHERE id = $id_inserido";
        $dba->query($update);

        mkdir("../images/usuarios/empresas/" . $id_inserido . "/galeria");

        $perfil = $userData->profile;
        if (!empty($perfil)) {
            $file_chunks = explode(";base64,", $perfil);
            $image_file = base64_decode($file_chunks[1]);
            file_put_contents($profile_default . "profile.png", $image_file);
        }
    }
    $array = array("success" => true, "id_usuario" => $id_inserido);
} else {
    $array = array("error" => false, "type" => "parametros", "msg" => "Parâmetros inválidos.");
}
header('Content-type: application/json');
echo json_encode($array);
