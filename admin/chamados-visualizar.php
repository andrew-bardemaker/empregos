<?php
include('./inc/inc.verificasession.php');
include('./inc/class.TemplatePower.php');
include('./inc/inc.configdb.php');
include('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html'); 
$tpl->assignInclude('content', './tpl/chamados-visualizar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 1;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include('./menu.php'); //monta o menu de acordo com o usuário
include('./inc/inc.mensagens.php'); //mensagens e alertas

$tpl->gotoBlock('_ROOT');

if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$id_chamado   = $_GET['id'];

	$sql2 = "SELECT chamados.id, chamados.assunto, chamados.status, chamados.data_registro, chamados.anexo, chamados_categorias.titulo, chamados.mensagem, chamados.telefone, chamados.ip_registro, chamados.id_usuario
			 FROM bejobs_chamados AS chamados 
			 INNER JOIN bejobs_chamados_categorias AS chamados_categorias
			 WHERE chamados.id_categoria = chamados_categorias.id
			 AND chamados.id = $id_chamado";
	$query2 = $dba->query($sql2);
	$qntd2 = $dba->rows($query2);
	if ($qntd2 > 0) {
		$vet2 = $dba->fetch($query2);
		$id_chamado = $vet2[0];
		$tpl->assign('id_chamado', $id_chamado);
		$tpl->assign('assunto', stripslashes($vet2[1]));
		$tpl->assign('mensagem', stripslashes(nl2br($vet2[6])));
		$tpl->assign('telefone', stripslashes($vet2[7]));
		$tpl->assign('ip_registro', stripslashes($vet2[8]));

		$status = $vet2[2];
		if ($status == 1) {
			$tpl->assign('status', 'Aberto');
		} elseif ($status == 2) {
			$tpl->assign('status', 'Atualizado');
		} elseif ($status == 3) {
			$tpl->assign('status', 'Aguardando seu retorno');
		} elseif ($status == 4) {
			$tpl->assign('status', 'Resolvido');
		} elseif ($status == 5) {
			$tpl->assign('status', 'Fechado');
		}

		$tpl->assign('categoria', $vet2[5]);
		$tpl->assign('data_registro', datetime_date_ptbr($vet2[3]));
		$tpl->assign('hora_registro', datetime_time_full_ptbr($vet2[3]));

		$id_usuario = $vet2[9];
		$sql4   = "SELECT nome, cpf FROM bejobs_usuarios WHERE id = $id_usuario";
		$query4 = $dba->query($sql4);
		$qntd4  = $dba->rows($query4);
		if ($qntd4 > 0) {
			$vet4 = $dba->fetch($query4);
			$tpl->assign('nome_usuario', stripslashes($vet4[0]));
			$tpl->assign('cpfcnpj', $vet4[1]);
		}
		
		if (!empty($vet2[4]) && $vet2[4] != "") {
			$anexo = $vet2[4];
			if (file_exists("../".$anexo)) {
				$tpl->newBlock('anexo_chamado');
				$tpl->assign('anexo', "../".$anexo);
				$nome_anexo = explode('/', $anexo); // Pega última parte da string = nome do arquivo
				$tpl->assign('nome_anexo', end($nome_anexo));
			}
		} else {
			$tpl->newBlock('noanexo_chamado');
		}

		if ($status == 5) {
			$tpl->newBlock('avaliacao_chamado');

			$sql5   = "SELECT nota FROM bejobs_chamados_avaliacao WHERE id_chamado = $id_chamado"; 
			$query5 = $dba->query($sql5);
			$vet5   = $dba->fetch($query5);
			$nota   = $vet5[0];

			if ($nota == 1) {
				$tpl->assign('nota', 'Péssimo');
			} elseif ($nota == 2) {
				$tpl->assign('nota', 'Ruim');
			} elseif ($nota == 3) {
				$tpl->assign('nota', 'Regular');
			} elseif ($nota == 4) {
				$tpl->assign('nota', 'Bom');
			} elseif ($nota == 5) {
				$tpl->assign('nota', 'Ótimo');
			} elseif ($nota == 6) {
				$tpl->assign('nota', 'Não desejo responder');
			}
		} else {
			$tpl->newBlock('chamado_interacao2');
			$tpl->assign('id_chamado', $id_chamado);
		}
		
		$sql3   = "SELECT id, mensagem, data_registro, tipo_usuario, id_usuario, anexo FROM bejobs_chamados_interacao WHERE id_chamado = $id_chamado ORDER BY data_registro";
		$query3 = $dba->query($sql3);
		$qntd3  = $dba->rows($query3);
		if ($qntd3 > 0) {
			for ($k=0; $k<$qntd3; $k++) {
				$vet3 = $dba->fetch($query3);
				$tpl->newBlock('chamado_interacao');
				$tpl->assign('id', $vet3[0]);
				$tpl->assign('mensagem', stripslashes(nl2br($vet3[1])));
				$tpl->assign('data_registro', datetime_date_ptbr($vet3[2]));
				$tpl->assign('hora_registro', datetime_time_full_ptbr($vet3[2]));

				$id_usuario = $vet3[4];
				$tipo_usuario = $vet3[3];
				if ($tipo_usuario == 1) {
					$sql4   = "SELECT nome FROM bejobs_usuarios WHERE id = $id_usuario";
					$query4 = $dba->query($sql4);
					$qntd4  = $dba->rows($query4);
					if ($qntd4 > 0) {
						$vet4 = $dba->fetch($query4);
						$tpl->assign('nome_usuario', stripslashes($vet4[0]));
					}
				}

				if ($tipo_usuario == 4) {
					$sql4   = "SELECT name FROM bejobs_usuario_admin WHERE id = $id_usuario";
					$query4 = $dba->query($sql4);
					$qntd4  = $dba->rows($query4);
					if ($qntd4 > 0) {
						$vet4 = $dba->fetch($query4);
						$tpl->assign('nome_usuario', stripslashes($vet4[0]));
					}
				}

				if (!empty($vet3[5]) && $vet3[5] != "") {
					$anexo = $vet3[5];
					if (file_exists("../".$anexo)) {
						$tpl->newBlock('anexo_chamado_interacao');
						$tpl->assign('anexo', "../".$anexo);
						$nome_anexo = explode('/', $anexo); // Pega última parte da string = nome do arquivo
						$tpl->assign('nome_anexo', end($nome_anexo));
					}
				} else {
					$tpl->newBlock('noanexo_chamado_interacao');
				}
			}
		} else {
			$tpl->newBlock('nochamado_interacao');
		}

	} else {
		header("Location: chamados");
	}

} else {
	header("Location: chamados");
}
 
$tpl->printToScreen();
?>