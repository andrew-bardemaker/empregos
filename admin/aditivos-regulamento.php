<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/aditivos-regulamento.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 12;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql = "SELECT id, titulo, data_registro, status FROM bejobs_aditivos_regulamento ORDER BY titulo";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('aditivos_regulamento');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('titulo', stripslashes($vet[1]));
		$tpl->assign('data_registro', datetime_date_ptbr($vet[2]));

		$status = $vet[3];
		if ($status == 0) {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarAditivosRegulamento('.$vet[0].',\'aditivos_regulamento_ativar\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: Inativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>');
		}else{
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarAditivosRegulamento('.$vet[0].',\'aditivos_regulamento_desativar\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: Ativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>');
		}

	}
}

//--------------------------
$tpl->printToScreen();
?>