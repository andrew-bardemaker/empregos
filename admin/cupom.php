<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/cupom.html');
$tpl->prepare();


// Verificação de acesso página e permissões
$id_pagina_permissao = 1;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$status     = ""; // Variável p/ salvar id do status
$sql_status = "";
if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = $_GET['status'];
    $sql_status = " AND status = " . $status;
}

// $tipo_usuario     = ""; // Variável p/ salvar id do tipo_usuario
// $sql_tipo_usuario = "";
// if (isset($_GET['tipo_usuario']) && !empty($_GET['tipo_usuario'])) {
// 	$tipo_usuario = $_GET['tipo_usuario'];
// 	$sql_tipo_usuario = " AND tipo_usuario = ".$tipo_usuario;
// }

$sql = "SELECT * FROM bejobs_cuppons  ORDER BY data_registro DESC"; // print_r($sql);
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
    for ($i = 0; $i < $qntd; $i++) {
        $vet = $dba->fetch($query);
        $tpl->newBlock('cupons');
        $tpl->assign('data_registro', datetime_date_ptbr($vet['data_registro']));
        $tpl->assign('cupom_nome', $vet['cupom']);
        $tpl->assign('cupom_desconto', $vet['desconto']);
        $tpl->assign('data_ini', datetime_date_ptbr($vet['data_ini']).' '.datetime_time_full_ptbr($vet['data_ini']));
		$tpl->assign('data_fim', datetime_date_ptbr($vet['data_fim']).' '.datetime_time_full_ptbr($vet['data_fim']));
        $tpl->assign('id', $vet['id']);
        

        $status2 = $vet['status'];
        if ($status2 == 1) {
            $tpl->assign('status', 'ativo');
        } elseif ($status2 == 0) {
            $tpl->assign('status', 'inativo');
        }

        if ($status2 == 0) { // Verifica status
			$tpl->assign('ativa', '<a href="javascript:void(0)" onclick="ativarCuppom('.$vet['id'].',\'ativar_cuppom\', \''.$vet['cupom'].'\')" data-toggle="tooltip" title="cupom ativo" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>');
		} else {
			$tpl->assign('ativa', '<a href="javascript:void(0)" onclick="desativarCuppom('.$vet['id'].',\'desativar_cuppom\', \''.$vet['cupom'].'\')" data-toggle="tooltip" title="cupom inativo" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>');
		}
     
    }
}

// Filtros de Busca
$tpl->gotoBlock('_ROOT');

//$options_status  = array('','1','2','3','4','5');
//$options_status2 = array('Todos','Aberto','Atualizado','Aguardando seu retorno','Resolvido','Fechado');
//$output_status = "";
//for ($p=0; $p < count($options_status); $p++) {
// $output_status .= '<option value="'.$options_status[$p].'" ' 
//	             . ( $status == $options_status[$p] ? 'selected="selected"' : '' ) . '>' 
//	             . $options_status2[$p] 
//	             . '</option>';
//}	
//$tpl->assign('status',  $output_status);

// $options_tipo_usuario  = array('','1','2','3');
// $options_tipo_usuario2 = array('Todos','Colaboradores','Pessoa Física','Pessoa Jurídica');
// $output_tipo_usuario = "";
// for ($p=0; $p < count($options_tipo_usuario); $p++) {
//   $output_tipo_usuario .= '<option value="'.$options_tipo_usuario[$p].'" ' 
// 	             . ( $tipo_usuario == $options_tipo_usuario[$p] ? 'selected="selected"' : '' ) . '>' 
// 	             . $options_tipo_usuario2[$p] 
// 	             . '</option>';
// }	
// $tpl->assign('tipo_usuario',  $output_tipo_usuario);

//--------------------------
$tpl->printToScreen();
