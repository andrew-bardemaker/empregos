<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/anuncio.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 1;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$sql = "SELECT * FROM bejobs_anuncios";
$query = $dba->query($sql);
$qntd = $dba->rows($query);

if ($qntd > 0) {
    for ($i = 0; $i < $qntd; $i++) {
        $vet = $dba->fetch($query);
        $tpl->newBlock('anuncio');
        $tpl->assign('id', stripslashes($vet[0]));
        $tpl->assign('id_pedido', stripslashes($vet[1]));
        $tpl->assign('vigencia', stripslashes($vet[4]));
        $tpl->assign('perfil', stripslashes($vet[3]));
        $tpl->assign('categoria', stripslashes($vet['categoria']));
        $tpl->assign('area', stripslashes($vet['area']));


        if ($vet['status'] == 0) { // Verifica status
            $tpl->assign('status', '<a href="javascript:void(0)" onclick="ativarAnuncio(' . $vet['0'] . ',\'ativar_anuncio\', \'' . $vet['0'] . '\')" data-toggle="tooltip" title="Anúncio Inativa" class="tooltips"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></a>');
            $tpl->assign('status2', 'Inativa');
        } else {
            $tpl->assign('status', '<a href="javascript:void(0)" onclick="desativarAnuncio(' . $vet['0'] . ',\'desativar_anuncio\', \'' . $vet['0'] . '\')" data-toggle="tooltip" title="Anúncio Ativa" class="tooltips"><i class="fa fa-check" aria-hidden="true"></i></a>');
            $tpl->assign('status2', 'Ativa');
        }
    }
}

//--------------------------
$tpl->printToScreen();
