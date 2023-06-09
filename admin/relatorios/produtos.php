<?php
require_once('../inc/inc.verificasession.php');
require_once('../inc/class.TemplatePower.php');
require_once('../inc/inc.configdb.php');
require_once('../inc/inc.lib.php');

$tpl = new TemplatePower('../tpl/default.html');
$tpl->assignInclude('content', './tpl/produtos.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 13;
// $act_permissao_editar = true;
require_once('../inc/inc.verificaacessopermissao.php');

include_once('../menu.php'); //monta o menu de acordo com o usuário
include_once('../inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

// Select com períodos de filtro
$tpl->assign('custom', date('d/m/Y'));
$tpl->assign('today', date('d/m/Y'));
$tpl->assign('yesterday', date('d/m/Y', strtotime('- 1 days')));
$tpl->assign('last_week', date('d/m/Y', strtotime('- 7 days')));
$tpl->assign('last_30_days', date('d/m/Y', strtotime('- 30 days')));
$tpl->assign('last_6_months', date('d/m/Y', strtotime('- 6 months')));
$tpl->assign('last_12_months', date('d/m/Y', strtotime('- 12 months')));

$periodo = "";
if (isset($_GET['periodo']) && !empty($_GET['periodo'])) {
	$periodo = $_GET['periodo'];
}

switch ($periodo) {
	case 'custom':
		$tpl->assign('custom_selected', 'selected');
		break;
	case 'today':
		$tpl->assign('today_selected', 'selected');
		break;
	case 'yesterday':
		$tpl->assign('yesterday_selected', 'selected');
		break;
	case 'last_week':
		$tpl->assign('last_week_selected', 'selected');
		break;
	case 'last_30_days':
		$tpl->assign('last_30_days_selected', 'selected');
		break;
	case 'last_6_months':
		$tpl->assign('last_6_months_selected', 'selected');
		break;
	case 'last_12_months':
		$tpl->assign('last_12_months_selected', 'selected');
		break;
	default:
		// $tpl->assign('today_selected', 'selected');
		break;
}

// Data dos filtros
$periodo_sql = "";

if (isset($_GET['data_ini']) && !empty($_GET['data_ini']) && isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
	$tpl->assign('data_ini', $_GET['data_ini']);
	$tpl->assign('data_fim', $_GET['data_fim']);

	$data_ini = dataMY($_GET['data_ini'])." 00:00:00";
	$data_fim = dataMY($_GET['data_fim'])." 23:59:59";

	$periodo_sql = "AND a.data_hora_registro BETWEEN '$data_ini' AND '$data_fim'"; // Monta sql com datas filtradas no período
} 

$entregador     = ""; // Variável p/ salvar id do entregador
$entregador_sql = ""; // Variável p/ montar sql com id do entregador
if (isset($_GET['entregador']) && !empty($_GET['entregador'])) {
	$entregador     = $_GET['entregador'];
	$entregador_sql = "AND  a.id_entregadores = '".$_GET['entregador']."'";
	$tpl->assignGlobal('entregador',  $entregador);
}

$sql18   = "SELECT id, nome FROM bejobs_entregadores ORDER BY nome";
$query18 = $dba->query($sql18);
$qntd18  = $dba->rows($query18);
if ($qntd18 > 0) {
	for ($y=0; $y<$qntd18; $y++) {
		$vet18 = $dba->fetch($query18);
		$tpl->newBlock('entregadores');
		$tpl->assign('id', stripslashes($vet18[0]));
		$tpl->assign('nome', stripslashes($vet18[1]));	

		if ($entregador == stripslashes($vet18[0])) {
			$tpl->assign('selected', 'selected');
		}	
	}
}

$categoria     = ""; // Variável p/ salvar id do categoria
$categoria_sql = ""; // Variável p/ montar sql com id do categoria
if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
	$categoria     = $_GET['categoria'];
	$categoria_sql = "AND d.id = '".$_GET['categoria']."'";
	$tpl->assignGlobal('categoria',  $categoria);
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

		if ($vet1[0] == $categoria) {
			$tpl->assign('selected', 'selected');
		}
	}
}

$marca     = ""; // Variável p/ salvar id do marca
$marca_sql = ""; // Variável p/ montar sql com id do marca
if (isset($_GET['marca']) && !empty($_GET['marca'])) {
	$marca     = $_GET['marca'];
	$marca_sql = "AND e.id = '".$_GET['marca']."'";
	$tpl->assignGlobal('marca',  $marca);
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

		if ($vet2[0] == $marca) {
			$tpl->assign('selected', 'selected');
		}
	}
}

$produto     = ""; // Variável p/ salvar id do produto
$produto_sql = ""; // Variável p/ montar sql com id do produto
if (isset($_GET['produto']) && !empty($_GET['produto'])) {
	$produto     = $_GET['produto'];
	$produto_sql = "AND c.id = '".$_GET['produto']."'";
	$tpl->assignGlobal('produto',  $produto);
}

$sql20   = "SELECT id, titulo, codigo FROM bejobs_produtos ORDER BY codigo";
$query20 = $dba->query($sql20);
$qntd20  = $dba->rows($query20);
if ($qntd20 > 0) {
	for ($j=0; $j<$qntd20; $j++) {
		$vet20 = $dba->fetch($query20);
		$tpl->newBlock('produtos_');
		$tpl->assign('id', $vet20[0]);
		$tpl->assign('titulo', stripslashes($vet20[1]));
		$tpl->assign('codigo', $vet20[2]);

		if ($vet20[0] == $produto) {
			$tpl->assign('selected', 'selected');
		}
	}
}

if (!empty($periodo_sql)) {

	$total_vendas = 0;

	$sql20   = "SELECT c.id, c.codigo, c.titulo, d.titulo, e.titulo, SUM(b.qntd), SUM(b.valor_produto*b.qntd)
			    FROM bejobs_pedidos AS a 
			    INNER JOIN bejobs_pedidos_produtos AS b
			    INNER JOIN bejobs_produtos AS c
			    INNER JOIN bejobs_produtos_categorias AS d
          		INNER JOIN bejobs_produtos_marcas AS e
			    WHERE c.id_categoria = d.id
          		AND c.id_marca = e.id
          		AND c.id = b.id_produto
          		AND a.id = b.id_pedido
			    $periodo_sql 	
			    $entregador_sql		
			    $categoria_sql
				$marca_sql
				$produto_sql    
			    GROUP BY c.id
			    ORDER BY c.titulo";
	// print_r($sql20);
	$query20 = $dba->query($sql20);
	$qntd20  = $dba->rows($query20);
	if ($qntd20 > 0) {
		for ($y=0; $y<$qntd20; $y++) {
			$vet = $dba->fetch($query20);
			$tpl->newBlock('produtos');
			$tpl->assign('id', $vet[0]);
			$tpl->assign('codigo', $vet[1]);
			$tpl->assign('titulo', stripslashes($vet[2]));
			$tpl->assign('categoria', stripslashes($vet[3]));
			$tpl->assign('marca', stripslashes($vet[4]));

			$tpl->assign('qtde', $vet[5]);
			$tpl->assign('total_vendas', moeda($vet[6]));
			$total_vendas = $total_vendas + $vet[6];
			
		}

		$tpl->newBlock('total');
		$tpl->assign('total_vendas', moeda($total_vendas));	
		
	} else {
		$tpl->newBlock('no-produtos');
	}

} else {
	$tpl->newBlock('no-produtos');
}

$tpl->printToScreen();
?>