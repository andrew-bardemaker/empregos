<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/entregadores-editar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 4;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];
if (empty($id)) {
	header('Location: ./entregadores');
}

$sql2   = "SELECT * FROM bejobs_entregadores WHERE id = $id";
$query2 = $dba->query($sql2);
$qntd2  = $dba->rows($query2);
if ($qntd2 > 0) {
	$vet2 = $dba->fetch($query2);
	$tpl->assign('id', $vet2['id']);
	$tpl->assign('nome', stripslashes($vet2['nome']));
	$tpl->assign('email', stripslashes($vet2['email']));
	$tpl->assign('telefone_celular', stripslashes($vet2['telefone_celular']));	
	$tpl->assign('login', stripslashes($vet2['login']));	
	$tpl->assign('senha', stripslashes($vet2['senha']));	

} else {
	header('Location: ./entregadores');
}

//--------------------------
$tpl->printToScreen();
?>