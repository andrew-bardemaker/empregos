<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/vendas.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 18;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

/** Período de consulta **/
if (  (!empty($_GET['data_inicio']) && isset($_GET['data_inicio'])) && (!empty($_GET['data_fim']) && isset($_GET['data_fim'])) ) {
	$data_inicio = dataMY($_GET['data_inicio']).' 00:00:00';
	$data_fim    = dataMY($_GET['data_fim']).' 23:59:59';
	$periodo     = " AND data_criacao BETWEEN '$data_inicio' AND '$data_fim'";

	$tpl->assign('data_inicio', $_GET['data_inicio']);
	$tpl->assign('data_fim', $_GET['data_fim']);

} else {
	$data_inicio = date('Y-m-01 00:00:00');
	$data_fim    = date('Y-m-d 23:59:59');
	$periodo     = " AND data_criacao BETWEEN '$data_inicio' AND '$data_fim'";

	$tpl->newBlock('block-30-dias'); // Aviso de transações do mês corrente
}


$sql = "SELECT id, data_criacao, status
	     FROM bejobs_pedidos   
	     $periodo 
		 ORDER BY data_criacao DESC";

$query = $dba->query($sql);
$qntd  = $dba->rows($query);


if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('vendas');
		$tpl->assign('id', $vet['id']);
		$tpl->assign('data_hora_registro', datetime_date_ptbr($vet['data_criacao']).' '.datetime_time_ptbr($vet['data_criacao']));

		$status = $vet['status'];
	}
	
}

//--------------------------
$tpl->printToScreen();
?>