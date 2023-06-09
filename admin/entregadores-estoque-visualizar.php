<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/entregadores-estoque-visualizar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 2;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

if (!is_numeric($id)) {
	header("Location: ./entregadores-estoque");
}

$sql   = "SELECT id, id_entregadores, id_produto, qtde, data_registro FROM bejobs_entregadores_estoque WHERE id = $id"; // or die(); // print_r($sql);
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	$vet = $dba->fetch($query);
	$tpl->assign('id', $vet[0]);
		$tpl->assign('data', dataBR($vet[4]));
		$tpl->assign('qtde', $vet[3]);

		$id_entregadores = $vet[1];
		$sql1   = "SELECT nome FROM bejobs_entregadores WHERE id='$id_entregadores'";
        $query1 = $dba->query($sql1);
        $vet1   = $dba->fetch($query1);
        $tpl->assign('entregador', $vet1[0]);

		$id_produto      = $vet[2];
		$sql2   = "SELECT titulo FROM bejobs_produtos WHERE id='$id_produto'";
        $query2 = $dba->query($sql2);
        $vet2   = $dba->fetch($query2);
        $tpl->assign('produto', $vet2[0]);

} else {
	header("Location: ./entregadores-estoque");
}

//--------------------------
$tpl->printToScreen();
?>