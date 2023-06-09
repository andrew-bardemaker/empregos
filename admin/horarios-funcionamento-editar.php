<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/horarios-funcionamento-editar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao  = 5;
$act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

$sql   = "SELECT * FROM bejobs_horarios_funcionamento WHERE id = '$id'";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$idp = $vet['id'];
		$tpl->assign('id', $vet['id']);
		$tpl->assign('data_inicio', datetime_date_ptbr($vet['data_hora_ini']));
		$tpl->assign('data_fim', datetime_date_ptbr($vet['data_hora_fim']));	
		$tpl->assign('hora_inicio', datetime_time_ptbr($vet['data_hora_ini']));
		$tpl->assign('hora_fim', datetime_time_ptbr($vet['data_hora_fim']));
	}

} else {	
	header('Location: ./horarios-funcionamento');
}

//--------------------------
$tpl->printToScreen();
?>

