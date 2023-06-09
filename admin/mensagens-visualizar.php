<?php
include('./inc/inc.verificasession.php');
include('./inc/inc.configdb.php');
include('./inc/class.TemplatePower.php');
include('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html'); 
$tpl->assignInclude('content', './tpl/mensagens-visualizar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 1;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include('./menu.php'); //monta o menu de acordo com o usuário
include('./inc/inc.mensagens.php'); //mensagens e alertas

$tpl->gotoBlock('_ROOT');

if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$id_mensagem   = $_GET['id'];

	$sql2 = "SELECT id, data_registro, assunto, nome, email, mensagem, status, telefone FROM bejobs_mensagens_faleconosco WHERE id = $id_mensagem";
	$query2 = $dba->query($sql2);
	$qntd2 = $dba->rows($query2);
	if ($qntd2 > 0) {
		$vet2 = $dba->fetch($query2);
		$id_mensagem = $vet2[0];
		$tpl->assign('id_mensagem', $vet2[0]);
		$tpl->assign('assunto', stripslashes($vet2[2]));
		$tpl->assign('nome', stripslashes($vet2[3]));
		$tpl->assign('email', stripslashes($vet2[4]));
		$tpl->assign('mensagem', stripslashes(nl2br($vet2[5])));
		$tpl->assign('data_registro', datetime_date_ptbr($vet2[1]));
		$tpl->assign('hora_registro', datetime_time_full_ptbr($vet2[1]));
		$tpl->assign('telefone', stripslashes($vet2[7]));

		$status = $vet2[6];
		if ($status == 1) {
			$tpl->assign('status', 'Não lido');
			$tpl->newBlock('mensagem-nao-lida');
			$tpl->assign('id_mensagem', $vet2[0]);
			
		} elseif ($status == 2) {
			$tpl->assign('status', 'Lido');
		}	

	} else {
		header("Location: mensagem");
	}

} else {
	header("Location: mensagem");
}
 
$tpl->printToScreen();
?>