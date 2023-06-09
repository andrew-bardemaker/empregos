<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/compras-visualizar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 2;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

if (!is_numeric($id)) {
	header("Location: ./compras");
}

$sql   = "SELECT * FROM bejobs_compras WHERE id = $id"; // or die(); // print_r($sql);
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	$vet = $dba->fetch($query);
	$tpl->assign('id', $vet['id']);
	$chave_acesso = $vet['chave_acesso'];
	$tpl->assign('chave_acesso', $vet['chave_acesso']);
	$tpl->assign('protocolo_aut', $vet['protocolo_aut']);
	$tpl->assign('n_nfce', $vet['n_nfce']);
	$tpl->assign('serie', $vet['serie']);
	$tpl->assign('data_emissao', $vet['data_emissao']);
	$tpl->assign('emit_nome', $vet['emit_nome']);
	$tpl->assign('emit_cnpj', $vet['emit_cnpj']);
	$tpl->assign('emit_ie', $vet['emit_ie']);
	$tpl->assign('dest_cnpj_cpf', $vet['dest_cnpj_cpf']);
	$tpl->assign('dest_nome', $vet['dest_nome']);
	$tpl->assign('inf_cpl', $vet['inf_cpl']);
	if ($vet['total_nfce'] != "") {
		$tpl->assign('total_nfce', moeda($vet['total_nfce']));
	}	
	// $tpl->assign('app_pontos', $vet['app_pontos']);
	/*$tpl->assign('titulo', stripslashes($vet['titulo']));*/

	// if ($vet['status'] == 1) {
	// 	$tpl->assign('status', 'Autorizada');
		
	// } else if ($vet['status'] == 2) {
	// 	$tpl->assign('status', 'Cancelada');
	// }

	$sql2 = "SELECT * FROM bejobs_compras_produtos WHERE nfce_chave_acesso = '$chave_acesso'";
	$query2 = $dba->query($sql2);
	$qntd2 = $dba->rows($query2);
	if ($qntd2 > 0) {
		for ($i=0; $i<$qntd2; $i++) {
			$vet2 = $dba->fetch($query2);
			$tpl->newBlock('compras_produtos');
			$tpl->assign('id_produto', $vet2['id_produto']);
			$tpl->assign('titulo', $vet2['titulo']);
			$tpl->assign('qtde', $vet2['qtde']);
			$tpl->assign('vl_unit', moeda($vet2['vl_unit']));
			$tpl->assign('vl_total', moeda($vet2['vl_total']));			
			// $tpl->assign('app_pontos', $vet2['app_pontos']);
		}
	} 

} else {
	header("Location: ./compras");
}

//--------------------------
$tpl->printToScreen();
?>