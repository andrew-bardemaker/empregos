<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/chamados.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 1;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$status     = ""; // Variável p/ salvar id do status
$sql_status = "";
if (isset($_GET['status']) && !empty($_GET['status'])) {
	$status = $_GET['status'];
	$sql_status = " AND status = ".$status;
}

// $tipo_usuario     = ""; // Variável p/ salvar id do tipo_usuario
// $sql_tipo_usuario = "";
// if (isset($_GET['tipo_usuario']) && !empty($_GET['tipo_usuario'])) {
// 	$tipo_usuario = $_GET['tipo_usuario'];
// 	$sql_tipo_usuario = " AND tipo_usuario = ".$tipo_usuario;
// }

$sql = "SELECT id, status, data_registro, id_usuario FROM bejobs_chamados WHERE 1 $sql_status ORDER BY data_registro DESC"; // print_r($sql);
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('chamados');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('data_registro', datetime_date_ptbr($vet[2])." ".datetime_time_ptbr($vet[2]));

		$status2 = $vet[1];
		if ($status2 == 1) {
			$tpl->assign('status', 'Aberto');
		} elseif ($status2 == 2) {
			$tpl->assign('status', 'Atualizado');
		} elseif ($status2 == 3) {
			$tpl->assign('status', 'Aguardando seu retorno');
		} elseif ($status2 == 4) {
			$tpl->assign('status', 'Resolvido');
		} elseif ($status2 == 5) {
			$tpl->assign('status', 'Fechado');
		}

		$id_usuario   = $vet[3];

		$sql4 = "SELECT usuarios.nome, usuarios.cpf FROM bejobs_usuarios AS usuarios WHERE usuarios.id = $id_usuario";
		$query4 = $dba->query($sql4);
		$qntd4 = $dba->rows($query4);
		if ($qntd4 > 0) {
			// $tpl->newBlock('usuarios_pf');
			$vet4 = $dba->fetch($query4);
			$tpl->assign('usuario_nome', $vet4[0]);	
			$tpl->assign('usuario_cpf', $vet4[1]);
			// $tpl->assign('usuario_tipo', 'Pessoa Física');
		}

		// $tipo_usuario2 = $vet[4];

		// if ($tipo_usuario2 == 2) {

		// 	// Verifica se usuário bonificado é PF
		// 	$sql4 = "SELECT usuarios_pf.nome, usuarios_pf.cpf FROM bejobs_usuarios_pf AS usuarios_pf WHERE usuarios_pf.id = $id_usuario";
		// 	$query4 = $dba->query($sql4);
		// 	$qntd4 = $dba->rows($query4);
		// 	if ($qntd4 > 0) {
		// 		// $tpl->newBlock('usuarios_pf');
		// 		$vet4 = $dba->fetch($query4);
		// 		$tpl->assign('usuario_nome', $vet4[0]);	
		// 		$tpl->assign('usuario_cpf_cnpj', $vet4[1]);
		// 		$tpl->assign('usuario_tipo', 'Pessoa Física');
		// 	}

		// } elseif ($tipo_usuario2 == 3) {

		// 	// Verifica se usuário bonificado é PJ
		// 	$sql5 = "SELECT usuarios_pj.nome_fantasia, usuarios_pj.cnpj FROM bejobs_usuarios_pj AS usuarios_pj WHERE usuarios_pj.id = $id_usuario";
		// 	$query5 = $dba->query($sql5);
		// 	$qntd5 = $dba->rows($query5);
		// 	if ($qntd5 > 0) {
		// 		// $tpl->newBlock('usuarios_pj');
		// 		$vet5 = $dba->fetch($query5);
		// 		$tpl->assign('usuario_nome', $vet5[0]);	
		// 		$tpl->assign('usuario_cpf_cnpj', $vet5[1]);
		// 		$tpl->assign('usuario_tipo', 'Pessoa Jurídica');
		// 	}

		// } elseif ($tipo_usuario2 == 1) {

		// 	// Verifica se usuário bonificado é Colaborador
		// 	$sql6 = "SELECT usuarios_colaboradores.nome, usuarios_colaboradores.cpf FROM bejobs_usuarios_colaboradores AS usuarios_colaboradores WHERE usuarios_colaboradores.id = $id_usuario";
		// 	$query6 = $dba->query($sql6);
		// 	$qntd6 = $dba->rows($query6);
		// 	if ($qntd6 > 0) {
		// 		// $tpl->newBlock('usuarios_colaboradores');
		// 		$vet6 = $dba->fetch($query6);
		// 		$tpl->assign('usuario_nome', $vet6[0]);	
		// 		$tpl->assign('usuario_cpf_cnpj', $vet6[1]);
		// 		$tpl->assign('usuario_tipo', 'Colaboradores');
		// 	}
		// }
	}
}

$sql2 = "SELECT id, titulo FROM bejobs_chamados_categorias ORDER BY titulo";
$query2 = $dba->query($sql2);
$qntd2 = $dba->rows($query2);
if ($qntd2 > 0) {
	for ($j=0; $j<$qntd2; $j++) {
		$vet2 = $dba->fetch($query2);
		$tpl->newBlock('chamados_categorias');
		$tpl->assign('id', $vet2[0]);
		$tpl->assign('titulo', $vet2[1]);
	}
} 

// Filtros de Busca
$tpl->gotoBlock('_ROOT');

$options_status  = array('','1','2','3','4','5');
$options_status2 = array('Todos','Aberto','Atualizado','Aguardando seu retorno','Resolvido','Fechado');
$output_status = "";
for ($p=0; $p < count($options_status); $p++) {
  $output_status .= '<option value="'.$options_status[$p].'" ' 
	             . ( $status == $options_status[$p] ? 'selected="selected"' : '' ) . '>' 
	             . $options_status2[$p] 
	             . '</option>';
}	
$tpl->assign('status',  $output_status);

// $options_tipo_usuario  = array('','1','2','3');
// $options_tipo_usuario2 = array('Todos','Colaboradores','Pessoa Física','Pessoa Jurídica');
// $output_tipo_usuario = "";
// for ($p=0; $p < count($options_tipo_usuario); $p++) {
//   $output_tipo_usuario .= '<option value="'.$options_tipo_usuario[$p].'" ' 
// 	             . ( $tipo_usuario == $options_tipo_usuario[$p] ? 'selected="selected"' : '' ) . '>' 
// 	             . $options_tipo_usuario2[$p] 
// 	             . '</option>';
// }	
// $tpl->assign('tipo_usuario',  $output_tipo_usuario);

//--------------------------
$tpl->printToScreen();
?>