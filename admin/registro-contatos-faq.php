<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/registro-contatos-faq.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 17;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');


include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$sql   = "SELECT id,hora_contato, nome, email, telefone,  mensagem FROM bejobs_registro_contatos";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$tpl->newBlock('chamados');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('data_cadastro', $vet[1]);
		$tpl->assign('nome', $vet[2]); 
		$tpl->assign('email', $vet[3]); 
		$tpl->assign('telefone_celular', $vet[4]); 
		$tpl->assign('mensagem', utf8_encode($vet[5])); 
        
	}
} 


//--------------------------
$tpl->printToScreen();
?>