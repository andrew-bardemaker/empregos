<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/horarios-funcionamento.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 5;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql   = "SELECT id, data_hora_ini, data_hora_fim, status FROM bejobs_horarios_funcionamento ORDER BY data_hora_ini ASC";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('horarios_funcionamento');
		$tpl->assign('id', stripslashes($vet[0]));
		$tpl->assign('data_hora_ini', datetime_date_ptbr($vet[1]).' '.datetime_time_full_ptbr($vet[1]));
		$tpl->assign('data_hora_fim', datetime_date_ptbr($vet[2]).' '.datetime_time_full_ptbr($vet[2]));

		if ($vet[3] == 0) { // Verifica status
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarHorarioFuncionamento('.$vet[0].',\'ativar_horario_funcionamento\', \''.$vet[1].' '.$vet[2].'\')" data-toggle="tooltip" title="Horário de Funcionamento Inativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>');
			
		} else {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarHorarioFuncionamento('.$vet[0].',\'desativar_horario_funcionamento\', \''.$vet[1].' '.$vet[2].'\')" data-toggle="tooltip" title="Horário de Funcionamento Ativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>');
		}
	}
}

//--------------------------
$tpl->printToScreen();
?>a