<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/entregadores.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 4;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql   = "SELECT id, nome, login, status FROM bejobs_entregadores ORDER BY nome DESC";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('entregadores');
		$tpl->assign('id', stripslashes($vet[0]));
		$tpl->assign('nome', stripslashes($vet[1]));
		$tpl->assign('login', stripslashes($vet[2]));

		if ($vet[3] == 0) { // Verifica status
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarEntregadores('.$vet[0].',\'ativar_entregadores\', \''.$vet[1].'\')" data-toggle="tooltip" title="Entregador Inativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>');
		} else {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarEntregadores('.$vet[0].',\'desativar_entregadores\', \''.$vet[1].'\')" data-toggle="tooltip" title="Entregador Ativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>');
		}
	}
}

$tpl->printToScreen();
?>