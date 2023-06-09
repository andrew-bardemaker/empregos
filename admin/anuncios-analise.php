<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/anuncio-visualizar.html');
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

if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$id_anuncio   = $_GET['id'];

    $sql = "SELECT * FROM bejobs_anuncios WHERE id=$id_anuncio";
    $query = $dba->query($sql);
    $qntd = $dba->rows($query);
    if ($qntd > 0) {
        $vet= $dba->fetch($query); 
        $tpl->assign('id',$id_anuncio);
        $tpl->assign('numero_pedido', $id_anuncio);
        $tpl->assign('periodo_vigencia', stripslashes($vet[4]));
        $tpl->assign('perfil', stripslashes($vet[3]));
        $tpl->assign('categoria', stripslashes($vet['categoria']));
        $tpl->assign('area', stripslashes($vet['area']));
        if (file_exists("../images/anuncio/".$id.".jpg")) { 
            echo "<script>console.log('../images/anuncio/".$id.".jpg')</script>";
            $tpl->newBlock('img');
            $tpl->assign('imagem', "../images/anuncio/".$id.".jpg");
        } else { 
            $tpl->newBlock('noimg');
        }
    }
    
}

//--------------------------
$tpl->printToScreen();
