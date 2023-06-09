<?php
include('./inc/inc.verificasession.php');
include('./inc/class.TemplatePower.php');
include('./inc/inc.configdb.php');
include('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/push-notification.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 1;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include('./menu.php'); //monta o menu de acordo com o usuário
include('./inc/inc.mensagens.php'); //mensagens e alerts

$sql = "SELECT id, data_registro, titulo FROM bejobs_push_notification ORDER BY data_registro DESC";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('mensagens');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('data_registro', datetime_date_ptbr($vet[1])." ".datetime_time_ptbr($vet[1]));
		$tpl->assign('assunto', stripslashes($vet[2]));
	}
}

//--------------------------
$tpl->printToScreen();
?>