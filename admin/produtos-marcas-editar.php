<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/produtos-marcas-editar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 20;
$act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

if (!empty($_GET['id']) && is_numeric($_GET['id'])) {

	$id = $_GET['id'];

	$sql   = "SELECT * FROM bejobs_produtos_marcas WHERE id = $id";	// print_r($sql);
	$query = $dba->query($sql);
	$qntd  = $dba->rows($query);
	if ($qntd > 0) {
		$vet = $dba->fetch($query);
		$tpl->assign('id', $vet['id']);
		$tpl->assign('titulo', stripslashes($vet['titulo']));

		if (file_exists('../images/marcas/'.$vet['id'].'.jpg')) {
			$tpl->newBlock('img');
			$tpl->assign('id', $vet['id']);
			$tpl->assign('time', time());
		} else {
			$tpl->newBlock('noimg');
		}

	} else {
		header("location: ./produtos-marcas");
	}
	
} else {
	header("location: ./produtos-marcas");
}


//--------------------------
$tpl->printToScreen();
?>