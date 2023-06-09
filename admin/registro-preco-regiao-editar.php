<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/registro-preco-regiao-editar.html');
$tpl->prepare();
// $act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET["id"];
$sql = "SELECT * FROM bejobs_regiao_preco WHERE id=$id";
$query = $dba->query($sql);
$rows = $dba->rows($query);
if ($rows > 0) {
    $vet = $dba->fetch($query);
    $id = $vet["id"];
    $estado = $vet["estado"];
    $cidade = $vet["cidade"];
    $preco =  $vet["preco"];
}

//LISTAGEM DE ESTADOS
$tpl->newBlock('cadastro_lista');
$tpl->assign("id",$id);
$ufs = array(
    "AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS",
    "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC",
    "SP", "SE", "TO"
);
for ($j = 0; $j < count($ufs); $j++) {
    if ($ufs[$j] == $estado) {
        $options_estados .= "<option value='$ufs[$j]' selected >$ufs[$j] </option>";
    } else {
        $options_estados .= "<option value='$ufs[$j]'> $ufs[$j] </option> ";
    }
}
$tpl->assign("estados", $options_estados);

//LISTAGEM DE CIDADES
$json = file_get_contents('https://servicodados.ibge.gov.br/api/v1/localidades/estados/RS/municipios');
$data = json_decode($json, true);

foreach($data as $municipio){
    if($municipio["nome"] == $cidade){
        $options_municipio.="<option value='".$municipio['nome']."' selected>".$municipio['nome'] ."</option>";
    }else{
        $options_municipio.="<option value=".$municipio['nome']."'> ".$municipio['nome']."</option>";
    }
}
$tpl->assign("cidades",$options_municipio);
$tpl->assign("preco", $preco); 

//Onchange do superior
//https://servicodados.ibge.gov.br/api/v1/localidades/estados/$UF/distritos

//--------------------------
$tpl->printToScreen();
