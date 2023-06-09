<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/grupos-usuarios.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 3;
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];

$sql2 = "SELECT 
		 grupos.titulo,
		 grupos.observacoes,
		 grupos.data_registro,
		 usuario_admin.name
		 FROM bejobs_grupos AS grupos
		 INNER JOIN bejobs_usuario_admin AS usuario_admin
		 WHERE grupos.usuario_registro = usuario_admin.id
		 AND grupos.id = $id";
// print_r($sql2);
$query2 = $dba->query($sql2);
$vet2 = $dba->fetch($query2);

$tpl->assign('id_grupo', $id);
$tpl->assign('titulo_grupo', addslashes($vet2[0]));
$tpl->assign('observacoes_grupo', addslashes($vet2[1]));
$tpl->assign('data_registro', datetime_date_ptbr($vet2[2]));	
$tpl->assign('hora_registro', datetime_time_ptbr($vet2[2]));
$tpl->assign('usuario_registro', addslashes($vet2[3]));
$tpl->assign('id_grupo', $id);

$sql = "SELECT * FROM bejobs_grupos_usuarios WHERE id_grupos = $id";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
$tpl->assign('qntd_resultados', $qntd);

$tpl->newBlock('cadastro_lista');
$ufs = array("AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", 
             "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", 
             "SP", "SE", "TO");
for($j=0;$j<count($ufs);$j++){
    $options_estados.="<option value='$ufs[$j]'> $ufs[$j] </option> " ;
} 
$tpl->assign("estados",$options_estados);

if ($qntd > 0) {
    for ($i=0; $i<$qntd; $i++) {
        $vet = $dba->fetch($query);
        $tpl->newBlock('usuarios');
        $tpl->assign('id', $vet['id']);

        $id_usuario = $vet['id_usuarios'];

        $sql5 = "SELECT nome, cpf, email, telefone_celular FROM bejobs_usuarios WHERE id = $id_usuario";
        $query5 = $dba->query($sql5);
        $vet5 = $dba->fetch($query5);
        $tpl->assign('nome', $vet5[0]);
        $tpl->assign('cpf_cnpj', $vet5[1]);
        $tpl->assign('email', $vet5[2]);
        $tpl->assign('telefone_celular', $vet5[3]);
    }
}

//--------------------------
$tpl->printToScreen();
?>