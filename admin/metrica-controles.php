<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/metrica-controles.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 4;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts
  
$sql_query= "SELECT * FROM bejobs_metricas"; 
$query = $dba->query($sql_query);
$rows  = $dba->rows($query); 
for($i=0;$i<$rows;$i++){ 
    $vet = $dba->fetch($query); 
	$tpl->newBlock('metricas'); 
    if(stripslashes($vet[0]) == '1'){
		$nome="Next Day";
        $nome_form='NextDay';
	}else{
		$nome="Today";
        $nome_form='Today';
	}
    $tpl->assign('tipo_formatada',$nome_form);
	$tpl->assign('tipo', stripslashes($nome));
	$tpl->assign('valor', stripslashes($vet[2]));  
    $tpl->assign('id',$vet[0]);  

}
$tpl->printToScreen();
?>