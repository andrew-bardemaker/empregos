<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/aditivos-regulamento-analytics.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 12;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

$sql = "SELECT * FROM bejobs_aditivos_regulamento WHERE id = $id"; // print_r($sql);
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	$vet = $dba->fetch($query);
	$tpl->assign('titulo', stripslashes($vet['titulo']));
	$tpl->assign('texto', stripslashes($vet['texto']));
}

$sql = "SELECT usuarios.nome, usuarios.cpf, aditivos_regulamento_usuarios.ip_registro, aditivos_regulamento_usuarios.data_registro
		FROM bejobs_aditivos_regulamento AS aditivos_regulamento
		INNER JOIN bejobs_usuarios AS usuarios 
		INNER JOIN bejobs_aditivos_regulamento_usuarios AS aditivos_regulamento_usuarios
		WHERE aditivos_regulamento_usuarios.id_aditivos_regulamento = aditivos_regulamento.id		
		AND aditivos_regulamento_usuarios.id_usuario = usuarios.id
		AND aditivos_regulamento.id = $id
		ORDER BY aditivos_regulamento_usuarios.data_registro DESC"; // print_r($sql);
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i < $qntd; $i++) { 
		$vet = $dba->fetch($query);
		$tpl->newBlock('usuarios');
		$tpl->assign('nome', stripslashes($vet[0]));
		$tpl->assign('cpf', $vet[1]);
		$tpl->assign('ip_registro', stripslashes($vet[2]));
		$tpl->assign('data_registro', datetime_date_ptbr($vet[3])." ".datetime_time_ptbr($vet[3]));
	}	
} else {
	$tpl->newBlock('nousuarios');
}

$tpl->gotoBlock('_ROOT');
$tpl->assign('total_usuarios', $qntd);

//--------------------------
$tpl->printToScreen();
?>