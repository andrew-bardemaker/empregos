<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/promocoes.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 10;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

//listagem promoções
$sql = "SELECT id, titulo, status FROM bejobs_promo ORDER BY titulo";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('promocoes');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('titulo', $vet[1]);
		
		if ($vet[2] == 0) { // Verifica status
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarPromocao('.$vet[0].',\'ativar_promocao\', \''.$vet[1].'\')" data-toggle="tooltip" title="Promoção Inativa" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>');
		} else {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarPromocao('.$vet[0].',\'desativar_promocao\', \''.$vet[1].'\')" data-toggle="tooltip" title="Promoção Ativa" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>');
		}

	}
} 

// Produtos
$sql2   = "SELECT id, titulo, codigo FROM bejobs_produtos ORDER BY codigo";
$query2 = $dba->query($sql2);
$qntd2  = $dba->rows($query2);
if ($qntd2 > 0) {
	for ($j=0; $j<$qntd2; $j++) {
		$vet2 = $dba->fetch($query2);
		$tpl->newBlock('produtos');
		$tpl->assign('id', $vet2[0]);
		$tpl->assign('titulo', stripslashes($vet2[1]));
		$tpl->assign('codigo', $vet2[2]);
	}
}

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