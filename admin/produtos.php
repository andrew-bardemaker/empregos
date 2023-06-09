<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/produtos.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 9;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql   = "SELECT a.id, a.titulo, b.titulo, c.titulo, a.status, a.destaque, a.codigo, a.preco, a.preco_promo
          FROM bejobs_produtos AS a
          INNER JOIN bejobs_produtos_categorias AS b
          INNER JOIN bejobs_produtos_marcas AS c
          WHERE a.id_categoria = b.id
          AND a.id_marca = c.id
          ORDER BY codigo";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('produtos');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('codigo', $vet[6]);
		$tpl->assign('titulo', stripslashes($vet[1]));
		$tpl->assign('categoria', stripslashes($vet[2]));
		$tpl->assign('marca', stripslashes($vet[3]));

		$tpl->assign('preco', $vet[7]);
		$tpl->assign('preco_promo', $vet[8]);

		$status = $vet[4];
		if ($status == 0) {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarProdutos('.$vet[0].',\'ativar_produto\', \''.addcslashes($vet[1], "'").'\')" data-toggle="tooltip" title="Status: Produto inativo" class="tooltips"><i class="fa fa-long-arrow-down"></i></a>');
		}else{
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarProdutos('.$vet[0].',\'desativar_produto\', \''.addcslashes($vet[1], "'").'\')" data-toggle="tooltip" title="Status: Produto ativo" class="tooltips"><i class="fa fa-check"></i></a>');
		}

		$destaque = $vet[5];
		if ($destaque == 0) {
			$tpl->assign('destaque', '<a href="javascript:void(0)" onclick="destaqueProdutosSim('.$vet[0].',\'produtos_destaque_sim\', \''.addcslashes($vet[1], "'").'\')" data-toggle="tooltip" title="Destaque: Produto inativo" class="tooltips"><i class="fa fa-star-o"></i></a>');
		} else {
			$tpl->assign('destaque', '<a href="javascript:void(0)" onclick="destaqueProdutosNao('.$vet[0].',\'produtos_destaque_nao\', \''.addcslashes($vet[1], "'").'\')" data-toggle="tooltip" title="Destaque: Produto ativo" class="tooltips"><i class="fa fa-star"></i></a>');
		}
	}
} 

$sql2   = "SELECT id, titulo FROM bejobs_produtos_marcas ORDER BY titulo";
$query2 = $dba->query($sql2);
$qntd2  = $dba->rows($query2);
if ($qntd2 > 0) {
	for ($i=0; $i<$qntd2; $i++) {
		$vet2 = $dba->fetch($query2);
		$tpl->newBlock('marcas');
		$tpl->assign('id', $vet2[0]);
		$tpl->assign('titulo', $vet2[1]);
	}
}

$sql1   = "SELECT id, titulo FROM bejobs_produtos_categorias ORDER BY titulo";
$query1 = $dba->query($sql1);
$qntd1  = $dba->rows($query1);
if ($qntd1 > 0) {
	for ($i=0; $i<$qntd1; $i++) {
		$vet1 = $dba->fetch($query1);
		$tpl->newBlock('categorias');
		$tpl->assign('id', $vet1[0]);
		$tpl->assign('titulo', $vet1[1]);
	}
}

//--------------------------
$tpl->printToScreen();
?>