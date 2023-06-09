<?php
include('./inc/inc.verificasession.php');
include('./inc/inc.configdb.php');
include('./inc/class.TemplatePower.php');
include('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html'); 
$tpl->assignInclude('content', './tpl/notificacoes-visualizar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 1;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include('./menu.php'); //monta o menu de acordo com o usuário
include('./inc/inc.mensagens.php'); //mensagens e alertas

$tpl->gotoBlock('_ROOT');

if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$id_notificacao   = $_GET['id'];

	$sql2 = "SELECT id, data_registro, titulo, texto, status, tipo, id_chamado FROM bejobs_notificacoes_admin WHERE id = $id_notificacao";
	$query2 = $dba->query($sql2);
	$qntd2 = $dba->rows($query2);
	if ($qntd2 > 0) {
		$vet2 = $dba->fetch($query2);
		$id_notificacao = $vet2[0];
		$tpl->assign('id_notificacao', $vet2[0]);
		$tpl->assign('titulo', stripslashes($vet2[2]));
		$tpl->assign('mensagem', stripslashes(nl2br($vet2[3])));
		$tpl->assign('data_registro', datetime_date_ptbr($vet2[1]));
		$tpl->assign('hora_registro', datetime_time_full_ptbr($vet2[1]));

		$status = $vet2[4];
		if ($status == 1) {
			$tpl->assign('status', 'Não lido');
			$tpl->newBlock('notificacao-nao-lida');
			$tpl->assign('id_notificacao', $vet2[0]);
			
		} elseif ($status == 2) {
			$tpl->assign('status', 'Lido');
		}	

	} else {
		header("Location: notificacoes");
	}

} else {
	header("Location: notificacoes");
}
 
$tpl->printToScreen();
?>