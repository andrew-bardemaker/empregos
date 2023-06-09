<?php

if ($_SESSION['app_user_type'] != 1) {

	$sql_permissao_editar = "";
	if (isset($act_permissao_editar) AND $act_permissao_editar == true) {
		$sql_permissao_editar = " AND editar = 1";
	}	

	$sql_permissao = "SELECT * 
					  FROM bejobs_paginas_acesso 
					  WHERE id_usuario = ".$_SESSION['app_user_id']." 
					  AND id_pagina = ".$id_pagina_permissao." 
					  AND visualizar = 1".$sql_permissao_editar; 
	// print_r($sql_permissao);
	$query_permissao = $dba->query($sql_permissao);
	$qntd_permissao  = $dba->rows($query_permissao);
	if ($qntd_permissao == 0) {
		header('Location: ./painel');		
	} else {
		$vet_permissao = $dba->fetch($query_permissao);
		if ($vet_permissao['cadastrar'] != 1) {
			$tpl->assignGlobal('layout-cadastrar', 'hidden');
		}	

		if ($vet_permissao['editar'] != 1) {
			$tpl->assignGlobal('layout-editar', 'hidden');
		}	

		if ($vet_permissao['excluir'] != 1) {
			$tpl->assignGlobal('layout-excluir', 'hidden');
		}
	}
}

?>