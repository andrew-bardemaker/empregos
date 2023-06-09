<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/compras.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 2;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql = "SELECT id, chave_acesso, data_emissao FROM bejobs_compras WHERE 1 ORDER BY data_emissao DESC";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {	
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('compras');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('chave_acesso', $vet[1]);
		$tpl->assign('data', datetime_date_ptbr($vet[2]));
		$tpl->assign('hora', datetime_time_ptbr($vet[2]));
	}
}

// Entregadores
// $sql2   = "SELECT id, nome FROM bejobs_entregadores ORDER BY id";
// $query2 = $dba->query($sql2);
// $qntd2  = $dba->rows($query2);
// if ($qntd2 > 0) {
// 	for ($j=0; $j<$qntd2; $j++) {
// 		$vet2 = $dba->fetch($query2);
// 		$tpl->newBlock('entregadores');
// 		$tpl->assign('id', $vet2[0]);
// 		$tpl->assign('nome', stripslashes($vet2[1]));

// 		$tpl->newBlock('entregadores2');
// 		$tpl->assign('id', $vet2[0]);
// 		$tpl->assign('nome', stripslashes($vet2[1]));
// 	}
// }

// Grupos de Usuários
// $sql4   = "SELECT id, titulo FROM bejobs_grupos ORDER BY titulo";
// $query4 = $dba->query($sql4);
// $qntd4  = $dba->rows($query4);
// if ($qntd4 > 0) {
// 	for ($g=0; $g<$qntd4; $g++) {
// 		$vet4 = $dba->fetch($query4);
// 		$tpl->newBlock('grupos_usuarios');
// 		$tpl->assign('id', $vet4[0]);
// 		$tpl->assign('titulo', stripslashes($vet4[1]));
// 	}
// } 

//--------------------------
$tpl->printToScreen();
?>