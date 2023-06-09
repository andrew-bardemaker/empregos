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
        $especialidade = $userData->especialty; 
        $data_cadastro    = date('Y-m-d H:i:s');
        $ip_cadastro      = getIp();
        $id = $obj->id;

        $senha_md5 = md5($senha);

        if ($is_foreign) {
            $sql_update = "UPDATE bejobs_usuarios SET 
                                  nome='$nome', 
                                  nascimento='$nascimento', 
                                  sexo = '$sexo', 
                                  doc_estrangeiro= '$internationalDoc', 
                                  email='$email',    
                                  telefone_celular = '$telefone_celular', 
                                  complemento = '$complemento', 
                                  numero = '$numero', 
                                  bairro = '$bairro',
                                  estado = '$estado',  
                                  cidade = '$cidade', 
                                  cep = '$cep', 
                                  ip_cadastro = '$ip_cadastro',
                                  data_cadastro = '$data_cadastro' ,
                                  tipo_usuario = '$user_type', 
                                  linkedin = '$linkedin' ,
                                  instagram = '$insta' ,
                                  facebook = '$face' ,
                                  profissao = '$profissao' 
                            WHERE id = $id";
        } else {
            $sql_update = "UPDATE bejobs_usuarios SET 
                                  nome='$nome',
                                  nascimento='$nascimento', 
                                  sexo = '$sexo', 
                                  cpf= '$cpf', 
                                  rg= '$rg', 
                                  email='$email',    
                                  telefone_celular = '$telefone_celular', 
                                  complemento = '$complemento', 
                                  numero = '$numero', 
                                  bairro = '$bairro',
                                  estado = '$estado',  
                                  cidade = '$cidade', 
                                  cep = '$cep', 
                                  ip_cadastro = '$ip_cadastro',
                                  data_cadastro = '$data_cadastro' ,
                                  tipo_usuario = '$user_type', 
                                  linkedin = '$linkedin' ,
                                  instagram = '$insta' ,
                                  facebook = '$face' ,
                                  profissao = '$profissao'  
                            WHERE id = $id";
        }
        $dba->query($sql_update); 
   
        $array = array("success" => true, "id_usuario" => $id);
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

        $sql_insert = "UPDATE bejobs_usuarios 
                                  SET nome = '$nome',   
                                    razao_social = '$razao_social',
                                    cnpj = '$cnpj',
                                    email = '$email',
                                    telefone_celular = '$telefone_celular',  
                                    logradouro = '$logradouro',
                                    complemento = '$complemento',
                                    numero = '$numero',
                                    bairro = '$bairro',
                                    estado = '$estado',
                                    cidade = '$cidade',
                                    cep = '$cep', 
                                    ip_cadastro = '$ip_cadastro',
                                    data_cadastro = '$data_cadastro',
                                    tipo_usuario =  '$user_type'
                                WHERE
                                    id = $id        
                                  ";

        $dba->query($sql_insert); 
    }
    $array = array("success" => true, "id_usuario" => $id);
} else {
    $array = array("error" => false, "type" => "parametros", "msg" => "Parâmetros inválidos.");
}
header('Content-type: application/json');
echo json_encode($array);
