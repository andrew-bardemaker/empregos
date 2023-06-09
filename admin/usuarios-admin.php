<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/usuarios-admin.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 16;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$sql   = "SELECT id, name, status FROM bejobs_usuario_admin";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('usuarios');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('nome', $vet[1]);

		$status = $vet[2];
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