<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/notas.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 6;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

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

	$periodo_sql = "AND a.data_emissao BETWEEN '$data_ini' AND '$data_fim'"; // Monta sql com datas filtradas no período
} 

$codigo_barras_sql  = "";
if (isset($_GET['codigo_barras']) && !empty($_GET['codigo_barras'])) {
	$tpl->assign('codigo_barras', $_GET['codigo_barras']);
	$codigo_barras     = addslashes($_GET['codigo_barras']);
	$codigo_barras_sql = "AND a.codigo_barras = '$codigo_barras'";
}

$loja     = ""; // Variável p/ salvar id do loja
$loja_sql = ""; // Variável p/ montar sql com id do loja
if (isset($_GET['loja']) && !empty($_GET['loja'])) {
	$loja     = $_GET['loja'];
	$loja_sql = "AND b.id = '".$_GET['loja']."'";
	$tpl->assignGlobal('loja',  $loja);
}

$sql18   = "SELECT id, razao_social, cnpj FROM bejobs_lojas ORDER BY razao_social";
$query18 = $dba->query($sql18);
$qntd18  = $dba->rows($query18);
if ($qntd18 > 0) {
	for ($y=0; $y<$qntd18; $y++) {
		$vet18 = $dba->fetch($query18);
		$tpl->newBlock('lojas');
		$tpl->assign('id', $vet18['id']);
		$tpl->assign('titulo', stripslashes($vet18['razao_social']).' - '.$vet18['cnpj']);	

		if ($loja == $vet18['id']) {
			$tpl->assign('selected', 'selected');
		}	
	}
} 

if (!empty($codigo_barras_sql) || !empty($produto_sql) || !empty($loja_sql) || !empty($periodo_sql)) {

	$tpl->newBlock('notas_filter'); 
	$sql   = "SELECT a.id, a.data_emissao, a.numero, a.codigo_barras, a.codigo_produto, a.qntd, a.codigo_cliente, a.cnpj
			  FROM bejobs_notas AS a
	          INNER JOIN bejobs_lojas AS b 
	          WHERE a.cnpj = b.cnpj 
	          $codigo_barras_sql 
	          $produto_sql 
	          $loja_sql 
	          $periodo_sql
	          ORDER BY a.data_emissao DESC"; 
	$query = $dba->query($sql);
	$qntd  = $dba->rows($query);
	if ($qntd > 0) {
		for ($i=0; $i<$qntd; $i++) {
			$vet = $dba->fetch($query);
			$tpl->newBlock('notas');
			$tpl->assign('id', stripslashes($vet[0]));
			$tpl->assign('data_emissao', dataBR($vet[1]));
			$tpl->assign('nota_numero', stripslashes($vet[2]));
			$tpl->assign('codigo_barras', stripslashes($vet[3]));
			$tpl->assign('codigo_produto', stripslashes($vet[4]));
			$tpl->assign('qntd', stripslashes($vet[5]));
			$tpl->assign('codigo_cliente', stripslashes($vet[6]));
			$tpl->assign('cnpj_cliente', stripslashes($vet[7]));
		}
	} 

} else {
	$tpl->newBlock('notas_all');
}

//--------------------------
$tpl->printToScreen();
?>