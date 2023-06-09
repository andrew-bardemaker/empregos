<?php
include('./inc/inc.verificasession.php');
include('./inc/inc.configdb.php');
include('./inc/class.TemplatePower.php');
include('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html'); 
$tpl->assignInclude('content', './tpl/mensagens-usuarios-analytics.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 1;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include('./menu.php'); //monta o menu de acordo com o usuário
include('./inc/inc.mensagens.php'); //mensagens e alertas

$tpl->gotoBlock('_ROOT');

if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$id_mensagem = $_GET['id'];

	$sql2 = "SELECT * FROM bejobs_mensagens_usuarios WHERE id = $id_mensagem";
	$query2 = $dba->query($sql2);
	$qntd2 = $dba->rows($query2);
	if ($qntd2 > 0) {
		$vet2 = $dba->fetch($query2);
		$tpl->assign('id_mensagem', $vet2['id']);
		$tpl->assign('titulo', stripslashes($vet2['titulo']));
		$tpl->assign('texto', stripslashes($vet2['texto']));
		$tpl->assign('data_registro', datetime_date_ptbr($vet2['data_registro']));
		$tpl->assign('hora_registro', datetime_time_full_ptbr($vet2['data_registro']));

		$vet2['usuarios_proclube'] == 1 ? $tpl->assign('usuarios_proclube', 'checked="checked"') : $tpl->assign('usuarios_proclube', '');
		// $vet2['grupos_economicos'] == 1 ? $tpl->assign('grupos_economicos', 'checked="checked"') : $tpl->assign('grupos_economicos', '');
		// $vet2['lojas'] == 1 ? $tpl->assign('lojas', 'checked="checked"') : $tpl->assign('lojas', '');	
		$vet2['grupos_usuarios'] == 1 ? $tpl->assign('grupos_usuarios', 'checked="checked"') : $tpl->assign('grupos_usuarios', '');
		$vet2['usuarios_individual'] == 1 ? $tpl->assign('usuarios_individual', 'checked="checked"') : $tpl->assign('usuarios_individual', '');
		// $vet2['usuarios_reputacao'] == 1 ? $tpl->assign('usuarios_reputacao', 'checked="checked"') : $tpl->assign('usuarios_reputacao', '');

		// $id_grupo = $vet2['id_grupos'];

		// $sql1 = "SELECT titulo FROM bejobs_grupos WHERE id = $id_grupo";
		// $query1 = $dba->query($sql1);
		// $qntd1 = $dba->rows($query1);
		// if ($qntd1 > 0) {
		// 	$vet1 = $dba->fetch($query1);
		// 	$tpl->assign('grupo_usuarios', $vet1[0]);
		// }

		// $id_grupo_notificacoes = $vet2['id_grupos_notificacoes'];

		// $sql1 = "SELECT titulo FROM bejobs_grupos2 WHERE id = $id_grupo_notificacoes";
		// $query1 = $dba->query($sql1);
		// $qntd1 = $dba->rows($query1);
		// if ($qntd1 > 0) {
		// 	$vet1 = $dba->fetch($query1);
		// 	$tpl->assign('grupo_usuarios', $vet1[0]);
		// }

		$sql3 = "SELECT COUNT(id) FROM bejobs_notificacoes_usuarios WHERE id_mensagens_usuarios = $id_mensagem AND status = 2"; // print_r($sql3);
		$query3 = $dba->query($sql3);
		$vet3 = $dba->fetch($query3);
		$tpl->assign('total_lidas', $vet3[0]);

		$sql5 = "SELECT COUNT(id) FROM bejobs_notificacoes_usuarios WHERE id_mensagens_usuarios = $id_mensagem AND status = 1"; // print_r($sql5);
		$query5 = $dba->query($sql5);
		$vet5 = $dba->fetch($query5);
		$tpl->assign('total_nao_lidas', $vet5[0]);

		if (file_exists('../images/mensagens/'.$vet2['id'].'.jpg')) {
			$tpl->newBlock('img');
			$tpl->assign('id', $vet2['id']);
			$tpl->assign('date', date('YmdHmi'));
		} else {
			$tpl->newBlock('noimg');
		}

		$sql5   = "SELECT nu.status, u.nome, u.cpf, u.email, u.telefone_celular
				   FROM bejobs_notificacoes_usuarios AS nu
				   INNER JOIN bejobs_usuarios AS u
				   WHERE nu.id_mensagens_usuarios = $id_mensagem
				   AND nu.id_usuario = u.id"; // print_r($sql5);
		$query5 = $dba->query($sql5);
		$qntd5  = $dba->rows($query5);
		if ($qntd5 > 0) {
			for ($i=0; $i<$qntd5; $i++) {
				$vet5 = $dba->fetch($query5);
				$tpl->newBlock('usuarios_notificacoes');
				$tpl->assign('nome', stripslashes($vet5[1]));
				$tpl->assign('cpf', $vet5[2]);
				$tpl->assign('email', stripslashes($vet5[3]));
				$tpl->assign('telefone_celular', stripslashes($vet5[4]));

				if ($vet5[0] == 2) {
					$tpl->assign('status', 'Lida');
				} elseif ($vet5[0] == 1) {
					$tpl->assign('status', 'Não Lida');
				}
								
			}
		}

	} else {
		header("Location: ./mensagens-usuarios");
	}

} else {
	header("Location: ./mensagens-usuarios");
}
 
$tpl->printToScreen();
?>