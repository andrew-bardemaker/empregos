<?php

/**
 * @author			Andrew Bardemaker - v 1.0 - abr/2019
 * @description 	Serviço de retorno das perguntas/respostas FAQ
 * @params

 */
// session_start();
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
include('./verificatoken.php');

$array           = array();
$array_perguntas = array();

$json  = file_get_contents('php://input');

$obj   = json_decode($json); // var_dump($obj);

$pagina       = 1;
$pesquisa     = "";
$pesquisa_sql = 1;

if (!empty($obj->pagina) && is_numeric($obj->pagina)) {
	$pagina = $obj->pagina;
}

if (!empty($obj->pesquisa)) {
	$pesquisa     = $obj->pesquisa;
	$pesquisa_sql = "titulo LIKE '%" . $pesquisa . "%' || texto LIKE '%" . $pesquisa . "%'";
}

// Seta a quantidade de itens por página, neste caso, 10 itens
$registros = 9999999;
// Variavel para calcular o início da visualização com base na página atual
$inicio    = ($registros * $pagina) - $registros;

$sql3   = "SELECT id, titulo, texto FROM bejobs_faq WHERE $pesquisa_sql ORDER BY titulo";
$query3 = $dba->query($sql3);
$total  = $dba->rows($query3);

$sql2   = "SELECT id, titulo, texto FROM bejobs_faq WHERE $pesquisa_sql ORDER BY titulo LIMIT $inicio, $registros";

$query2 = $dba->query($sql2);
$qntd2  = $dba->rows($query2);
if ($qntd2 > 0) {
	for ($j = 0; $j < $qntd2; $j++) {
		$vet2   = $dba->fetch($query2);
		$id_faq = $vet2[0];
		$titulo = $vet2[1];
		$texto = $vet2[2];

		$array_perguntas[] = array('titulo' => $titulo, 'texto' => $texto, 'id' => $id_faq);
	}
}

$array = array("success" => "true", "perguntas" => $array_perguntas, "total" => $total, "pagina" => $pagina, "pesquisa" => $pesquisa);


header('Content-type: application/json');
echo json_encode($array);
