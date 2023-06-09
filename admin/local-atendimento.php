<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/local-atendimento.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 16;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$sql   = "SELECT id, name, status FROM bejobs_locais_atendimento";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('atendimento');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('nome', $vet[1]);
		$tpl->assign('rua', $vet[2]);
		$tpl->assign('bairro', $vet[3]);
		$tpl->assign('cidade', $vet[4]);
		$tpl->assign('estado', $vet[5]);

		$status = $vet[6];
		if ($status == 0) {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarUsuario('.$vet[0].',\'ativar_usuarios_admin\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: Usuário inativo" class="tooltips"><i class="fa fa-long-arrow-down"></i></a>');
		} else {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarUsuario('.$vet[0].',\'desativar_usuarios_admin\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: Usuário ativo" class="tooltips"><i class="fa fa-check"></i></a>');
		}
	}
} 


//--------------------------
$tpl->printToScreen();
?>