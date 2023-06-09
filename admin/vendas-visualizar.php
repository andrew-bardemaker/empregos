<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/vendas-visualizar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 18;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

$sql2   = "SELECT 
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
		   id_entregadores,
		   nf_cnpj_emitente,
		   nf_ref,
		   nf_status_titulo,
		   nf_status_sefaz,
		   nf_mensagem_sefaz,
		   nf_chave_nfe,
		   nf_numero,
		   nf_serie,
		   nf_caminho_xml_nota_fiscal,
		   nf_caminho_danfe,
		   nf_qrcode_url,
		   nf_url_consulta_nf,
		   status_nfce
		   FROM bejobs_pedidos 
		   WHERE id = $id";
$query2 = $dba->query($sql2);
$qntd2  = $dba->rows($query2);
if ($qntd2 > 0) {
	$vet2 = $dba->fetch($query2);
	$tpl->assign('id_pedido', $vet2[0]);
	$tpl->assign('data_hora_registro', datetime_date_ptbr($vet2[1])." - ".datetime_time_ptbr($vet2[1]));
	$tpl->assign('endereco', $vet2[2]);
	$tpl->assign('numero', $vet2[3]);
	$tpl->assign('complemento', $vet2[4]);
	$tpl->assign('bairro', $vet2[5]);
	$tpl->assign('cep', $vet2[6]);
	$tpl->assign('cidade', $vet2[7]);
	$tpl->assign('uf', $vet2[8]);	
	$tpl->assign('total_pedido', moeda($vet2[10]));
	$tpl->assign('observacoes', $vet2[12]);	
	$observacoes_cancelamento = $vet2[13];	

	$tpl->assign('rede_reference', $vet2[14]);
	$tpl->assign('rede_tid', $vet2[15]);
	$tpl->assign('rede_nsu', $vet2[16]);
	$tpl->assign('rede_authorization_code', $vet2[17]);
	$tpl->assign('rede_date_time', $vet2[18]);
	$tpl->assign('rede_amount', $vet2[19]);
	$tpl->assign('rede_card_bin', $vet2[20]);
	$tpl->assign('rede_last4', $vet2[21]);
	$tpl->assign('rede_brand_tid', $vet2[22]);

	$status_pedido = $vet2[9];
	if ($status_pedido == 1) {
		$tpl->assign('status', "Realizado/ Aguardando aprovação");
	} else if ($status_pedido == 2) {
		$tpl->assign('status', "Aceito");
	} else if ($status_pedido == 3) {
		$tpl->assign('status', "Processo de entrega");
	} else if ($status_pedido == 4) {
		$tpl->assign('status', "Pedido Entregue");
	} else if ($status_pedido == 5) {
		$tpl->assign('status', "Cancelado");
	}

	$status_nfce = $vet2[36];
	if ($status_nfce == 0) {
	 	$tpl->assign("status_nfce", "Aguardando Emissão");
	} elseif ($status_nfce == 1) {
		$tpl->assign("status_nfce", "NFCe Autorizada");
	} elseif ($status_nfce == 2) {
		$tpl->assign("status_nfce", "NFCe Enviada por Email");
	}

	$id_usuario = $vet2[11];	
	$sql1 = "SELECT u.id, u.nome, u.cpf, u.telefone_celular, u.data_cadastro, u.email, u.nascimento, u.status
	         FROM bejobs_usuarios AS u
	         WHERE u.id = $id_usuario";
	$query1 = $dba->query($sql1);
	$vet1   = $dba->fetch($query1);
	$tpl->assign('nome', stripslashes($vet1[1]));
	$tpl->assign('cpf', $vet1[2]);

	$id_entregadores = $vet2[23];	
	$sql3   = "SELECT * FROM bejobs_entregadores WHERE id = $id_entregadores";
	$query3 = $dba->query($sql3);
	$vet3   = $dba->fetch($query3);
	$tpl->assign('entregador_id', $vet3['id']);
	$tpl->assign('entregador_nome', stripslashes($vet3['nome']));
	$tpl->assign('entregador_login', stripslashes($vet3['login']));	

	$sql5   = "SELECT * FROM bejobs_pedidos_produtos WHERE id_pedido = $id";
	$query5 = $dba->query($sql5);
	$qntd5  = $dba->rows($query5);
	if ($qntd5 > 0) {
		for ($j=0; $j<$qntd5; $j++) {
			$tpl->newBlock('produtos');
			$vet5     = $dba->fetch($query5);
			$tpl->assign('id_produto', $vet5['id_produto']);
			$tpl->assign('titulo_produto', $vet5['titulo_produto']);
			$tpl->assign('valor_produto', moeda($vet5['valor_produto']));
			$tpl->assign('qntd', $vet5['qntd']);
			$tpl->assign('valor_total_produto', moeda($vet5['valor_produto']*$vet5['qntd']));

			// $foto_grande  = "https://bejobsapp.com.br/images/produtos/".$id_produto."_800x600.jpg";
			// $foto_pequena = "https://bejobsapp.com.br/images/produtos/".$id_produto.".jpg";

			// $array_produtos[] = array('id' => $id_produto, 'titulo' => $titulo_produto, 'qntd' => $qntd, 'preco' => moeda($valor_produto), 'foto_grande' => $foto_grande, 'foto_pequena' => $foto_pequena);
		}
	}	

	$sql4   = "SELECT * FROM bejobs_pedidos_status WHERE id_pedido = $id ORDER BY data_hora_registro";
	$query4 = $dba->query($sql4);
	$qntd4  = $dba->rows($query4);
	if ($qntd4 > 0) {
		for ($k=0; $k<$qntd4; $k++) {
			$tpl->newBlock('historico_status');
			$vet4    = $dba->fetch($query4);
			$tpl->assign('data_hora_registro', datetime_date_ptbr($vet4['data_hora_registro'])." - ".datetime_time_ptbr($vet4['data_hora_registro']));
			
			$status  = $vet4['status'];
			if ($status == 1) {
				$tpl->assign("status", "Realizado/ Aguardando aprovação");
			} else if ($status == 2) {
				$tpl->assign("status", "Aceito");
			} else if ($status == 3) {
				$tpl->assign("status", "Processo de entrega");
			} else if ($status == 4) {
				$tpl->assign("status", "Pedido Entregue");
			} else if ($status == 5) {

				if ($vet4['id_usuario'] != '') {
					$tpl->assign("status", "Cancelado - Usuário");

				} else if ($vet4['id_entregadores'] != '') {
					$tpl->assign("status", "Cancelado Entregador");
					
				}	
			}

		}
	}

	$sql6   = "SELECT * FROM bejobs_pedidos_transferencias WHERE id_pedido = $id";
	$query6 = $dba->query($sql6);
	$qntd6  = $dba->rows($query6);
	if ($qntd6 > 0) {
		for ($b=0; $b<$qntd6; $b++) {
			$tpl->newBlock('historico_transferencias');
			$vet6 = $dba->fetch($query6);
			$tpl->assign('data_hora_registro', datetime_date_ptbr($vet6['data_hora_registro'])." - ".datetime_time_ptbr($vet6['data_hora_registro']));
			$tpl->assign('observacoes', stripslashes($vet6['observacoes']));

			$status = $vet6['status_atual'];
			if ($status == 1) {
				$tpl->assign("status", "Realizado/ Aguardando aprovação");
			} else if ($status == 2) {
				$tpl->assign("status", "Aceito");
			} else if ($status == 3) {
				$tpl->assign("status", "Processo de entrega");
			} else if ($status == 4) {
				$tpl->assign("status", "Pedido Entregue");
			} else if ($status == 5) {
				$tpl->assign("status", "Cancelado");	
			}
			
			$id_entregadores_remetente = $vet6['id_entregadores_remetente'];	
			$sql10   = "SELECT * FROM bejobs_entregadores WHERE id = $id_entregadores_remetente";
			$query10 = $dba->query($sql10);
			$vet10   = $dba->fetch($query10);
			$tpl->assign('entregador_remetente', stripslashes($vet10['nome']));

			$id_entregadores_destinatario = $vet6['id_entregadores_destinatario'];	
			$sql11   = "SELECT * FROM bejobs_entregadores WHERE id = $id_entregadores_destinatario";
			$query11 = $dba->query($sql11);
			$vet11   = $dba->fetch($query11);
			$tpl->assign('entregador_destinatario', stripslashes($vet11['nome']));
		}
	} else {
		$tpl->newBlock('nohistorico_transferencias');
	}	

	if ($status_pedido == 5) {
		$sql1 = "SELECT * FROM bejobs_pedidos_cancelamentos WHERE id_pedido = $id";
		$query1 = $dba->query($sql1);
		$vet1   = $dba->fetch($query1);
		$tpl->newBlock('infos_cancelamento');
		$tpl->assign('rede_refund_id', stripslashes($vet1['rede_refund_id']));
		$tpl->assign('rede_refund_tid', stripslashes($vet1['rede_refund_tid']));
		$tpl->assign('rede_refund_nsu', stripslashes($vet1['rede_refund_nsu']));
		$tpl->assign('rede_refund_date_time', stripslashes($vet1['rede_refund_date_time']));
		$tpl->assign('observacoes_cancelamento', stripslashes($observacoes_cancelamento));
		
	}

	if ($status_nfce != 0) {
		$tpl->newBlock('infos_nfce');
		$tpl->assign("nf_cnpj_emitente", $vet2[24]); 
		$tpl->assign("nf_ref", $vet2[25]); 
		$tpl->assign("nf_status_titulo", $vet2[26]); 
		$tpl->assign("nf_status_sefaz", $vet2[27]); 
		$tpl->assign("nf_mensagem_sefaz", $vet2[28]); 
		$tpl->assign("nf_chave_nfe", $vet2[29]); 
		$tpl->assign("nf_numero", $vet2[30]); 
		$tpl->assign("nf_serie", $vet2[31]); 
		$tpl->assign("nf_caminho_xml_nota_fiscal", $vet2[32]); 
		$tpl->assign("nf_caminho_danfe", $vet2[33]); 
		$tpl->assign("nf_qrcode_url", $vet2[34]); 
		$tpl->assign("nf_url_consulta_nf", $vet2[35]);
	}	 

} else {
	header('Location: ./vendas');
}

//--------------------------
$tpl->printToScreen();
?>