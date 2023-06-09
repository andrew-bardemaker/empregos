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

$id=$_GET["id"]; 
if($id!=NULL){  
    $query_consulta="SELECT  * FROM bejobs_pedidos where id_usuario=".$id;
    $query=$dba->query($query_consulta);
    $rows=$dba->rows($query);
    if($rows>0){
        for($i=0;$i<$rows;$i++){
            $vet=$dba->fetch($query); 
            $id= $vet["id"];
            $dest_nome=$vet["dest_nome"];
            $produto=$vet["produto"];
            $status=$vet["status"];
            $modalidade = $vet["modalidade"];
            $descricao = $vet["description"];
            $tempo_estimado = $vet["tempo_estimado"];
            $horario_pedido = date("h:i:sa");
            $array_produtos[]=array("id_pedido"=>$id,"dest_nome"=>$dest_nome,"produto"=>$produto,"status"=>$status,"modalidade"=>$modalidade,"descricao"=>$descricao,"tempo_estimado"=>$tempo_estimado,"horario_pedido"=>$horario_pedido);        
            $total_pedido+=$vet["total_pedido"];
        }
    }
    

    $array = array("success" => true, "produtos" => $array_produtos, "total" => $total_pedido);
}else{
    $array= array("error"=>true,"msg"=>"Parâmetros ruins");
} 


header('Content-type: application/json');
echo json_encode($array);
