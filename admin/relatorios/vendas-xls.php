<?php
// header('Content-Type: text/html; charset=utf-8');
include('../inc/inc.verificasession.php');
// include('../inc/fpdf/fpdf.php');
include('../inc/inc.configdb.php');
include('../inc/inc.lib.php');


if ((empty($_GET['data_ini']) || !isset($_GET['data_ini'])) && (empty($_GET['data_fim']) || !isset($_GET['data_fim'])) ) {
	header('Location: ./relatorios/vendas?msg=rlt001');
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

$header  = "Data/Hora Registro"."\t"."ID"."\t"."Status"."\t"."CPF"."\t"."Nome"."\t"."Produtos - Quantidade"."\t".'Entregador'."\t"."Total Pedido R$"."\t"; 

$data    = "";

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

		$data .= $data_hora_registro. "\t";
		$data .= $id. "\t";
		$data .= $status. "\t";
		$data .= $cpf. "\t";
		$data .= $nome. "\t";

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
					$data .= $titulo_produto." - ".$qntd."x";
				} else {
					$data .= $titulo_produto." - ".$qntd."x, ";
				}
			}
		}
		$data .= "\t";
			

		$data .= $entregador. "\t";
		$data .= $total_pedido. "\t";
		$data .= "\n";
		
	}

	$data .= "Valor Total R$". "\t";
	$data .= "". "\t";
	$data .= "". "\t";
	$data .= "". "\t";
	$data .= "". "\t";
	$data .= "". "\t";
	$data .= "". "\t";
	$data .= $total_vendas. "\t";	
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Relatório-Vendas-".date('Y-m-d').".xls");  

echo utf8_decode($header)."\n".utf8_decode($data)."\n"; 

?>