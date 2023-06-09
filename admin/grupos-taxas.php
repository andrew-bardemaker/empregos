<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/grupos-taxas.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 3;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql = "SELECT id, titulo FROM bejobs_grupos_taxas ORDER BY titulo";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('grupos');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('titulo', stripslashes($vet[1]));
		// if ($vet[2] == 1) {
		// 	$tpl->assign('status_bonificacao', 'Ativo');
		// } elseif ($vet[2] == 2) {
		// 	$tpl->assign('status_bonificacao', 'Bloqueado');
		// }

		$tpl->newBlock('grupos2');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('titulo', stripslashes($vet[1]));

		$tpl->newBlock('grupos3');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('titulo', stripslashes($vet[1]));
	}
} 

//--------------------------
$tpl->printToScreen();
?>