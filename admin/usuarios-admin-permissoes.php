<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/usuarios-admin-permissoes.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 16;
$act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$id_usuario   = $_GET['id'];

	$sql = "SELECT * FROM bejobs_usuario_admin WHERE id = $id_usuario"; //or die(); // print_r($sql);
	$query = $dba->query($sql);
	$qntd = $dba->rows($query);
	if ($qntd > 0) {
		$vet = $dba->fetch($query);
		$tpl->assign('id', $vet['id']);
		$tpl->assign('nome', stripslashes($vet['name']));
		$tpl->assign('user', stripslashes($vet['user']));

		$sql2 = "SELECT id, nome FROM bejobs_paginas ORDER BY nome ASC";
		$query2 = $dba->query($sql2);
		$qntd2 = $dba->rows($query2);
		if ($qntd2 > 0) {
			for ($i=0; $i<$qntd2; $i++) {
				$vet2 = $dba->fetch($query2);
				$tpl->newBlock('paginas');
				$id_pagina = $vet2[0];
				$tpl->assign('id', $vet2[0]);
				$tpl->assign('nome', $vet2[1]);

				$sql3 = "SELECT visualizar, cadastrar, editar, excluir
						 FROM bejobs_paginas_acesso
						 WHERE id_pagina = $id_pagina AND id_usuario = $id_usuario";
				$query3 = $dba->query($sql3);
				$vet3 = $dba->fetch($query3);

				if ($vet3[0] == 1) {
					$tpl->assign('visualizar', 'checked');
				}
				
				if ($vet3[1] == 1) {
					$tpl->assign('cadastrar', 'checked');
				}

				if ($vet3[2] == 1) {
					$tpl->assign('editar', 'checked');
				}

				if ($vet3[3] == 1) {
					$tpl->assign('excluir', 'checked');
				}
			}
		} 

	} else {
		header('Location: ./usuarios');
	}

	
}


//--------------------------
$tpl->printToScreen();
?>