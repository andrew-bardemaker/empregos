<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/banners-editar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 22;
$act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$id = $_GET['id'];
if (empty($id)) {
	header('Location: ./banners');
}

$sql   = "SELECT * FROM bejobs_banners WHERE id = $id";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	$vet = $dba->fetch($query);
	$tpl->assign('id', $vet['id']);
	$tpl->assign('posicao', $vet['posicao']);		

	if (file_exists('../images/banners/'.$vet['id'].'.jpg')) {
		$tpl->newBlock('img');
		$tpl->assign('id', $vet['id']);
		$tpl->assign('time', time());
	} else {
		$tpl->newBlock('noimg');
	}

	if (file_exists('../images/banners/'.$vet['id'].'-m.jpg')) {
		$tpl->newBlock('img_m');
		$tpl->assign('id', $vet['id']);
		$tpl->assign('time', time());
	} else {
		$tpl->newBlock('noimg_m');
	}			
	
} else {
	header("Location: ./banners");
}

//--------------------------
$tpl->printToScreen();
?>