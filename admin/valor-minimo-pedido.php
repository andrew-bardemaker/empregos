<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/valor-minimo-pedido.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 5;
$act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$sql   = "SELECT valor FROM bejobs_valor_minimo_pedido WHERE id = 1"; // or die(); // print_r($sql);
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	$vet = $dba->fetch($query);
	$tpl->assign('valor', moeda($vet[0]));	
}

//--------------------------
$tpl->printToScreen();
?>