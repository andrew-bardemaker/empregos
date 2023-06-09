<?php
header('Content-Type: text/html; charset=utf-8');
include('../inc/inc.verificasession.php');
include('../inc/fpdf/fpdf.php');
include('../inc/inc.configdb.php');
include('../inc/inc.lib.php');
include('../inc/class.ValidaCpfCnpj.php');

if ((empty($_GET['data_ini']) || !isset($_GET['data_ini'])) && (empty($_GET['data_fim']) || !isset($_GET['data_fim'])) ) {
	header('Location: ./relatorios/vendas?msg=rlt001');
	exit;
}


class PDF extends FPDF {
	//Page header
	function Header() {
	    //Logo
	    $this->Image('../images/logo_nobg.jpg',10,8,20);
	    //Arial bold 15
	    $this->SetFont('Arial','B',10);
	    //Title
	    $this->Cell(0,15,utf8_decode('Relatórios | Vendas'),0,0,'C');
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

$cpf_sql = "";
$cpf 	 = "";
if (isset($_GET['cpf']) && !empty($_GET['cpf'])) {
	$cpf     =  preg_replace("/[^0-9]/", "", $_GET['cpf']);
	$cpf_sql = "AND b.cpf = '$cpf'";
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

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L','A4');

$pdf->SetFont('Arial','B',7);
$pdf->Cell(32,6,utf8_decode('Data Início'),1,0,'L');
$pdf->SetFont('Arial','B',7);
$pdf->Cell(35,6,utf8_decode('Data Fim'),1,0,'L');
$pdf->SetFont('Arial','B',7);
$pdf->Cell(105,6,utf8_decode('CPF'),1,0,'L');
$pdf->SetFont('Arial','B',7);
$pdf->Cell(105,6,utf8_decode('Entregador'),1,1,'L');

$pdf->SetFont('Arial','',7);
$pdf->Cell(32,6,utf8_decode($_GET['data_ini']),1,0,'L');
$pdf->SetFont('Arial','',7);
$pdf->Cell(35,6,utf8_decode($_GET['data_fim']),1,0,'L');
$pdf->SetFont('Arial','',7);
$pdf->Cell(105,6,utf8_decode($cpf),1,0,'L');
$pdf->SetFont('Arial','',7);
$pdf->Cell(105,6,utf8_decode($entregador),1,1,'L');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',7);
$pdf->Cell(25,6,utf8_decode('Data/Hora Registro'),1,0,'L');
$pdf->Cell(12,6,utf8_decode('#ID'),1,0,'L');
$pdf->Cell(25,6,utf8_decode('Status'),1,0,'L');
$pdf->Cell(20,6,utf8_decode('CPF'),1,0,'L');
$pdf->Cell(50,6,utf8_decode('Nome'),1,0,'L');
$pdf->Cell(30,6,utf8_decode('Entregador'),1,0,'L');
$pdf->Cell(35,6,utf8_decode('Total Pedido R$'),1,0,'L');
$pdf->Cell(80,6,utf8_decode('Produtos - Quantidade'),1,1,'L');

$total_vendas = 0;

$sql20   = "SELECT a.id, 
			a.data_hora_registro, 
			a.total_pedido, 
			a.status, 
			b.nome, 
			b.cpf,
			c.nome
		    FROM bejobs_pedidos AS a 
		    INNER JOIN bejobs_usuarios AS b
		    INNER JOIN bejobs_entregadores AS c
		    WHERE a.id_usuario = b.id
		    AND a.id_entregadores = c.id
		    AND a.status = 4
		    $periodo_sql 
		    $cpf_sql
		    $entregador_sql
		    ORDER BY a.data_hora_registro DESC";
// print_r($sql20);
$query20 = $dba->query($sql20);
$qntd20  = $dba->rows($query20);
if ($qntd20 > 0) {
	for ($y=0; $y<$qntd20; $y++) {
		$vet = $dba->fetch($query20);
		// $tpl->newBlock('vendas');
		$id                 = $vet[0];
		$data_hora_registro = datetime_date_ptbr($vet[1]).' '.datetime_time_ptbr($vet[1]);
		$total_pedido       = moeda($vet[2]);		
		$nome               = stripslashes($vet[4]);
		$cpf                = stripslashes($vet[5]);

		$total_vendas = $total_vendas + $vet[2];

		$status_ = $vet[3];
		if ($status_ == 1) {
			$status = "Realizado/ Aguardando aprovação";
		} else if ($status_ == 2) {
			$status = "Aceito";
		} else if ($status_ == 3) {
			$status = "Processo de entrega";
		} else if ($status_ == 4) {
			$status = "Pedido Entregue";
		} else if ($status_ == 5) {
			$status = "Cancelado";
		}

		$entregador = stripslashes($vet[6]);

		$produtos = "";
		$sql5   = "SELECT * FROM bejobs_pedidos_produtos WHERE id_pedido = $id";
		$query5 = $dba->query($sql5);
		$qntd5  = $dba->rows($query5);
		if ($qntd5 > 0) {
			for ($j=0; $j<$qntd5; $j++) {
				// $tpl->newBlock('produtos');
				$vet5           = $dba->fetch($query5);
				$id_produto     = $vet5['id_produto'];
				$titulo_produto = $vet5['titulo_produto'];
				// $valor_produto = moeda($vet5['valor_produto']);
				$qntd = $vet5['qntd'];
				// $valor_total_produto = moeda($vet5['valor_produto']*$vet5['qntd']);

				if ($j+1 == $qntd5) {
					$produtos .= $titulo_produto." - ".$qntd."x";
				} else {
					$produtos .= $titulo_produto." - ".$qntd."x\n";
				}
			}
		}

		$height = $qntd5 * 5;

		$pdf->SetFont('Arial','',7);
		$pdf->Cell(25,$height,utf8_decode($data_hora_registro),1,0,'L');
		$pdf->Cell(12,$height,utf8_decode($id),1,0,'L');
		$pdf->Cell(25,$height,utf8_decode($status),1,0,'L');
		$pdf->Cell(20,$height,utf8_decode($cpf),1,0,'L');
		$pdf->Cell(50,$height,utf8_decode($nome),1,0,'L');		
		$pdf->Cell(30,$height,utf8_decode($entregador),1,0,'L');
		$pdf->Cell(35,$height,utf8_decode($total_pedido),1,0,'L');
		$pdf->MultiCell(80,5,"$produtos",1,'L',false);
		
	}

	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(162,6,utf8_decode('Valor Total R$'),1,0,'L');
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(35,6,utf8_decode(moeda($total_vendas)),1,1,'L');
	
}

$pdf->Output(utf8_decode("Relatório-Vendas-".date('Y-m-d').".pdf"), "D");

?>