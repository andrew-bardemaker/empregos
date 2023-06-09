<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/promocoes-editar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 10;
$act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

$sql = "SELECT * FROM bejobs_promo WHERE id = $id"; // print_r($sql);
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	$vet = $dba->fetch($query);
	$tpl->assign('id', $vet['id']);
	$tpl->assign('titulo', stripslashes($vet['titulo']));
	$tpl->assign('descricao', stripslashes($vet['descricao']));	

	$id_produto = $vet['id_produto'];

	$sql4   = "SELECT id, titulo, codigo FROM bejobs_produtos ORDER BY codigo";
	$query4 = $dba->query($sql4);
	$qntd4  = $dba->rows($query4);
	if ($qntd4 > 0) {
		for ($g=0; $g<$qntd4; $g++) {
			$vet4 = $dba->fetch($query4);
			$tpl->newBlock('produtos');
			$tpl->assign('id', $vet4[0]);
			$tpl->assign('titulo', stripslashes($vet4[1]));
			$tpl->assign('codigo', $vet4[2]);

			if ($id_produto == $vet4[0]) {
				$tpl->assign('selected', 'selected');
			}
		}
	}

	if (file_exists('../images/promo/'.$id.'.jpg')) {
		$tpl->newBlock('img');
		$tpl->assign('id', $vet['id']);
		$tpl->assign('date', date('YmdHmi'));
	} else {
		$tpl->newBlock('noimg');
	}


} else {
	header('Location: ./promocoes');
}


//--------------------------
$tpl->printToScreen();
?>