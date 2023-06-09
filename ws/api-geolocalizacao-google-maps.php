<?php
#PagSeguro deve receber cartao e cobrar do mesmo , se sucedido retornar success":true}
#caso contrario retornar error":true}  
 
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

include('../admin/inc/inc.configdb.php');
include('../admin/inc/inc.lib.php');

$json = file_get_contents("php://input");
$obj = json_decode($json);
$array=array();
 
function insereGeolocalizacao($obj,$dba){ 
    if($obj != NULL){
        $id= $obj->id_pedido;
        $lat = $obj->lat;
        $long = $obj->long;

        $sql_entregador="SELECT id_entregador FROM bejobs_pedidos where id = $id";
        $query = $dba->query($sql_entregador);
        $rows = $dba->rows($query);
        if($rows > 0){
            $entregador=$dba->fetch($query);
            $id_entregador=$entregador["id_entregador"];
            $sql_entregador= "UPDATE bejobs_entregadores SET lat_entregador = $lat,long_entregador = $long where id=$id_entregador";
            $query = $dba->query($sql_entregador);
            geraGeolocation($obj,$dba,$id_entregador);
        }else{
            $array = array("error"=>true,"msg"=>"Não foi encontrado nenhum entregador para este pedido");
            echo json_encode($array);
        }
    }else{
        $array = array("error"=>true,"msg"=>"Não há dados");
        echo json_encode($array);
    } 
    header('Content-Type: application/json');  
    header('Accept: application/json'); 
}

function geraGeolocation($obj,$dba,$id_entregador){
    if($obj!=NULL){   
        $sql_entregador_lat_long = "SELECT lat_entregador,long_entregador FROM bejobs_entregadores WHERE id=$id_entregador";
        $query = $dba->query($sql_entregador_lat_long);
        $rows = $dba->rows($query);  
        if($rows > 0){
            $vet = $dba->fetch($query);
            $lat_entregador = $vet["lat_entregador"];
            $long_entregador = $vet["long_entregador"];
            $array=array("success"=>true,"msg"=>"Entregador encontrado","google_maps_link"=>"https://www.google.com/maps/place/$lat_entregador,$long_entregador");
        }else{
            $array=array("error"=>false,"msg"=>"Dados não encontrado para este ID");
        }
    }else{
        $array = array("error"=>true,"msg"=>"Não há dados");
    }
    echo json_encode($array);
    header('Content-Type: application/json');  
    header('Accept: application/json'); 
}

insereGeolocalizacao($obj,$dba);