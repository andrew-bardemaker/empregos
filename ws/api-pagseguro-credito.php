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


function api_pagseguro_call_credit_create(
    $desc,
    $val,
    $num,
    $exp_month,
    $exp_year,
    $cvv,
    $nome,
    $vezes)
{

  $curl = curl_init();
  
   
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://sandbox.api.pagseguro.com/charges',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',  
    CURLOPT_POSTFIELDS =>'{ 
      "description": "' . $desc . '",
      "amount": {
        "value": "' . $val . '", 
        "currency": "BRL"
      },
      "payment_method": {
        "type": "CREDIT_CARD",
        "installments":'.$vezes.',
        "capture": true,
        "soft_descriptor": "BeJobs Co.",    
        "card": {
          "number": "' . $num . '",
          "exp_month": "' . $exp_month . '",
          "exp_year": "' . $exp_year . '",
          "security_code": "' . $cvv . '",
          "holder": {
            "name": "' . $nome . '"
          }
        }
      } 
    }',
    #Provavelmente será necessário, para envio de debito 
    CURLOPT_HTTPHEADER => array(
      'Authorization: EE9F2AF269B94BA08E0087E51B297617',
      'Accept: application/json',
      'Content-type: application/json'
    ),
  ));

  $response = curl_exec($curl);
  $response_json = $response; 
  curl_close($curl); 
  return json_decode($response_json);
}

function api_pagseguro_call_credit_capture($amount, $charge_id)
{
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://sandbox.api.pagseguro.com/charges/$charge_id/capture",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => "{'amount': {
                                'value':$amount
                            }
                          }
                        }",
    #Provavelmente será necessário, para envio de debito 
    CURLOPT_HTTPHEADER => array(
      'Authorization: DDA1A0D94141C99664DF6F8484FC2BBB',
      'Accept: application/json',
      'Content-type: application/json'
    )
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
  $descricao = $obj->description;
  $value = $obj->value;
  $method_payment = $obj->method_payment; 
  #Areas de tipo de cobranca 
  $number = $method_payment->number;
  $exp_month = $method_payment->exp_month;
  $exp_year = $method_payment->exp_year;
  $cvv = $method_payment->cvv;
  $holder_name = $method_payment->holderName; 
  $vezes = $method_payment->vezes;
 
  #Recebe a resposta 
  $response_receiver = api_pagseguro_call_credit_create(
    $descricao, 
    $value, 
    $number,
    $exp_month,
    $exp_year,
    $cvv,
    $holder_name,
    $vezes
  );
  

  if (!$response_receiver->error_messages) {
    #id_produto = $response_receiver->id;
    $recipient_info = $obj->recipientData;
    $sender_info = $obj->senderData;
    $total_pedido = $response_receiver->amount->value;
    $id_pagamento = $response_receiver->id;
    $data = date("Y-m-d H:i:s");
 
    $dateTomorrow = new DateTime($data);
    $dateTomorrow->modify('+1 day');
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
            link_cancelamento,
            link_self)
      VALUES( 
          '$id_pagamento',
          'CREDITO',
          '$data',
          'PAGO',
          '$dateTomorrow',
          '" . $response_receiver->links[2]->href . "',
          '" . $response_receiver->links[1]->href . "',
          '" . $response_receiver->links[0]->href . "'

      )"; 
    $query = $dba->query($query_insert);

    $id = $dba->lastId();
    $array = array("success" => true, "msg" => "Pedido efetuado com sucesso!", "id" => $id,"status"=>$status);
  } else {
    $array = array("error" => true, "msg" => "Erro em dados de cobrança!");
  }
} else {
  $array = array("error" => true, "msg" => "Parametros");
}
echo json_encode($array);
header('Content-Type: application/json');
header('Accept: application/json');
