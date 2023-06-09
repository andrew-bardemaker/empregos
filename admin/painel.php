<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/painel.html');
$tpl->prepare();

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$app_user_id = $_SESSION['app_user_id'];

if ($_SESSION['app_user_type'] == 1 || $_SESSION['app_user_type'] == 2) {

	$sql0        = "SELECT acess_panel FROM bejobs_usuario_admin WHERE id = $app_user_id";
	$query0      = $dba->query($sql0);
	$vet0        = $dba->fetch($query0);
	$acess_panel = $vet0[0];

	if ($acess_panel == 1) {

		$tpl->newBlock('painel-admin');
		/**
 		 *
 		 * Painel de Usuários
 		 *
 		 */
		$sql1   = "SELECT COUNT(id) FROM bejobs_usuarios WHERE status = 1"; // Usuários PF
		$query1 = $dba->query($sql1);
		$vet1   = $dba->fetch($query1);
		$qntd_usuarios = $vet1[0];
		$tpl->assignGlobal('qntd_usuarios', $qntd_usuarios);


		$data_ini = date("Y-m-d 00:00:00");
		$data_fim = date("Y-m-d 23:59:59");

		/**
 		 *
 		 * Painel de Anúncios
 		 *
 		 */
		$sql1   = "SELECT COUNT(id) FROM bejobs_anuncios ";
		$query1 = $dba->query($sql1);
		$vet1   = $dba->fetch($query1);
		$qntd_vendas = $vet1[0];
		$tpl->assignGlobal('qntd_vendas', $qntd_vendas);

		/**
 		 *
 		 * Painel de Entregas
 		 *
 		 */
		$sql1   = "SELECT COUNT(id) FROM bejobs_pedidos WHERE status = 4 AND data_hora_registro BETWEEN '$data_ini' AND '$data_fim'";
		$query1 = $dba->query($sql1);
		$vet1   = $dba->fetch($query1);
		$qntd_entregas = $vet1[0];
		$tpl->assignGlobal('qtnd_vagas', $qntd_entregas);

		
		
		/**
 		 *
 		 * Painel de Vagas
 		 *
 		 */
		  $sql1   = "SELECT COUNT(id) FROM bejobs_vagas WHERE status = 1";
		  $query1 = $dba->query($sql1);
		  $vet1   = $dba->fetch($query1);
		  $qtnd_vagas = $vet1[0];
		  $tpl->assignGlobal('qtnd_vagas', $qtnd_vagas);

		  
		/**
 		 *
 		 * Painel de Vagas
 		 *
 		 */
		  $sql1   = "SELECT COUNT(id) FROM bejobs_vagas WHERE status_pagamento = 1";
		  $query1 = $dba->query($sql1);
		  $vet1   = $dba->fetch($query1);
		  $qtnd_vagas = $vet1[0];
		  $tpl->assignGlobal('qtnd_vagas_pagas', $qtnd_vagas);

		  
		/**
 		 *
 		 * Painel de Vagas
 		 *
 		 */
		  $sql1   = "SELECT COUNT(id) FROM bejobs_vagas WHERE status_pagamento = 0";
		  $query1 = $dba->query($sql1);
		  $vet1   = $dba->fetch($query1);
		  $qtnd_vagas_pendentes = $vet1[0];
		  $tpl->assignGlobal('qtnd_vagas_pendentes', $qtnd_vagas_pendentes);
		/**
 		 *
 		 * Painel Horário de Funcionamento
 		 *
 		 */

		$data_hora_atual = date('Y-m-d H:i:s');

		 

		/**
 		 *
 		 * Painel de Chamados
 		 *
 		 */
		$sql1   = "SELECT COUNT(id) FROM bejobs_chamados WHERE status != 5";
		$query1 = $dba->query($sql1);
		$vet1   = $dba->fetch($query1);
		$qntd_chamados = $vet1[0];
		$tpl->assignGlobal('qntd_chamados', $qntd_chamados);


		$sql = "SELECT a.id, a.data_hora_registro, a.total_pedido, a.status, b.nome, b.cpf
			    FROM bejobs_pedidos AS a 
			    INNER JOIN bejobs_usuarios AS b
			    WHERE a.id_usuario = b.id
			    AND a.status = 1
			    ORDER BY a.data_hora_registro DESC";
		$query = $dba->query($sql);
		$qntd  = $dba->rows($query);
		if ($qntd > 0) {
			for ($i=0; $i<$qntd; $i++) {
				$vet = $dba->fetch($query);
				$tpl->newBlock('vendas');
				$tpl->assign('id', $vet['id']);
				$tpl->assign('data_hora_registro', datetime_date_ptbr($vet['data_hora_registro']).' '.datetime_time_ptbr($vet['data_hora_registro']));
				$tpl->assign('total_pedido', moeda($vet['total_pedido']));		
				$tpl->assign('nome', stripslashes(strtoupper($vet['nome'])));
				$tpl->assign('cpf', stripslashes($vet['cpf']));

				// $status = $vet['status'];
				// if ($status == 1) {
				// 	$tpl->assign("status", strtoupper("Realizado/ Aguardando aprovação");
				// } else if ($status == 2) {
				// 	$tpl->assign("status", strtoupper("Aceito");
				// } else if ($status == 3) {
				// 	$tpl->assign("status", strtoupper("Processo de entrega");
				// } else if ($status == 4) {
				// 	$tpl->assign("status", strtoupper("Pedido Entregue");
				// } else if ($status == 5) {
				// 	$tpl->assign("status", strtoupper("Cancelado");
				// }
			}
		} else {
			$tpl->newBlock('novendas');
		}

		$tpl->newBlock('grafico_nivel_crescimento');
		$dados8 = '';
		for ($t=5; $t >= 0; $t--) {
			// echo $t;
			// echo "<br>";
			$usuarios = 0;
			$vendas   = 0;

			$mes_consulta = date('Y-m', strtotime('- '.$t.' month', strtotime(date('Y-m')))).'-01'; // echo "<br>";
			$sql8_1   = "SELECT COUNT(id) FROM bejobs_usuarios WHERE MONTH(data_cadastro) = MONTH('$mes_consulta') AND YEAR(data_cadastro) = YEAR('$mes_consulta')"; // print_r($sql8_1);
			$query8_1 = $dba->query($sql8_1);
			$vet8_1   = $dba->fetch($query8_1);
			if (!empty($vet8_1[0])) {
				$usuarios = $vet8_1[0];
			}	

			$sql8_4   = "SELECT COUNT(id) FROM bejobs_pedidos WHERE status = 4 AND MONTH(data_hora_registro) = MONTH('$mes_consulta') AND YEAR(data_hora_registro) = YEAR('$mes_consulta')"; // print_r($sql8_4);
			$query8_4 = $dba->query($sql8_4);
			$vet8_4   = $dba->fetch($query8_4);
			if (!empty($vet8_4[0])) {
				$vendas = $vet8_4[0];
			}	
			
			$dados8 .= "{ month: '".  date('Y-m', strtotime('- '.$t.' month', strtotime(date('Y-m')))) . "', usuarios: ".$usuarios.", vendas: ".$vendas." }";

			if ($t-1 < 5) {
				$dados8 .= ",";
			}
		}
		$tpl->assign('dados_grafico_nivel_crescimento', $dados8); // Dados do gráfico de Usuários Cadastro

	}


}

$tpl->printToScreen();
?>