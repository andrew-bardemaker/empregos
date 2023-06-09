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

function api_capture_pix($order_id)
{

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $order_id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Authorization: EE9F2AF269B94BA08E0087E51B297617'
    ),
  ));

  $response = curl_exec($curl);
  $response_json = $response;

  curl_close($curl);
  return json_decode($response_json);
}


$json = file_get_contents("php://input");
$obj = json_decode($json);
$array = array();
if($obj){
  #Recebe a resposta 
  $response_receiver = api_capture_pix($obj->link);
  
  $charges = $response_receiver->charges[0];
  $status = $charges->status;
  
  if($status=="PAID"){
    $pago = true;
  }else{
    $pago = false;
  }
  
  $array = array("success" => true, "pago" => $pago);
}else{
  $array = array("error" => true, "message" => "Link faltando");
} 


echo json_encode($array);
header('Content-Type: application/json');
header('Accept: application/json');
