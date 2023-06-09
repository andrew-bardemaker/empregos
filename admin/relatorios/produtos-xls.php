<?php
// header('Content-Type: text/html; charset=utf-8');
include('../inc/inc.verificasession.php');
// include('../inc/fpdf/fpdf.php');
include('../inc/inc.configdb.php');
include('../inc/inc.lib.php');


if ((empty($_GET['data_ini']) || !isset($_GET['data_ini'])) && (empty($_GET['data_fim']) || !isset($_GET['data_fim'])) ) {
	header('Location: ./relatorios/produtos?msg=rlt001');
	exit;
}


// Data dos filtros
$periodo_sql = "";

if (isset($_GET['data_ini']) && !empty($_GET['data_ini']) && isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
	// $tpl->assign('data_ini', $_GET['data_ini']);
	// $tpl->assign('data_fim', $_GET['data_fim']);

	$data_ini = dataMY($_GET['data_ini'])." 00:00:00";
	$data_fim = dataMY($_GET['data_fim'])." 23:59:59";

	$periodo_sql = "AND a.data_hora_registro BETWEEN '$data_ini' AND '$data_fim'"; // Monta sql com datas filtradas no período
} 

$entregador_sql = "";
$entregador 	= "";
if (isset($_GET['entregador']) && !empty($_GET['entregador'])) {
	$entregador     = $_GET['entregador'];
	$entregador_sql = "AND  a.id_entregadores = '".$_GET['entregador']."'";

	$sql18   = "SELECT id, nome FROM bejobs_entregadores WHERE id = $entregador";
	$query18 = $dba->query($sql18);
	$qntd18  = $dba->rows($query18);
	if ($qntd18 > 0) {
		$vet18 = $dba->fetch($query18);
		$entregador = $vet18[1]; 
	}
} 

$produto_sql = "";
$produto 	= "";
if (isset($_GET['produto']) && !empty($_GET['produto'])) {
	$produto     = $_GET['produto'];
	$produto_sql = "AND c.id = '".$_GET['produto']."'";

	$sql18   = "SELECT id, titulo FROM bejobs_produtos WHERE id = $produto";
	$query18 = $dba->query($sql18);
	$qntd18  = $dba->rows($query18);
	if ($qntd18 > 0) {
		$vet18 = $dba->fetch($query18);
		$produto = $vet18[1]; 
	}
}

$categoria_sql = "";
$categoria 	= "";
if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
	$categoria     = $_GET['categoria'];
	$categoria_sql = "AND d.id = '".$_GET['categoria']."'";

	$sql18   = "SELECT id, titulo FROM bejobs_produtos_categorias WHERE id = $categoria";
	$query18 = $dba->query($sql18);
	$qntd18  = $dba->rows($query18);
	if ($qntd18 > 0) {
		$vet18 = $dba->fetch($query18);
		$categoria = $vet18[1]; 
	}
}

$marca_sql = "";
$marca 	= "";
if (isset($_GET['marca']) && !empty($_GET['marca'])) {
	$marca     = $_GET['marca'];
	$marca_sql = "AND e.id = '".$_GET['marca']."'";

	$sql18   = "SELECT id, titulo FROM bejobs_produtos_marcas WHERE id = $marca";
	$query18 = $dba->query($sql18);
	$qntd18  = $dba->rows($query18);
	if ($qntd18 > 0) {
		$vet18 = $dba->fetch($query18);
		$marca = $vet18[1]; 
	}
} 

$header  = "Código"."\t"."Título"."\t"."Marca"."\t"."Categoria"."\t"."Qtde Total"."\t".'Total Pedido R$'."\t"; 

$data    = "";

$total_vendas = 0;

$sql20   = "SELECT c.id, c.codigo, c.titulo, d.titulo, e.titulo, SUM(b.qntd), SUM(b.valor_produto*b.qntd)
		    FROM bejobs_pedidos AS a 
		    INNER JOIN bejobs_pedidos_produtos AS b
		    INNER JOIN bejobs_produtos AS c
		    INNER JOIN bejobs_produtos_categorias AS d
      		INNER JOIN bejobs_produtos_marcas AS e
		    WHERE c.id_categoria = d.id
      		AND c.id_marca = e.id
      		AND c.id = b.id_produto
      		AND a.id = b.id_pedido
		    $periodo_sql 	
		    $entregador_sql	
		    $categoria_sql
			$marca_sql
			$produto_sql 	    
		    GROUP BY c.id
		    ORDER BY c.titulo";
// print_r($sql20);
$query20 = $dba->query($sql20);
$qntd20  = $dba->rows($query20);
if ($qntd20 > 0) {
	for ($y=0; $y<$qntd20; $y++) {
		$vet = $dba->fetch($query20);
		// $tpl->newBlock('vendas');
		$codigo = $vet[1];
		$titulo = stripslashes($vet[2]);
		$categoria = stripslashes($vet[3]);
		$marca = stripslashes($vet[4]);

		$qtde = $vet[5];
		$total_vendas_ = $vet[6];
		$total_vendas  = $total_vendas + $vet[6];

		$data .= $codigo. "\t";
		$data .= $titulo. "\t";
		$data .= $marca. "\t";
		$data .= $categoria. "\t";
		$data .= $qtde. "\t";
		$data .= $total_vendas_. "\t";
		$data .= "\n";
		
	}

	$data .= "Valor Total R$". "\t";
	$data .= "". "\t";
	$data .= "". "\t";
	$data .= "". "\t";
	$data .= "". "\t";
	$data .= $total_vendas. "\t";	
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Relatório-Produtos-".date('Y-m-d').".xls");  

echo utf8_decode($header)."\n".utf8_decode($data)."\n"; 

?>