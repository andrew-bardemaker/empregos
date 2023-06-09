<?php
require_once('../inc/inc.verificasession.php');
require_once('../inc/class.TemplatePower.php');
require_once('../inc/inc.configdb.php');
require_once('../inc/inc.lib.php');

$tpl = new TemplatePower('../tpl/default.html');
$tpl->assignInclude('content', './tpl/vendas.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 13;
// $act_permissao_editar = true;
require_once('../inc/inc.verificaacessopermissao.php');

include_once('../menu.php'); //monta o menu de acordo com o usuário
include_once('../inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

// $numero_serie_sql = "";
$cpf_sql          = "";

// if (isset($_GET['numero_serie']) && !empty($_GET['numero_serie'])) {
// 	$tpl->assign('numero_serie', $_GET['numero_serie']);
// 	$numero_serie     = addslashes($_GET['numero_serie']);
// 	$numero_serie_sql = "AND v.numero_serie = '$numero_serie'";
// } 

if (isset($_GET['cpf']) && !empty($_GET['cpf'])) {
	$tpl->assign('cpf', $_GET['cpf']);
	$cpf     =  preg_replace("/[^0-9]/", "", $_GET['cpf']);
	$cpf_sql = "AND b.cpf = '$cpf'";
} 

// Select com períodos de filtro
$tpl->assign('custom', date('d/m/Y'));
$tpl->assign('today', date('d/m/Y'));
$tpl->assign('yesterday', date('d/m/Y', strtotime('- 1 days')));
$tpl->assign('last_week', date('d/m/Y', strtotime('- 7 days')));
$tpl->assign('last_30_days', date('d/m/Y', strtotime('- 30 days')));
$tpl->assign('last_6_months', date('d/m/Y', strtotime('- 6 months')));
$tpl->assign('last_12_months', date('d/m/Y', strtotime('- 12 months')));

$periodo = "";
if (isset($_GET['periodo']) && !empty($_GET['periodo'])) {
	$periodo = $_GET['periodo'];
}

switch ($periodo) {
	case 'custom':
		$tpl->assign('custom_selected', 'selected');
		break;
	case 'today':
		$tpl->assign('today_selected', 'selected');
		break;
	case 'yesterday':
		$tpl->assign('yesterday_selected', 'selected');
		break;
	case 'last_week':
		$tpl->assign('last_week_selected', 'selected');
		break;
	case 'last_30_days':
		$tpl->assign('last_30_days_selected', 'selected');
		break;
	case 'last_6_months':
		$tpl->assign('last_6_months_selected', 'selected');
		break;
	case 'last_12_months':
		$tpl->assign('last_12_months_selected', 'selected');
		break;
	default:
		// $tpl->assign('today_selected', 'selected');
		break;
}

// Data dos filtros
$periodo_sql = "";

if (isset($_GET['data_ini']) && !empty($_GET['data_ini']) && isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
	$tpl->assign('data_ini', $_GET['data_ini']);
	$tpl->assign('data_fim', $_GET['data_fim']);

	$data_ini = dataMY($_GET['data_ini'])." 00:00:00";
	$data_fim = dataMY($_GET['data_fim'])." 23:59:59";

	$periodo_sql = "AND a.data_hora_registro BETWEEN '$data_ini' AND '$data_fim'"; // Monta sql com datas filtradas no período
} 

$entregador     = ""; // Variável p/ salvar id do entregador
$entregador_sql = ""; // Variável p/ montar sql com id do entregador
if (isset($_GET['entregador']) && !empty($_GET['entregador'])) {
	$entregador     = $_GET['entregador'];
	$entregador_sql = "AND  a.id_entregadores = '".$_GET['entregador']."'";
	$tpl->assignGlobal('entregador',  $entregador);
}

if (!empty($periodo_sql)) {

	$total_vendas = 0;

	$sql20   = "SELECT a.id, 
				a.data_hora_registro, 
				a.total_pedido, 
				a.status,  
				b.cpf
			    FROM bejobs_pedidos AS a 
			    INNER JOIN bejobs_usuarios AS b
			    WHERE a.id_usuario = b.id 
			    AND a.status = 4
			    $periodo_sql 
			    $cpf_sql
			    $entregador_sql
			    ORDER BY a.data_hora_registro DESC";
	// print_r($sql20);
	$query20 = $dba->query($sql20);
	$qntd20  = $dba->rows($query20);
	if ($qntd20 > 0) {
		for ($y=0; $y<$qntd20; $y++) {
			$vet = $dba->fetch($query20);
			$tpl->newBlock('vendas');
			$id_pedido = $vet[0];
			$tpl->assign('id', $vet[0]);
			$tpl->assign('data_hora_registro', datetime_date_ptbr($vet[1]).' '.datetime_time_ptbr($vet[1]));
			$tpl->assign('total_pedido', moeda($vet[2]));	 
			$tpl->assign('cpf', stripslashes($vet[5]));

			$total_vendas = $total_vendas + $vet[2];

			$status = $vet[3];
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

			$tpl->assign('entregador', stripslashes($vet[6]));

			$sql5   = "SELECT * FROM bejobs_pedidos_produtos WHERE id_pedido = $id_pedido";
			$query5 = $dba->query($sql5);
			$qntd5  = $dba->rows($query5);
			if ($qntd5 > 0) {
				for ($j=0; $j<$qntd5; $j++) {
					$tpl->newBlock('produtos');
					$vet5     = $dba->fetch($query5);
					$tpl->assign('id_produto', $vet5['id_produto']);
					$tpl->assign('titulo_produto', $vet5['titulo_produto']);
					// $tpl->assign('valor_produto', moeda($vet5['valor_produto']));
					$tpl->assign('qntd', $vet5['qntd']);
					// $tpl->assign('valor_total_produto', moeda($vet5['valor_produto']*$vet5['qntd']));
				}
			}
			
		}

		$tpl->newBlock('total');
		$tpl->assign('total_vendas', moeda($total_vendas));	
		
	} else {
		$tpl->newBlock('no-vendas');
	}

} else {
	$tpl->newBlock('no-vendas');
}

$tpl->printToScreen();
?>