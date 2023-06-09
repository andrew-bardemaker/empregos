<?php
/**
 * @author			Andrew Bardemaker - v 1.0 - mar/2019
 * @description 	Serviço de login usuário
 * @params

 */
 
// session_start();
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

include('../admin/inc/inc.configdb.php');
include('../admin/inc/inc.lib.php');
 
function call_Google_API($address1,$address2,$mode){  
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => str_replace(" ","%20","https://maps.googleapis.com/maps/api/distancematrix/json?origins='".$address1."'&destinations='".$address2."'&mode='DRIVING'&language=PT-BR&key=AIzaSyDUuBlqIKA0CXYThZKD5FHoTJZAr4Upcw8"),
     CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);  
    $response_json = json_decode($response);
    curl_close($curl);   
    return $response_json; 
}

function build_address($address){
    return $address->{'address'}.','.$address->{'number'}.'-'.$address->{'district'}.','.$address->{'city'}.'-'.$address->{'uf'};
}

$array = array();

$json  = file_get_contents('php://input'); 
$obj   = json_decode($json);
/* Insira aqui o codigo */ 
if($obj!=null){         
    
    $address_Sender_data=$obj->{'senderData'};
    $address_Receiver_data=$obj->{'recipientData'};
    $tipo=$obj->{'deliveryInfo'}->{'modality'};  
    if($tipo!=null){
        $sql= "SELECT * FROM bejobs_metricas where id=$tipo";
    } 
    else{
        $sql=  "SELECT * FROM bejobs_metricas ";
    }
    $query = $dba->query($sql);
	$qntd  = $dba->rows($query);
    if($qntd>0){
		$vet = $dba->fetch($query);
        $multiplicador_km=$vet['valor'];
    }  
    $address_Sender=build_address($address_Sender_data);
    $address_Receiver= build_address($address_Receiver_data);
   
    $return_km = call_Google_API($address_Sender,$address_Receiver,'DRIVING','PT-BR');
    $km_value = $return_km->{'rows'}[0]->{'elements'}[0]->{'distance'}->{'value'}/1000;
    #Formatando para duas casas decimais
    $km_value= number_format($km_value,2);

    #Valor final de metricas
    $final_metric = $km_value * $multiplicador_km;
    #Formatando valor calculado para duas casas decimais
    $final_metric = number_format($final_metric,2);
    $array= array("success"=>true,"distance"=>$km_value,"value"=>$final_metric);
}else{
    $array = array("error" => true, "msg" => "Parametros");
}
echo json_encode($array);
header('Content-Type: application/json');  