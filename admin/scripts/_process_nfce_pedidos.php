<?php
include('../inc/inc.configdb.php');
include('../inc/inc.lib.php');

// Busca registros de pedidos que não tenham NFCe e ques status = Pedido Entregue
$sql2 = "SELECT 
		   id, 
		   data_hora_registro, 
		   endereco, 
		   numero, 
		   complemento, 
		   bairro, 
		   cep, 
		   cidade, 
		   uf, 
		   status, 
		   total_pedido, 
		   id_usuario, 
		   observacoes, 
		   observacoes_cancelamento,
		   rede_reference,
		   rede_tid,
		   rede_nsu,
		   rede_authorization_code,
		   rede_date_time,
		   rede_amount,
		   rede_card_bin,
		   rede_last4,
		   rede_brand_tid,
		   id_entregadores
		FROM bejobs_pedidos 
		WHERE status_nfce = 0 
		AND status = 4";
$query2 = $dba->query($sql2);
$qntd2  = $dba->rows($query2);
if ($qntd2 > 0) {
	for ($i=0; $i<$qntd2; $i++) {
		$vet2               = $dba->fetch($query2);

		$id_pedido 			= $vet2[0];
		// $data_hora_registro = dataMY(datetime_date_ptbr($vet2[1]))."T".datetime_time_full_ptbr($vet2[1]);
		$data_hora_registro = date("Y-m-d")." ".date("H:i:s");
		
		$endereco 			= $vet2[2];
		$numero 			= $vet2[3];
		$complemento 		= $vet2[4];
		$bairro 			= $vet2[5];
		$cep 				= $vet2[6];
		$cidade 			= $vet2[7];
		$uf 				= $vet2[8];	
		$total_pedido 		= $vet2[10];
		$observacoes 		= $vet2[12];	
		$observacoes_cancelamento = $vet2[13];	

		$rede_reference = $vet2[14];
		$rede_tid 		= $vet2[15];
		$rede_nsu 		= $vet2[16];
		$rede_authorization_code = $vet2[17];
		$rede_date_time = $vet2[18];
		$rede_amount 	= $vet2[19];
		$rede_card_bin 	= $vet2[20];
		$rede_last4 	= $vet2[21];
		$rede_brand_tid = $vet2[22];
	
		$id_usuario = $vet2[11];

		$nfe 			= array();	
		$array_produtos = array();

		// Destinatários
		$sql1 = "SELECT u.id, u.nome, u.cpf, u.telefone_celular, u.data_cadastro, u.email, u.nascimento, u.status
		         FROM bejobs_usuarios AS u
		         WHERE u.id = $id_usuario";
		$query1 = $dba->query($sql1);
		$vet1   = $dba->fetch($query1);
		$nome_destinatario = $vet1[1];
		$cpf_destinatario  = $vet1[2];
		$tel_destinatario  = $vet1[3];

		$numero_item = 1;

		$sql5   = "SELECT b.codigo, 
				   b.codigo_ncm, 
				   b.cfop, 
				   b.icms_origem, 
				   b.icms_situacao_tributaria, 
				   a.valor_produto, 
				   a.qntd, 
				   a.titulo_produto, 
				   b.icms_aliquota
				   FROM bejobs_pedidos_produtos AS a
				   INNER JOIN bejobs_produtos AS b
				   WHERE a.id_produto = b.id
				   AND a.id_pedido = $id_pedido";
		// print_r($sql5);
		$query5 = $dba->query($sql5);
		$qntd5  = $dba->rows($query5);
		if ($qntd5 > 0) {
			for ($j=0; $j<$qntd5; $j++) {
				// $tpl->newBlock('produtos');
				$vet5     = $dba->fetch($query5);
				$codigo_produto           = $vet5[0];
				$codigo_ncm               = $vet5[1];
				$cfop      			      = $vet5[2];
				$icms_origem     	 	  = $vet5[3];
				$icms_situacao_tributaria = $vet5[4];
				$valor_produto 		      = $vet5[5];
				$qntd 			     	  = $vet5[6];
				$valor_total_produto 	  = number_format($vet5[5]*$vet5[6], 2, '.', '');
				$titulo_produto  	 	  = $vet5[7];
				$icms_aliquota       	  = $vet5[8];

				$array_produtos[] = array(
										"numero_item" 		     	=> $numero_item,
										"codigo_ncm" 				=> $codigo_ncm,
										"codigo_produto" 			=> $codigo_produto,
										"descricao" 				=> $titulo_produto,
										// "descricao" 				=> "NOTA FISCAL EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL", // Homologação
										"quantidade_comercial" 		=> $qntd,
										"quantidade_tributavel" 	=> $qntd,
										"cfop" 						=> $cfop,
										"valor_unitario_comercial" 	=> $valor_produto,
										"valor_unitario_tributavel" => $valor_produto,
										"valor_bruto" 				=> $valor_total_produto,
										"unidade_comercial" 		=> "un",									
										"unidade_tributavel" 		=> "un",								
										"icms_origem" 				=> $icms_origem,
										"icms_situacao_tributaria" 	=> $icms_situacao_tributaria,
										"icms_aliquota"				=> $icms_aliquota										
										// "pis_situacao_tributaria" 	 => "07",
										// "cofins_situacao_tributaria" => "07"
									);

				$numero_item = $numero_item+1;
			}
		}	
			

		// Você deve definir isso globalmente para sua aplicação.
		// Para ambiente de produção utilize e a variável abaixo:

		$url = "https://api.focusnfe.com.br"; // URL Produção
		// $url   = "https://homologacao.focusnfe.com.br"; // URL Homologação

		// Substituir a variável, ref, pela sua identificação interna de nota.
		$ref   = 'bejobsapp_'.$id_pedido;
		// $login = "nF7fmMfsqIO6oQo3s07ady6IMqDSCUG0"; // Token Homologação
		$login = "e4rzde7ujoBQRGcmGcGIPv2P07W2HhLv"; // Token Produção
		$pass  = "";

		$nfe = array (
			"natureza_operacao" 			=> "VENDA AO CONSUMIDOR",
			"presenca_comprador"			=> "4", // 1 – Operação presencial. 4 – Entrega a domicílio.
			"data_emissao" 					=> $data_hora_registro, 
			"informacoes_adicionais_contribuinte" => "DOCUMENTO EMITIDO POR EMPRESA DO SIMPLES NACIONAL.",

			"nome_destinatario" 			=> $nome_destinatario,
			// "nome_destinatario" 			=> "NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL", // Homologação
			"cpf_destinatario"				=> $cpf_destinatario,
			"telefone_destinatario" 		=> $tel_destinatario,
			"logradouro_destinatario" 		=> $endereco,
			"numero_destinatario" 			=> $numero,
			"bairro_destinatario" 			=> $bairro,
			"municipio_destinatario" 		=> $cidade,
			"uf_destinatario"				=> $uf,
			"pais_destinatario"		    	=> "Brasil",
			"cep_destinatario" 				=> $cep,

			"cnpj_emitente" 				=> "37442532000199",
			// "nome_emitente" 				=> "NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL",  // Homologação
			// "nome_fantasia_emitente" 		=> "bejobs APP",
			// "logradouro_emitente" 			=> "Avenida Carlos Gomes",
			// "numero_emitente" 				=> "2012",
			// "complemento_emitente"			=> "Sala 604",
			// "bairro_emitente" 				=> "Três Figueiras",
			// "municipio_emitente" 			=> "Porto Alegre",
			// "uf_emitente" 					=> "RS",
			// "cep_emitente"			    	=> "90480002",
			// "inscricao_estadual_emitente" 	=> "0963821229",
			"valor_frete" 					=> "0.0",
			"valor_seguro" 					=> "0",
			"valor_total" 					=> $total_pedido,
			"valor_produtos"				=> $total_pedido,
			"modalidade_frete" 				=> "0",

			"formas_pagamento" => array( array(
		         "forma_pagamento"    => "03", // 03: Cartão de Crédito
		         "valor_pagamento"    => $total_pedido,
		         "tipo_integracao"	  => "2", // 2: Pagamento não integrado com o sistema de automação da empresa (valor padrão).
		         "bandeira_operadora" => "99" // 99: Outros.
		    )),

		  	"items" => $array_produtos,

		    "cnpj_transportador" 			   => "37442532000199",
		    "nome_transportador" 			   => "bejobs APP COMERCIO E DISTRIBUIÇAO DE BEBIDAS LTDA",
		    "inscricao_estadual_transportador" => "0963821229",
		    "endereco_transportador" 		   => "Avenida Carlos Gomes, 2012, Sala 604,Três Figueiras",
		    "municipio_transportador" 		   => "Porto Alegre",
		    "uf_transportador" 				   => "RS"
		);

		// echo json_encode($nfe);
		// exit;

		// Inicia o processo de envio das informações usando o cURL.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url."/v2/nfce?ref=".$ref);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfe));
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
		$result = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		// As próximas três linhas são um exemplo de como imprimir as informações de retorno da API.
		// print($http_code."\n");
		// print($result."\n\n");
		// print("");
		// exit;

		$obj = json_decode($result); 
		// echo $obj;
		// exit;
		// print_r($obj);

		if ($obj->status == "autorizado" && $obj->status_sefaz == "100") {
			$cnpj_emitente 			 = $obj->cnpj_emitente;
			$ref 					 = $obj->ref;
			$status_titulo			 = $obj->status;
			$status_sefaz 			 = $obj->status_sefaz;
			$mensagem_sefaz 		 = $obj->mensagem_sefaz;
			$chave_nfe               = $obj->chave_nfe;
			$numero 				 = $obj->numero;
			$serie 					 = $obj->serie;
			$caminho_xml_nota_fiscal = $url.$obj->caminho_xml_nota_fiscal;
			$caminho_danfe 			 = $url.$obj->caminho_danfe;

			$qrcode_url = "";
			if (isset($obj->qrcode_url)) {
				$qrcode_url = $obj->qrcode_url;
			}

			$url_consulta_nf = "";
			if (isset($obj->url_consulta_nf)) {
				$url_consulta_nf = $obj->url_consulta_nf;
			}
			

			$sql1 = "UPDATE bejobs_pedidos 
					 SET 
					 nf_cnpj_emitente='$cnpj_emitente', 
					 nf_ref='$ref', 
					 nf_status_titulo='$status_titulo', 
					 nf_status_sefaz='$status_sefaz', 
					 nf_mensagem_sefaz='$mensagem_sefaz', 
					 nf_chave_nfe='$chave_nfe', 
					 nf_numero='$numero', 
					 nf_serie='$serie', 
					 nf_caminho_xml_nota_fiscal='$caminho_xml_nota_fiscal', 
					 nf_caminho_danfe='$caminho_danfe', 
					 nf_qrcode_url='$qrcode_url', 
					 nf_url_consulta_nf='$url_consulta_nf',
					 status_nfce=1
					 WHERE id = '$id_pedido'";
			$dba->query($sql1);

		} else {

			$cnpj_emitente 	= $obj->cnpj_emitente;
			$ref 			= $obj->ref;
			$status 		= $obj->status;
			$status_sefaz 	= $obj->status_sefaz;
			$mensagem_sefaz = $obj->mensagem_sefaz;

			$titulo = "Processamento NFC-e Pedido #".$id_pedido;
			$texto  = "CNPJ emitente: ".$cnpj_emitente." <br> REF: ".$ref." <br> Status: ".$status." <br> Status SEFAZ: ".$status_sefaz." <br> Mensagem SEFAZ: ".$mensagem_sefaz;

			$sql1 = "INSERT INTO bejobs_notificacoes_admin (data_registro, titulo, texto, status) VALUES (NOW(), '$titulo', '$texto', 1)";
			$dba->query($sql1);

		}	
	}
}

?>