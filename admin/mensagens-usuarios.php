<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/mensagens-usuarios.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 1;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql   = "SELECT id, data_registro, titulo FROM bejobs_mensagens_usuarios ORDER BY data_registro DESC";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('mensagens');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('data_registro', datetime_date_ptbr($vet[1])." ".datetime_time_ptbr($vet[1]));
		$tpl->assign('assunto', stripslashes($vet[2]));
	}
}

// // Grupos de Usuários
$sql4   = "SELECT id, titulo FROM bejobs_grupos ORDER BY titulo";
$query4 = $dba->query($sql4);
$qntd4  = $dba->rows($query4);
if ($qntd4 > 0) {
	for ($g=0; $g<$qntd4; $g++) {
		$vet4 = $dba->fetch($query4);
		$tpl->newBlock('grupos_usuarios');
		$tpl->assign('id', $vet4[0]);
		$tpl->assign('titulo', stripslashes($vet4[1]));
	}
}  

 
//--------------------------
$tpl->printToScreen();
?>