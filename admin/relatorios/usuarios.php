<?php
require_once('../inc/inc.verificasession.php');
require_once('../inc/class.TemplatePower.php');
require_once('../inc/inc.configdb.php');
require_once('../inc/inc.lib.php');

$tpl = new TemplatePower('../tpl/default.html');
$tpl->assignInclude('content', './tpl/usuarios.html');
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

	$periodo_sql = "AND u.data_cadastro BETWEEN '$data_ini' AND '$data_fim'"; // Monta sql com datas filtradas no período

} 

if ( !empty($periodo_sql)) {
	$sql20   = "SELECT 
				u.nome, 
				u.cpf, 
				u.email, 
				u.telefone_celular, 
				u.nascimento, 
				u.data_cadastro, 
				u.status, 
				u.id
				FROM bejobs_usuarios AS u
				WHERE 1 $periodo_sql
				ORDER BY u.nome";
	$query20 = $dba->query($sql20);
	$qntd20  = $dba->rows($query20);
	if ($qntd20 > 0) {
		for ($y=0; $y<$qntd20; $y++) {
			$vet20 = $dba->fetch($query20);
			$tpl->newBlock('usuarios');
			$id_usuario = $vet20[7];
			
			$tpl->assign('nome', stripslashes($vet20[0]));
			$tpl->assign('cpf', $vet20[1]);
			$tpl->assign('email', stripslashes($vet20[2]));
			$tpl->assign('telefone_celular', stripslashes($vet20[3]));
			$tpl->assign('nascimento', dataBR($vet20[4]));
			$tpl->assign('data_cadastro', datetime_date_ptbr($vet20[5])." ".datetime_time_ptbr($vet20[5])); 
			$status_usuario = $vet20[6];
			if ($status_usuario == 1) {
				$tpl->assign('status', 'Ativo');
			} else {
				$tpl->assign('status', 'Bloqueado');
			}
			
		}
	} else {
		$tpl->newBlock('no-usuarios');
	}
} else {
	$tpl->newBlock('no-usuarios');
}

$tpl->printToScreen();
?>