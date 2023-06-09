<?php
/**
 * @author			Andrew Bardemaker - v 1.0 - mar/2019
 * @description 	Serviço de retorno das categorias de chamados
 * @params

 */

header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Credentials: true');
header('Content-type: application/json');
header('X-Content-Type-Options: nosniff');

include('../admin/inc/inc.configdb.php');
include('../admin/inc/inc.lib.php');

$array                     = array();
$array_chamados_categorias = array();

$sql = "SELECT id, titulo FROM bejobs_chamados_categorias ORDER BY titulo";
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$id_categoria = $vet[0];
		$titulo = stripslashes($vet[1]);

		$array_chamados_categorias[] = array('id' => $id_categoria, 'titulo' => $titulo);
	}
}

$array = array("success" => "true", "chamados_categorias" => $array_chamados_categorias);

header('Content-type: application/json');
echo json_encode($array);

?>