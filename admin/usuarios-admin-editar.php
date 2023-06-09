<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/usuarios-admin-editar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 16;
$act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); // Monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); // Mensagens e alertas

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

$sql = "SELECT * FROM bejobs_usuario_admin WHERE id = $id"; //or die();
//print_r($sql);
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	$vet = $dba->fetch($query);
	$tpl->assign('id', $vet['id']);
	$tpl->assign('nome', stripslashes($vet['name']));
	$tpl->assign('user', stripslashes($vet['user']));
	$tpl->assign('pass', stripslashes($vet['pass']));

	// Acesso Painel
	$acess_panel = $vet['acess_panel'];
	$options_acess_panel = array('1','2');
	$options_acess_panel2 = array('Ativo','Inativo');
	$output_acess_panel = '';
	for( $i=0; $i<count($options_acess_panel); $i++ ) {
	  $output_acess_panel .= '<option value="'.$options_acess_panel[$i].'" ' 
	             . ( $acess_panel == $options_acess_panel[$i] ? 'selected="selected"' : '' ) . '>' 
	             . $options_acess_panel2[$i] 
	             . '</option>';
	}	
	$tpl->assign('acess_panel',  $output_acess_panel);

} else {
	header('Location: ./usuarios');
}

//--------------------------
$tpl->printToScreen();
?>