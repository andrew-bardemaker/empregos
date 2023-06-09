<?php
header('Content-Type: text/html; charset=utf-8');
include('../inc/inc.verificasession.php');
include('../inc/fpdf/fpdf.php');
include('../inc/inc.configdb.php');
include('../inc/inc.lib.php');
include('../inc/class.ValidaCpfCnpj.php');

if ((empty($_GET['data_ini']) || !isset($_GET['data_ini'])) && (empty($_GET['data_fim']) || !isset($_GET['data_fim'])) ) {
	header('Location: ./relatorios/produtos?msg=rlt001');
	exit;
}


class PDF extends FPDF {
	//Page header
	function Header() {
	    //Logo
	    $this->Image('../images/logo2.jpg',10,8,20);
	    //Arial bold 15
	    $this->SetFont('Arial','B',10);
	    //Title
	    $this->Cell(0,15,utf8_decode('Relatórios | Produtos'),0,0,'C');
	    //Line break
	    $this->Ln(20);
	}

	//Page footer
	function Footer() {
	    //Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    //Arial italic 8
	    $this->SetFont('Arial','',8);
	    //Page number
	    $this->Cell(0,10,utf8_decode('Data de Emissão: '.date('d/m/Y H:i:s').' - Usuário: '.utf8_decode($_SESSION['app_user_nom'])),0,0,'L');
	    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'R');
	}
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

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L','A4');

$pdf->SetFont('Arial','B',7);
$pdf->Cell(32,6,utf8_decode('Data Início'),1,0,'L');
$pdf->SetFont('Arial','B',7);
$pdf->Cell(35,6,utf8_decode('Data Fim'),1,0,'L');
$pdf->SetFont('Arial','B',7);
$pdf->Cell(52,6,utf8_decode('Entregador'),1,0,'L');
$pdf->SetFont('Arial','B',7);
$pdf->Cell(52,6,utf8_decode('Categoria'),1,0,'L');
$pdf->SetFont('Arial','B',7);
$pdf->Cell(53,6,utf8_decode('Marca'),1,0,'L');
$pdf->SetFont('Arial','B',7);
$pdf->Cell(53,6,utf8_decode('Produto'),1,1,'L');

$pdf->SetFont('Arial','',7);
$pdf->Cell(32,6,utf8_decode($_GET['data_ini']),1,0,'L');
$pdf->SetFont('Arial','',7);
$pdf->Cell(35,6,utf8_decode($_GET['data_fim']),1,0,'L');
$pdf->SetFont('Arial','',7);
$pdf->Cell(52,6,utf8_decode($entregador),1,0,'L');
$pdf->SetFont('Arial','',7);
$pdf->Cell(52,6,utf8_decode($categoria),1,0,'L');
$pdf->SetFont('Arial','',7);
$pdf->Cell(53,6,utf8_decode($marca),1,0,'L');
$pdf->SetFont('Arial','',7);
$pdf->Cell(53,6,utf8_decode($produto),1,1,'L');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',7);
$pdf->Cell(32,6,utf8_decode('Código'),1,0,'L');
$pdf->Cell(55,6,utf8_decode('Título'),1,0,'L');
$pdf->Cell(55,6,utf8_decode('Marca'),1,0,'L');
$pdf->Cell(50,6,utf8_decode('Categoria'),1,0,'L');
$pdf->Cell(50,6,utf8_decode('Qtde Total'),1,0,'L');
$pdf->Cell(35,6,utf8_decode('Total Pedido R$'),1,1,'L');

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
		$total_vendas_ = moeda($vet[6]);
		$total_vendas = $total_vendas + $vet[6];

		$pdf->SetFont('Arial','',7);
		$pdf->Cell(32,6,utf8_decode($codigo),1,0,'L');
		$pdf->Cell(55,6,utf8_decode($titulo),1,0,'L');
		$pdf->Cell(55,6,utf8_decode($marca),1,0,'L');
		$pdf->Cell(50,6,utf8_decode($categoria),1,0,'L');
		$pdf->Cell(50,6,utf8_decode($qtde),1,0,'L');
		$pdf->Cell(35,6,utf8_decode($total_vendas_),1,1,'L');
		
	}

	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(242,6,utf8_decode('Valor Total R$'),1,0,'L');
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(35,6,utf8_decode(moeda($total_vendas)),1,1,'L');
	
}

$pdf->Output(utf8_decode("Relatório-Produtos-".date('Y-m-d').".pdf"), "D");

?>