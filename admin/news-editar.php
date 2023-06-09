<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/news-editar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 7;
$act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];
if (!is_numeric($id)) {
	header('Location: ./news');
}

$sql = "SELECT * FROM bejobs_news WHERE id = $id"; // print_r($sql);
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	$vet = $dba->fetch($query);
	$tpl->assign('id', $vet['id']);
	$tpl->assign('titulo', stripslashes($vet['titulo']));
	$tpl->assign('texto', stripslashes($vet['texto']));
	$tpl->assign('link', stripslashes($vet['link']));
	$tpl->assign('tags', stripslashes($vet['tags']));
	$tpl->assign('data', dataBR($vet['data']));	

	if (file_exists('../images/news/'.$id.'.jpg')) {
		$tpl->newBlock('img');
		$tpl->assign('id', $vet['id']);
		$tpl->assign('time', time());
	} else {
		$tpl->newBlock('noimg');
	}




} else {
	header("Location: ./news");
}

//--------------------------
$tpl->printToScreen();
?>