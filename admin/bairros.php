<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/bairros.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 21;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql = "SELECT id, titulo, status FROM bejobs_bairros ORDER BY titulo";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('bairros');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('titulo', $vet[1]);

		if ($vet[2] == 0) { // Verifica status
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarBairros('.$vet[0].',\'ativar_bairro\', \''.$vet[1].'\')" data-toggle="tooltip" title="Bairro Inativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>');
		} else {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarBairros('.$vet[0].',\'desativar_bairro\', \''.$vet[1].'\')" data-toggle="tooltip" title="Bairro Ativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>');
		}
	}
}

//--------------------------
$tpl->printToScreen();
?>