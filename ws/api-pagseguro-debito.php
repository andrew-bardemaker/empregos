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

function api_pagseguro_call_debit($desc,$valor, $nro_cartao, $exp_month, $exp_year, $cvv, $holder_name)
{ 
    $curl = curl_init(); 
    
    $json='{  
        "description":"$desc",
        "amount":{
            "value":'.$valor.',
            "currency":"BRL"
        },
        "payment_method":{
            "type":"DEBIT_CARD",
            "card":{
                "number":"'.$nro_cartao.'",
                "exp_month":"'.$exp_month.'",
                "exp_year":"'.$exp_year.'",
                "security_code":"'.$cvv.'",
                "holder":{
                    "name":"'.$holder_name.'"
                }
            },
            "authentication_method":{
                "type":"THREEDS",
                "cavv":"BwABBylVaQAAAAFwllVpAAAAAAA=",
                "xid":"BwABBylVaQAAAAFwllVpAAAAAAA=",
                "eci":"05",
                "version":"2.1.0",
                "dstrans_id":"DIR_SERVER_TID"
            }
        }
    }';   

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sandbox.api.pagseguro.com/charges',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $json ,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer EE9F2AF269B94BA08E0087E51B297617',
            'Content-Type: application/json'
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
if ($obj != null) {

    #INFO PASSADA PELO COLEGA 
    $value = $obj->value;
    $method_payment = $obj->method_payment;
    $id_user = $obj->id_user;
    #Areas de tipo de cobranca 
    $number = $method_payment->number;
    $exp_month = $method_payment->exp_month;
    $exp_year = $method_payment->exp_year;
    $cvv = $method_payment->cvv;
    $holder_name = $method_payment->holderName;

    #Recebe a resposta 
    $response_receiver = api_pagseguro_call_debit(
        $descricao,
        $value,
        $number,
        $exp_month,
        $exp_year,
        $cvv,
        $holder_name
    ); 

    if (!$response_receiver->error_messages) {
        #id_produto = $response_receiver->id;
        $total_pedido = $response_receiver->amount->value;
        $id_pagamento = $response_receiver->id;
        $data = date("Y-m-d H:i:s");

        $dateTomorrow = new DateTime($data);
        $dateTomorrow->modify('+7 day');
        $dateTomorrow = $dateTomorrow->format("Y-m-d H:i:s");

        $status = $response_receiver->status;
        if($status == "PAID"){
            $status="PAGO";
        }else{
            $status = "EM ESPERA";
        }

        $query_insert = "INSERT INTO bejobs_pagamentos ( 
                id_criado_pagseguro,
                tipo_de_pagamento,
                data_criacao,
                status,
                data_limite,
                link_pagamento,
                link_self,
                link_cancelamento)
          VALUES( 
              '$id_pagamento',
              'DEBITO',
              '$data',
              '$status',
              '$dateTomorrow',
              '".$response_receiver->links[2]->href."',
              '".$response_receiver->links[0]->href."',
              '".$response_receiver->links[1]->href."'
    
          )";
        $query = $dba->query($query_insert);

        $id = $dba->lastId();
        $array = array("success" => true, "msg" => "Pedido efetuado com sucesso!", "id" => $id, "id_pagamento" => $id_pagamento,"status"=>$status);
    } else {
        $array = array("error" => true, "msg" => $response_receiver->error_messages[0]); 
    }
} else {
        $array = array("error" => true, "msg" => "Erro em dados de cobrança!");
}
echo json_encode($array);
header('Content-Type: application/json');
header('Accept: application/json');
