<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/controle-de-estoque.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 2;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

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

	// $periodo_sql = "AND v.data_hora_registro BETWEEN '$data_ini' AND '$data_fim'"; // Monta sql com datas filtradas no período
} 

// $entregador     = ""; // Variável p/ salvar id do entregador
// $entregador_sql = ""; // Variável p/ montar sql com id do entregador
// if (isset($_GET['entregador']) && !empty($_GET['entregador'])) {
// 	$entregador     = $_GET['entregador'];
// 	$entregador_sql = "AND a.id_entregadores = '".$_GET['entregador']."'";
// 	$tpl->assignGlobal('entregador',  $entregador);
// }

// $sql18   = "SELECT id, nome FROM bejobs_entregadores ORDER BY nome DESC";
// $query18 = $dba->query($sql18);
// $qntd18  = $dba->rows($query18);
// if ($qntd18 > 0) {
// 	for ($y=0; $y<$qntd18; $y++) {
// 		$vet18 = $dba->fetch($query18);
// 		$tpl->newBlock('entregadores');
// 		$tpl->assign('id', $vet18[0]);
// 		$tpl->assign('nome', stripslashes($vet18[1]));

// 		if ($entregador == stripslashes($vet18[0])) {
// 			$tpl->assign('selected', 'selected');
// 		}	
// 	}
// }

if (isset($_GET['data_ini']) && !empty($_GET['data_ini']) && isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {

	$sql   = "SELECT a.id, a.titulo, b.titulo, c.titulo, a.status, a.destaque, a.codigo
	          FROM bejobs_produtos AS a
	          INNER JOIN bejobs_produtos_categorias AS b
	          INNER JOIN bejobs_produtos_marcas AS c
	          WHERE a.id_categoria = b.id
	          AND a.id_marca = c.id
	          ORDER BY codigo";
	$query = $dba->query($sql);
	$qntd  = $dba->rows($query);
	if ($qntd > 0) {
		for ($i=0; $i<$qntd; $i++) {
			$vet = $dba->fetch($query);
			$tpl->newBlock('estoque');
			$tpl->assign('id', $vet[0]);
			$id_produto = $vet[0];
			$tpl->assign('codigo_produto', $vet[6]);
			$codigo_produto = $vet[6];
			$tpl->assign('descricao_produto', stripslashes($vet[1]));
			$tpl->assign('categoria', stripslashes($vet[2]));
			$tpl->assign('marca', stripslashes($vet[3]));

			$qtde_total_vendas   = 0; 
			$qtde_total_compras  = 0; 
			$valor_total_vendas  = 0;
			$valor_total_compras = 0;
			$qtde_total_baixas   = 0;

			$sql1 = "SELECT SUM(b.qntd), SUM(b.valor_produto*b.qntd)
					 FROM bejobs_pedidos AS a
					 INNER JOIN bejobs_pedidos_produtos AS b 
					 WHERE a.id = b.id_pedido
					 AND b.id_produto = $id_produto
					 AND a.data_hora_registro BETWEEN '$data_ini' AND '$data_fim'
					 AND a.status = 4";
			// print_r($sql1);
			$query1 = $dba->query($sql1);
			$vet1   = $dba->fetch($query1);
			if ($vet1[0]!="") {
				$qtde_total_vendas  = $vet1[0];
				$valor_total_vendas = $vet1[1];
			}		
			$tpl->assign('qtde_total_vendas', $qtde_total_vendas);
			$tpl->assign('valor_total_vendas', moeda($valor_total_vendas));

			$sql2 = "SELECT SUM(b.qtde), SUM(b.vl_total)
					 FROM bejobs_compras AS a
					 INNER JOIN bejobs_compras_produtos AS b 
					 INNER JOIN bejobs_produtos AS c
					 WHERE a.chave_acesso = b.nfce_chave_acesso
					 AND b.id_produto = c.codigo
					 AND c.id = $id_produto
					 AND a.data_emissao BETWEEN '$data_ini' AND '$data_fim'";
			$query2 = $dba->query($sql2);
			$vet2   = $dba->fetch($query2);
			if ($vet2[0]!="") {
				$qtde_total_compras  = $vet2[0];
				$valor_total_compras = $vet2[1];
			}	
			$tpl->assign('qtde_total_compras', $qtde_total_compras);
			$tpl->assign('valor_total_compras', moeda($valor_total_compras));

			$sql3 = "SELECT SUM(a.qtde)
					 FROM bejobs_estoque_baixas AS a
					 WHERE a.id_produto = $id_produto
					 AND a.data_registro BETWEEN '$data_ini' AND '$data_fim'";
			// print_r($sql3);
			$query3 = $dba->query($sql3);
			$vet3   = $dba->fetch($query3);
			if ($vet3[0]!="") {
				$qtde_total_baixas = $vet3[0];
			}		
			$tpl->assign('qtde_total_baixas', $qtde_total_baixas);

			$tpl->assign('total', $qtde_total_compras-$qtde_total_vendas-$qtde_total_baixas);

		}
	} else {
		$tpl->newBlock('no-estoque');
	}

} else {
	$tpl->newBlock('no-estoque');
}

// $sql2   = "SELECT id, titulo FROM bejobs_produtos_marcas ORDER BY titulo";
// $query2 = $dba->query($sql2);
// $qntd2  = $dba->rows($query2);
// if ($qntd2 > 0) {
// 	for ($i=0; $i<$qntd2; $i++) {
// 		$vet2 = $dba->fetch($query2);
// 		$tpl->newBlock('marcas');
// 		$tpl->assign('id', $vet2[0]);
// 		$tpl->assign('titulo', $vet2[1]);
// 	}
// }

// $sql1   = "SELECT id, titulo FROM bejobs_produtos_categorias ORDER BY titulo";
// $query1 = $dba->query($sql1);
// $qntd1  = $dba->rows($query1);
// if ($qntd1 > 0) {
// 	for ($i=0; $i<$qntd1; $i++) {
// 		$vet1 = $dba->fetch($query1);
// 		$tpl->newBlock('categorias');
// 		$tpl->assign('id', $vet1[0]);
// 		$tpl->assign('titulo', $vet1[1]);
// 	}
// }

//--------------------------
$tpl->printToScreen();
?>