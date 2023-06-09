<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/notificacoes.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 1;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql = "SELECT id, status, data_registro FROM bejobs_notificacoes_admin ORDER BY data_registro DESC";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('notificacoes');
		$tpl->assign('id', $vet[0]);

		$status = $vet[1];
		if ($status == 1) {
			$tpl->assign('status', 'Não lido');
		} elseif ($status == 2) {
			$tpl->assign('status', 'Lido');
		}

		$tpl->assign('data_registro', datetime_date_ptbr($vet[2])." ".datetime_time_ptbr($vet[2]));
	}
}

//--------------------------
$tpl->printToScreen();
?>