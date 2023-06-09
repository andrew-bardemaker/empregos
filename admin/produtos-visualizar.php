<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/produtos-visualizar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 9;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

$sql2 = "SELECT * FROM bejobs_produtos WHERE id = $id";
$query2 = $dba->query($sql2);
$qntd2 = $dba->rows($query2);
if ($qntd2 > 0) {
	$vet2 = $dba->fetch($query2);
	$tpl->assign('id', $vet2['id']);
	$tpl->assign('codigo_produto', $vet2['codigo']);
	$tpl->assign('id_marca', $vet2['id_marca']);	
	$tpl->assign('marca', $vet2['marca']);
	$tpl->assign('id_categoria', $vet2['id_categoria']);
	$tpl->assign('categoria', $vet2['categoria']);
	$tpl->assign('referencia', $vet2['referencia']);
	$tpl->assign('descricao', $vet2['descricao']);
	$tpl->assign('observacoes', $vet2['observacoes']);
	$tpl->assign('app_pontos', $vet2['app_pontos']);	

	if ($vet2['imagem'] != "") {
		$tpl->newBlock('img');
		$tpl->assign('imagem', $vet2['imagem']);
	} else {
		$tpl->newBlock('noimg');
	}
}

//--------------------------
$tpl->printToScreen();
?>