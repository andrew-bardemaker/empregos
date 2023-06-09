<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/banners.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 22;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql   = "SELECT * FROM bejobs_banners ORDER BY posicao";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('banners');	
		$tpl->assign('id', $vet['id']);	
		$tpl->assign('posicao', $vet['posicao']);			
		$tpl->assign('time', time());	
		
		$status = $vet['status'];
		if ($status == 0) {
			$tpl->assign('status', '<li><a href="javascript:void(0)" onclick="ativarBanner('.$vet['id'].',\'ativar_banner\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: Banner inativo" class="tooltips"><i class="fa fa-long-arrow-down"></i>Ativar</a></li>');
			$tpl->assign('status_info', '<b class="badge alert-danger">Status: Inativo</b>');
		} else {
			$tpl->assign('status', '<li><a href="javascript:void(0)" onclick="desativarBanner('.$vet['id'].',\'desativar_banner\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: Banner ativo" class="tooltips"><i class="fa fa-check"></i>Desativar</a></li>');
			$tpl->assign('status_info', '<b class="badge alert-success">Status: Ativo</b>');
		}
	}
}

//--------------------------
$tpl->printToScreen();
?>