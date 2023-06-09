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
$array_resultados = array();

$id=$_GET['id'];
if($id != NULL){ 
    $query_consulta="SELECT  * FROM bejobs_pedidos where id=".$id;
    $query=$dba->query($query_consulta);
    $rows=$dba->rows($query);
    if($rows>0){
        for($i=0;$i<$rows;$i++){
            $vet=$dba->fetch($query);  

            $array_resultados[]=$vet; 
        } 
    }
}else{
    $array= array("error"=>true,"msg"=>"Parâmetros ruins");
}

$array = array("success" => true, "resultados" => $array_resultados);


header('Content-type: application/json');
echo json_encode($array);
