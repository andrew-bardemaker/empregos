<?php
header('Content-Type: text/html; charset=utf-8');
include('../inc/inc.verificasession.php');
include('../inc/fpdf/fpdf.php');
include('../inc/inc.configdb.php');
include('../inc/inc.lib.php');
include('../inc/class.ValidaCpfCnpj.php');
 
if ((empty($_GET['data_ini']) || !isset($_GET['data_ini'])) && (empty($_GET['data_fim']) || !isset($_GET['data_fim'])) ) {
	header('Location: ./relatorios/anuncios?msg=rlt001');
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
	    $this->Cell(0,15,utf8_decode('Relatórios | Usuários'),0,0,'C');
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

	$periodo_sql = "AND vigencia BETWEEN '$data_ini' AND '$data_fim'"; // Monta sql com datas filtradas no período
} 
 
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L','A4');

$pdf->SetFont('Arial','B',7);
$pdf->Cell(137,6,utf8_decode('Data Início'),1,0,'L');
$pdf->SetFont('Arial','B',7);
$pdf->Cell(140,6,utf8_decode('Data Fim'),1,1,'L');
// $pdf->SetFont('Arial','B',7);
// $pdf->Cell(210,6,utf8_decode('CPF'),1,1,'L');

$pdf->SetFont('Arial','',7);
$pdf->Cell(137,6,utf8_decode($_GET['data_ini']),1,0,'L');
$pdf->SetFont('Arial','',7);
$pdf->Cell(140,6,utf8_decode($_GET['data_fim']),1,1,'L');
// $pdf->SetFont('Arial','',7);
// $pdf->Cell(210,6,utf8_decode($cpf),1,1,'L');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',7);
$pdf->Cell(65,6,utf8_decode('Nome'),1,0,'L'); 
$pdf->Cell(42,6,utf8_decode('Vigência'),1,0,'L');
$pdf->Cell(42,6,utf8_decode('Titulo'),1,0,'L');
$pdf->Cell(45,6,utf8_decode('Área'),1,0,'L'); 
$pdf->Cell(30,6,utf8_decode('Status Conta'),1,1,'L');


    $sql = "SELECT 
                   bejobs_anuncios.id, 
                   bejobs_usuarios.nome, 
                   bejobs_anuncios.vigencia,
                   bejobs_anuncios.titulo,
                   bejobs_anuncios.status,
                   bejobs_anuncios.area        
            FROM bejobs_anuncios 
            INNER JOIN bejobs_usuarios ON bejobs_usuarios.id = bejobs_anuncios.id_usuario
            WHERE 1=1 $periodo_sql";
    $query = $dba->query($sql);
    $rows = $dba->rows($query);
 
if ($rows > 0) {
	for ($y=0; $y<$rows; $y++) { 
		$vet20 = $dba->fetch($query);
		// $tpl->newBlock('usuarios');
		$id_usuario = $vet20[0];
		$nome = stripslashes($vet20[1]); 
		$vigencia = datetime_date_ptbr($vet20[2])." ".datetime_time_ptbr($vet20[2]);
		$titulo = stripslashes($vet20[3]);  
		$area = stripslashes($vet20[5]);  

		$status_usuario = $vet20[4];
		$status = "";
		if ($status_usuario == 1) {
			$status = 'Ativo';
		} else {
			$status = 'Bloqueado';
		}
		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(65,6,utf8_decode($nome),1,0,'L');
		$pdf->Cell(42,6,utf8_decode($vigencia),1,0,'L');
		$pdf->Cell(42,6,utf8_decode($titulo),1,0,'L'); 
		$pdf->Cell(45,6,utf8_decode($area),1,0,'L'); 
		$pdf->Cell(30,6,utf8_decode($status),1,1,'L');
	}
} 

$pdf->Output(utf8_decode("Relatório-Anúncios-".date('Y-m-d').".pdf"), "D");

?>