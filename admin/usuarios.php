<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/usuarios.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 17;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql = "SELECT u.id, u.nome, u.cpf, u.telefone_celular, u.data_cadastro, u.email, u.nascimento, u.status
        FROM bejobs_usuarios AS u
        ORDER BY u.data_cadastro DESC";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('usuarios');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('nome', stripslashes($vet[1]));
		$tpl->assign('cpf', $vet[2]);
		$tpl->assign('telefone_celular', $vet[3]);
		$tpl->assign('data_cadastro', datetime_date_ptbr($vet[4])." ".datetime_time_ptbr($vet[4]));
		$tpl->assign('email', $vet[5]);
		$tpl->assign('nascimento', dataBR($vet[6]));

		if ($vet[7] == 0) { // Verifica status
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarUsuario('.$vet[0].',\'ativar_usuarios\', \''.$vet[1].'\')" data-toggle="tooltip" title="Usuário Inativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>');
		} else {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarUsuario('.$vet[0].',\'desativar_usuarios\', \''.$vet[1].'\')" data-toggle="tooltip" title="Usuário Ativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>');
		}
	}
}

$tpl->printToScreen();
?>