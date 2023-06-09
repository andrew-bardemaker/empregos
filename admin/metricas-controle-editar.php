<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/metricas-controle-editar.html');
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
	header('Location: ./metrica-controles');
}

$sql2   = "SELECT * FROM bejobs_valor_km WHERE id = $id";
$query2 = $dba->query($sql2);
$qntd2  = $dba->rows($query2);
if ($qntd2 > 0) {
	$vet2 = $dba->fetch($query2);
	$tpl->assign('id', $vet2['id']);
	if(stripslashes($vet2['id']) == '1'){
		$nome="Next Day";
	}else{
		$nome="Today";
	}
	$tpl->assign('nome', stripslashes($nome));
	$tpl->assign('value', stripslashes($vet2['valor'])); 
	$tpl->assign('id', $vet2['id']);

} else {
	header('Location: ./metrica-controles');
}

//--------------------------
$tpl->printToScreen();
?>