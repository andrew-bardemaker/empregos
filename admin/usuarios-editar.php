<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/usuarios-editar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
// $id_pagina_permissao = 21;
// $act_permissao_editar = true;
// require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

$sql2   = "SELECT * FROM bejobs_usuarios WHERE id = $id";
$query2 = $dba->query($sql2);
$qntd2  = $dba->rows($query2);
if ($qntd2 > 0) {
	$vet2 = $dba->fetch($query2);
	$tpl->assign('id', $vet2['id']);
	$tpl->assign('nome', stripslashes($vet2['nome']));
	$tpl->assign('cpf', $vet2['cpf']);
	$tpl->assign('email', stripslashes($vet2['email']));

	// $lojas_cnpj = $vet2['lojas_cnpj'];
	// $lojas_cnpj = preg_replace("/[^0-9]/", "", $lojas_cnpj);
	// $tpl->assign('lojas_cnpj', $lojas_cnpj);

	// $sql3   = "SELECT razao_social FROM bejobs_lojas WHERE cnpj = '$lojas_cnpj'";
	// $query3 = $dba->query($sql3);
	// $vet3   = $dba->fetch($query3);
	// $tpl->assign('lojas_nome_fantasia', stripslashes($vet3[0]));


	$tpl->assign('telefone_celular', $vet2['telefone_celular']);

	if($vet2['nascimento']!='0000-00-00'){
		$nascimento = dataBR($vet2['nascimento']);
	} else {
		$nascimento = '';
	}
	$tpl->assign('nascimento', $nascimento);

	// // Reputação
	// $reputacao = $vet2['id_reputacao'];
	// $sql3   = "SELECT id, titulo FROM bejobs_usuarios_reputacao ORDER BY titulo";
	// $query3 = $dba->query($sql3);
	// $qntd3  = $dba->rows($query3);
	// if ($qntd3 > 0) {
	// 	for ($h=0; $h<$qntd3; $h++) {
	// 		$vet3 = $dba->fetch($query3);
	// 		$options_reputacao[]  = $vet3['id'];
	// 		$options_reputacao2[] = $vet3['titulo'];		
	// 	}

	// 	$output_reputacao = '';
	// 	for ($j=0; $j<count($options_reputacao); $j++) {
	// 	  $output_reputacao .= '<option value="'.$options_reputacao[$j].'" ' 
	// 	             . ( $reputacao == $options_reputacao[$j] ? 'selected="selected"' : '' ) . '>' 
	// 	             . $options_reputacao2[$j] 
	// 	             . '</option>';
	// 	}
	// 	$tpl->assign('reputacao',  $output_reputacao);
	// }	

	// $acesso_gerente = $vet2['acesso_gerente'];
	// $options_acesso_gerente1 = array('Sim','Não');
	// $options_acesso_gerente2 = array('1','2');
	// $output_acesso_gerente = '';
	// for( $i=0; $i<count($options_acesso_gerente1); $i++ ) {
	//   $output_acesso_gerente .= '<option value="'.$options_acesso_gerente2[$i].'" ' 
	//              . ( $acesso_gerente == $options_acesso_gerente2[$i] ? 'selected="selected"' : '' ) . '>' 
	//              . $options_acesso_gerente1[$i] 
	//              . '</option>';
	// }	
	// $tpl->assign('acesso_gerente',  $output_acesso_gerente);

	// $tpl->assign('telefone_fixo', $vet2['telefone_fixo']);
	// $tpl->assign('telefone_celular', $vet2['telefone_celular']);

	// $status_email = $vet2['status_email'];
	// $options_status_email = array('1','2');
	// $options_status_email2 = array('Sim','Não');
	// $output_status_email = '';
	// for( $i=0; $i<count($options_status_email); $i++ ) {
	//   $output_status_email .= '<option value="'.$options_status_email[$i].'" ' 
	//              . ( $status_email == $options_status_email[$i] ? 'selected="selected"' : '' ) . '>' 
	//              . $options_status_email2[$i] 
	//              . '</option>';
	// }	
	// $tpl->assign('status_email',  $output_status_email);

	
	// $tpl->assign('endereco', stripslashes($vet2['endereco']));
	// $tpl->assign('numero', $vet2['numero']);
	// $tpl->assign('complemento', stripslashes($vet2['complemento']));
	// $tpl->assign('bairro', stripslashes($vet2['bairro']));
	// $tpl->assign('cep', $vet2['cep']);
	// $tpl->assign('cidade', stripslashes($vet2['cidade']));
	// $tpl->assign('estado', stripslashes($vet2['estado']));
	// $tpl->assign('senha', $vet2['senha']);

	/**
	 *
	 * Memorando
	 *
	 */
	$tpl->gotoBlock('_ROOT');
	$tpl->assign('memorando', stripslashes($vet2['memorando']));

	
}

//--------------------------

$tpl->printToScreen();
?>