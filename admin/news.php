<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/news.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 7;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts s 
$sql = "SELECT id, titulo, data, status FROM bejobs_news ORDER BY data"; 
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('news');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('titulo', stripslashes($vet[1]));
		$tpl->assign('data', dataBR($vet[2]));

		$status = $vet[3];
		if ($status == 0) {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarNews('.$vet[0].',\'ativar_news\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: News inativa" class="tooltips"><i class="fa fa-long-arrow-down"></i></a>');
		} else {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarNews('.$vet[0].',\'desativar_news\', \''.$vet[1].'\')" data-toggle="tooltip" title="Status: News ativa" class="tooltips"><i class="fa fa-check"></i></a>');
		}
	}
} 

//--------------------------
$tpl->printToScreen();
?>