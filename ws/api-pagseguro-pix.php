 

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


function api_pagseguro_pix($nome, $email, $cnpj_cpf, $valor, $date_till_payment)
{
    $curl = curl_init(); 

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sandbox.api.pagseguro.com/orders',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
        "customer": {
            "name": "' . $nome . '",
            "email": "' . $email . '",
            "tax_id": "' . $cnpj_cpf . '" 
        }, 
        "qr_codes": [
            {
                "amount": {
                    "value": ' . $valor . '
                },
                "expiration_date": "' . $date_till_payment . '.000-03:00"
            }
        ]
    }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: EE9F2AF269B94BA08E0087E51B297617',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    $response_json = $response;
  
    curl_close($curl);
 
    return json_decode($response_json);
}


// API PAGSEGURO PIX 
$json = file_get_contents("php://input");
$obj = json_decode($json);
$array = array();
if ($obj != null) {
    #id_produto = $response_receiver->id;
    $valor = $obj->value;
    $email = $obj->email;
    $method_payment = $obj->method_payment;

    $name = $method_payment->holderName;
    $cnpj_cpf = $method_payment->cnpj_cpf;
    $data = date("Y-m-d H:i:s");

    $dateTomorrow = new DateTime($data);
    $dateTomorrow->modify('+1 hour');
    $dateTomorrow = $dateTomorrow->format("Y-m-d\TH:i:s");

    $response_receiver = api_pagseguro_pix($name, $email, $cnpj_cpf, $valor, $dateTomorrow);


    if (!$response_receiver->error_messages) {
        $qr_codes = $response_receiver->qr_codes[0];
        $copy_paste = $qr_codes->text;
        $qr_code = $qr_codes->links[0]->href; 
        $id_pagamento = $qr_codes->id; 
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
                        'PIX',
                        '$data',
                        'PAGO',
                        '$dateTomorrow',
                        '" . $response_receiver->links[2]->href . "',
                        '" . $response_receiver->links[1]->href . "',
                        '" . $response_receiver->links[0]->href . "'

                    )";
        $query = $dba->query($query_insert);

        $id = $dba->lastId();
        $array = array("success" => true, "msg" => "Pedido efetuado com sucesso!", "id" => $id, "qr_code" => $qr_code, "copia_cola" => $copy_paste,"consulta_pagamento" => $response_receiver->links[0]->href);
    } else {
        $array = array("error" => true, "msg" => "Erro em dados de cobrança!");
    }
} else {
    $array = array("error" => true, "msg" => "Parametros");
}

echo json_encode($array);
header('Content-Type: application/json');
header('Accept: application/json');
