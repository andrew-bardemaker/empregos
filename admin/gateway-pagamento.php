<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/gateway-pagamento.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 4;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts
 
 
 
$tpl->newBlock('entregadores');
$tpl->assign('id', 1);
$tpl->assign('nome',"nome");
$tpl->assign('acoes',"acoes");
$tpl->printToScreen();
?>