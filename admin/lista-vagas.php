<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/lista-vagas.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 17;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');


include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$sql   = "SELECT * FROM bejobs_vagas WHERE status_pagamento=1";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('chamados');
		$tpl->assign('id',$vet['id']);
		$tpl->assign('titulo', utf8_encode($vet["titulo"]));
		$tpl->assign('descricao', utf8_encode($vet["descricao"]));
		$tpl->assign('local',utf8_encode($vet["local"])); 
		$tpl->assign('instituicao', utf8_encode($vet["instituicao"])); 
		$tpl->assign('profissao', utf8_encode($vet["profissao"])); 
		$tpl->assign('nome_empresa', utf8_encode($vet["nome_empresa"])); 
		$tpl->assign('pagamento', utf8_encode($vet["pagamento"])); 
		$tpl->assign('contratado', utf8_encode($vet["contratado"]));
		$status= $vet["status"];  
		if ($status == 0) {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarVaga('.$vet['id'].',\'ativar_vaga\', \''.$vet["titulo"].'\')" data-toggle="tooltip" title="Status: Vaga inativa" class="tooltips"><i class="fa fa-long-arrow-down"></i></a>');
		} else {
			$tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarVaga('.$vet['id'].',\'desativar_vaga\', \''.$vet["titulo"].'\')" data-toggle="tooltip" title="Status: Vaga ativa" class="tooltips"><i class="fa fa-check"></i></a>');
		}
         
	}
} 


//--------------------------
$tpl->printToScreen();
?>