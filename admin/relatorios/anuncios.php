<?php
require_once('../inc/inc.verificasession.php');
require_once('../inc/class.TemplatePower.php');
require_once('../inc/inc.configdb.php');
require_once('../inc/inc.lib.php');

$tpl = new TemplatePower('../tpl/default.html');
$tpl->assignInclude('content', './tpl/anuncios.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 13;
// $act_permissao_editar = true;
require_once('../inc/inc.verificaacessopermissao.php');

include_once('../menu.php'); //monta o menu de acordo com o usuário
include_once('../inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

// Select com períodos de filtro
$tpl->assign('custom', date('d/m/Y'));
$tpl->assign('today', date('d/m/Y'));
$tpl->assign('yesterday', date('d/m/Y', strtotime('- 1 days')));
$tpl->assign('last_week', date('d/m/Y', strtotime('- 7 days')));
$tpl->assign('last_30_days', date('d/m/Y', strtotime('- 30 days')));
$tpl->assign('last_6_months', date('d/m/Y', strtotime('- 6 months')));
$tpl->assign('last_12_months', date('d/m/Y', strtotime('- 12 months')));

$periodo = "";
if (isset($_GET['periodo']) && !empty($_GET['periodo'])) {
    $periodo = $_GET['periodo'];
}

switch ($periodo) {
    case 'custom':
        $tpl->assign('custom_selected', 'selected');
        break;
    case 'today':
        $tpl->assign('today_selected', 'selected');
        break;
    case 'yesterday':
        $tpl->assign('yesterday_selected', 'selected');
        break;
    case 'last_week':
        $tpl->assign('last_week_selected', 'selected');
        break;
    case 'last_30_days':
        $tpl->assign('last_30_days_selected', 'selected');
        break;
    case 'last_6_months':
        $tpl->assign('last_6_months_selected', 'selected');
        break;
    case 'last_12_months':
        $tpl->assign('last_12_months_selected', 'selected');
        break;
    default:
        // $tpl->assign('today_selected', 'selected');
        break;
}
// Data dos filtros
$periodo_sql = "";
if (isset($_GET['data_ini']) && !empty($_GET['data_ini']) && isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
    $tpl->assign('data_ini', $_GET['data_ini']);
    $tpl->assign('data_fim', $_GET['data_fim']);

    $data_ini = dataMY($_GET['data_ini']) . " 00:00:00";
    $data_fim = dataMY($_GET['data_fim']) . " 23:59:59";

    $periodo_sql = "AND vigencia BETWEEN '$data_ini' AND '$data_fim'"; // Monta sql com datas filtradas no período
}

if (!empty($periodo_sql)) {
    $total_anuncios = 0;

    $sql = "SELECT 
                   bejobs_anuncios.id, 
                   bejobs_usuarios.nome, 
                   bejobs_anuncios.vigencia,
                   bejobs_anuncios.titulo,
                   bejobs_anuncios.status,
                   bejobs_anuncios.area        
            FROM bejobs_anuncios 
            INNER JOIN bejobs_usuarios ON bejobs_usuarios.id = bejobs_anuncios.id_usuario
            WHERE 1=1 $periodo_sql";
    $query = $dba->query($sql);
    $rows = $dba->rows($query);
  

    if ($rows > 0) {
        for ($i = 0; $i < $rows; $i++) {
            $vet = $dba->fetch($query);
            $tpl->newBlock("anuncios");
            $tpl->assign("id", $vet["id"]);
            $tpl->assign("nome", $vet["nome"]);
            $tpl->assign("titulo", $vet["titulo"]);
            $tpl->assign("area", $vet["area"]);
            if ($vet["status"] == 0) {
                $tpl->assign("status", "Inativo");
            } else {
                $tpl->assign("status", "Ativo");
            }
            $tpl->assign("vigencia", $vet["vigencia"]);
        }

        $tpl->newBlock('total');
        $tpl->assign('total_anuncios', $rows);
    } else {
        $tpl->newBlock("no-anuncios");
    }
} else {
    $tpl->newBlock("no-anuncios");
}
$tpl->printToScreen();
