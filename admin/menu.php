<?php
setlocale (LC_ALL, 'pt_BR');
if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$tpl->assignGlobal('base', 'http://localhost/bejobsapp/admin/');
} else {
	$tpl->assignGlobal('base', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/bejobs/admin/');
}

if ($_SESSION['app_user_type'] == 1 || $_SESSION['app_user_type'] == 2) { // Administradores

	$tpl->assign('user_name_menu',  $_SESSION['app_user_nom']); // Defini nome de usuário	
	$app_user_id = $_SESSION['app_user_id']; // Id do usuário
	$canal_relacionamento = 0; // Default = 0

	$tpl->newBlock('menu_admin'); // Monta menu

	// Monta menu de acordo com módulos setados pelo admin
	$sql_menu   = "SELECT p.nome, p.code, p.id
				   FROM bejobs_paginas AS p 
				   INNER JOIN bejobs_paginas_acesso AS pa 
				   WHERE p.id = pa.id_pagina
				   AND pa.id_usuario = $app_user_id 
				   AND pa.visualizar = 1
				   ORDER BY p.nome";
	$query_menu = $dba->query($sql_menu);
	$qntd_menu  = $dba->rows($query_menu);		
	if ($qntd_menu > 0) {
		for ($w=0; $w<$qntd_menu; $w++) {
			$vet_menu = $dba->fetch($query_menu);
			$tpl->newBlock('item_menu_admin'); // Monta menu
			$modulo = $vet_menu[0];
			$code   = utf8_encode($vet_menu[1]);
			$id_m   = $vet_menu[2];
			$tpl->assign('item_menu', $code);

			if ($id_m == 1) {
				$canal_relacionamento = 1;
			}
		}
	}


	if ($canal_relacionamento == 1) {
		$tpl->newBlock('canal_relacionamento');
		// Notificações
		$sql_ntf   = "SELECT id, data_registro, titulo, texto FROM bejobs_notificacoes_admin WHERE status = 1 ORDER BY data_registro DESC"; // Monta bloco de notificações
		$query_ntf = $dba->query($sql_ntf);
		$qntd_ntf  = $dba->rows($query_ntf);		
		if ($qntd_ntf > 0) {
			for ($w=0; $w<$qntd_ntf; $w++) {
				$vet_ntf = $dba->fetch($query_ntf);
				$tpl->newBlock('menu_notificacoes');
				$tpl->assign('id',  $vet_ntf[0]);
				$tpl->assign('data_registro',  $vet_ntf[1]);
				$tpl->assign('titulo',  $vet_ntf[2]);
				$tpl->assign('texto',  limitaCaracteres(strip_tags($vet_ntf[3]), 110));

				if ($w >= 5) { break; }
			}

		} else {
			$tpl->newBlock('nomenu_notificacoes');
		}

		$tpl->gotoBlock('_ROOT');
		$tpl->assignGlobal('notificacoes_total',  $qntd_ntf); // Defini total de notificações não lidas

		// Mensagens
		$sql_mnsg   = "SELECT id, data_registro, assunto, nome, email, telefone FROM bejobs_mensagens_faleconosco WHERE status = 1 ORDER BY data_registro DESC"; // Monta bloco de mensagens
		$query_mnsg = $dba->query($sql_mnsg);
		$qntd_mnsg  = $dba->rows($query_mnsg);		
		if ($qntd_mnsg > 0) {
			for ($w=0; $w<$qntd_mnsg; $w++) {
				$vet_mnsg = $dba->fetch($query_mnsg);
				$tpl->newBlock('menu_mensagens');
				$tpl->assign('id',  $vet_mnsg[0]);
				$tpl->assign('data_registro',  $vet_mnsg[1]);
				$tpl->assign('assunto',  $vet_mnsg[2]);
				$tpl->assign('nome',  addslashes($vet_mnsg[3]));
				$tpl->assign('email',  addslashes($vet_mnsg[4]));
				$tpl->assign('telefone',  addslashes($vet_mnsg[5]));

				if ($w >= 5) { break; }
			}

		} else {
			$tpl->newBlock('nomenu_mensagens');
		}

		$tpl->gotoBlock('_ROOT');
		$tpl->assignGlobal('mensagens_total',  $qntd_mnsg); // Defini total de mensagens não lidas
	}

} elseif ($_SESSION['app_user_type'] == 3) { // Gerentes
	$tpl->assign('user_name_menu',  $_SESSION['app_user_nom']); // Defini nome de usuário	
	$app_user_id = $_SESSION['app_user_id']; // Id do usuário
	
	$tpl->newBlock('menu_gerente'); // Monta menu
}

?>