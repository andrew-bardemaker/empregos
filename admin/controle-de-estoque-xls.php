<?php
// header('Content-Type: text/html; charset=utf-8');
include('./inc/inc.verificasession.php');
// include('./inc/fpdf/fpdf.php');
include('./inc/inc.configdb.php');
include('./inc/inc.lib.php');


if ((empty($_GET['data_ini']) || !isset($_GET['data_ini'])) && (empty($_GET['data_fim']) || !isset($_GET['data_fim'])) ) {
	header('Location: ./controle-de-estoque?msg=rlt001');
	exit;
}

if (isset($_GET['data_ini']) && !empty($_GET['data_ini']) && isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
	$data_ini = dataMY($_GET['data_ini'])." 00:00:00";
	$data_fim = dataMY($_GET['data_fim'])." 23:59:59";
} 

$header  = "Código Produto"."\t"."Descrição"."\t"."Categoria"."\t"."Marca"."\t"."Valor Total Compras R$"."\t"."Qtde Total Compras"."\t"."Valor Total Vendas R$"."\t"."Qtde Total Vendas"."\t"."Qtde Total Baixas"."\t"."Estoque"."\t"; 

$data    = "";

$sql   = "SELECT a.id, a.titulo, b.titulo, c.titulo, a.status, a.destaque, a.codigo
          FROM bejobs_produtos AS a
          INNER JOIN bejobs_produtos_categorias AS b
          INNER JOIN bejobs_produtos_marcas AS c
          WHERE a.id_categoria = b.id
          AND a.id_marca = c.id
          ORDER BY codigo";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		// $tpl->newBlock('estoque');
		// $tpl->assign('id', $vet[0]);
		$id_produto = $vet[0];
		// $tpl->assign('codigo_produto', $vet[6]);
		$codigo_produto = $vet[6];
		$descricao_produto = stripslashes($vet[1]);
		$categoria = stripslashes($vet[2]);
		$marca = stripslashes($vet[3]);

		$qtde_total_vendas   = 0; 
		$qtde_total_compras  = 0; 
		$valor_total_vendas  = 0;
		$valor_total_compras = 0;
		$qtde_total_baixas   = 0;

		$sql1 = "SELECT SUM(b.qntd), SUM(b.valor_produto*b.qntd)
				 FROM bejobs_pedidos AS a
				 INNER JOIN bejobs_pedidos_produtos AS b 
				 WHERE a.id = b.id_pedido
				 AND b.id_produto = $id_produto
				 AND a.data_hora_registro BETWEEN '$data_ini' AND '$data_fim'
				 AND a.status = 4";
		// print_r($sql1);
		$query1 = $dba->query($sql1);
		$vet1   = $dba->fetch($query1);
		if ($vet1[0]!="") {
			$qtde_total_vendas  = $vet1[0];
			$valor_total_vendas = $vet1[1];
		}		
		$qtde_total_vendas = $qtde_total_vendas;
		$valor_total_vendas = number_format($valor_total_vendas, 2, ",", ".");

		$sql2 = "SELECT SUM(b.qtde), SUM(b.vl_total)
				 FROM bejobs_compras AS a
				 INNER JOIN bejobs_compras_produtos AS b 
				 INNER JOIN bejobs_produtos AS c
				 WHERE a.chave_acesso = b.nfce_chave_acesso
				 AND b.id_produto = c.codigo
				 AND c.id = $id_produto
				 AND a.data_emissao BETWEEN '$data_ini' AND '$data_fim'";
		$query2 = $dba->query($sql2);
		$vet2   = $dba->fetch($query2);
		if ($vet2[0]!="") {
			$qtde_total_compras  = $vet2[0];
			$valor_total_compras = $vet2[1];
		}	
		$qtde_total_compras = $qtde_total_compras;
		$valor_total_compras = number_format($valor_total_compras, 2, ",", ".");

		$sql3 = "SELECT SUM(a.qtde)
				 FROM bejobs_estoque_baixas AS a
				 WHERE a.id_produto = $id_produto
				 AND a.data_registro BETWEEN '$data_ini' AND '$data_fim'";
		// print_r($sql3);
		$query3 = $dba->query($sql3);
		$vet3   = $dba->fetch($query3);
		if ($vet3[0]!="") {
			$qtde_total_baixas = $vet3[0];
		}		
		$qtde_total_baixas = $qtde_total_baixas;

		$total = $qtde_total_compras-$qtde_total_vendas-$qtde_total_baixas;

		$data .= '"=""'.$codigo_produto.'"""'. "\t";
		$data .= $descricao_produto. "\t";
		$data .= $categoria. "\t";
		$data .= $marca. "\t";
		$data .= $valor_total_compras. "\t";
		$data .= $qtde_total_compras. "\t";
		$data .= $valor_total_vendas. "\t";
		$data .= $qtde_total_vendas. "\t";
		$data .= $qtde_total_baixas. "\t";
		$data .= $total. "\t";
		$data .= "\n";

	}
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Controle-de-Estoque-Geral-".$_GET['data_ini']."-".$_GET['data_fim'].".xls");  

echo utf8_decode($header)."\n".utf8_decode($data)."\n"; 

?>