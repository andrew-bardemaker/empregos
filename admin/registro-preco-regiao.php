<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/registro-preco-regiao.html');
$tpl->prepare(); 
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$UF=$_GET["uf"];

$sql="SELECT * FROM bejobs_regiao_preco";
$query = $dba->query($sql);
$rows = $dba->rows($query);
if($rows > 0){
    for($i=0;$i<$rows;$i++){
		$vet = $dba->fetch($query);
		$tpl->newBlock('lista');
		$tpl->assign('id', $vet[0]);
		$tpl->assign('estado', $vet[1]);
		$tpl->assign('cidade', $vet[2]);
		$tpl->assign('preco', $vet[3]);
    }
}

$tpl->newBlock('cadastro_lista');
$ufs = array("AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", 
             "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", 
             "SP", "SE", "TO");
for($j=0;$j<count($ufs);$j++){
    $options_estados.="<option value='$ufs[$j]'> $ufs[$j] </option> " ;
} 
$tpl->assign("estados",$options_estados);


//Onchange do superior
//https://servicodados.ibge.gov.br/api/v1/localidades/estados/$UF/distritos

//--------------------------
$tpl->printToScreen();
?>