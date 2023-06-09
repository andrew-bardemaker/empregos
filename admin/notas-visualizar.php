<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/notas-visualizar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 6;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

$sql2 = "SELECT * FROM bejobs_notas WHERE id = $id";
$query2 = $dba->query($sql2);
$qntd2 = $dba->rows($query2);
if ($qntd2 > 0) {
	$vet2 = $dba->fetch($query2);
	$tpl->assign('codigo_cliente', $vet2['codigo_cliente']);
	$tpl->assign('cnpj', $vet2['cnpj']);
	$tpl->assign('numero', $vet2['numero']);
	$tpl->assign('codigo', $vet2['codigo']);
	$tpl->assign('data_emissao', dataBR($vet2['data_emissao']));
	$tpl->assign('serie', $vet2['serie']);
	$tpl->assign('codigo_produto', $vet2['codigo_produto']);
	$tpl->assign('codigo_barras', $vet2['codigo_barras']);	
	$tpl->assign('qntd', $vet2['qntd']);	

	// $tpl->assign('id', $vet2['id']);
	// $tpl->assign('codigo_cliente', $vet2['codigo_cliente']);
	// $tpl->assign('cnpj', $vet2['cnpj']);
	// $tpl->assign('nota_fiscal', $vet2['nota_fiscal']);	
	// // $tpl->assign('codigo_produto', $vet2['codigo_produto']);	
	// $tpl->assign('qntd', $vet2['qntd']);
	// $tpl->assign('data_emissao', dataBR($vet2['data_emissao']));
	// $tpl->assign('numero_serie', $vet2['numero_serie']);
	// $tpl->assign('R_E_C_N_O_', $vet2['R_E_C_N_O_']);

	$sql3 = "SELECT * FROM bejobs_produtos WHERE codigo_barras = '".$vet2['codigo_barras']."'";
	$query3 = $dba->query($sql3);
	$qntd3 = $dba->rows($query3);
	if ($qntd3 > 0) {
		$vet3 = $dba->fetch($query3);
		$tpl->assign('id', $vet3['id']);
		$tpl->assign('codigo_produto', $vet3['codigo']);
		$tpl->assign('codigo_barras', $vet3['codigo_barras']);
		$tpl->assign('id_marca', $vet3['id_marca']);	
		$tpl->assign('marca', $vet3['marca']);
		$tpl->assign('id_categoria', $vet3['id_categoria']);
		$tpl->assign('categoria', $vet3['categoria']);
		$tpl->assign('referencia', $vet3['referencia']);
		$tpl->assign('descricao', $vet3['descricao']);
		// $tpl->assign('observacoes', $vet3['observacoes']);
		$tpl->assign('app_pontos', $vet3['app_pontos']);	

		if (file_exists('../images/produtos/'.$vet3['id'].'.jpg')) {
			$tpl->newBlock('img');
			$tpl->assign('id', $vet3['id']);
			$tpl->assign('time', time());
		} else {
			$tpl->newBlock('noimg');
		}
	}
}


//--------------------------
$tpl->printToScreen();
?>