<?php
// header('Content-Type: text/html; charset=utf-8');
include('../inc/inc.verificasession.php');
// include('../inc/fpdf/fpdf.php');
include('../inc/inc.configdb.php');
include('../inc/inc.lib.php');


if ((empty($_GET['data_ini']) || !isset($_GET['data_ini'])) && (empty($_GET['data_fim']) || !isset($_GET['data_fim'])) ) {
	header('Location: ./relatorios/usuarios?msg=rlt001');
	exit;
}


// Data dos filtros
$periodo_sql = "";

if (isset($_GET['data_ini']) && !empty($_GET['data_ini']) && isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
	// $tpl->assign('data_ini', $_GET['data_ini']);
	// $tpl->assign('data_fim', $_GET['data_fim']);

	$data_ini = dataMY($_GET['data_ini'])." 00:00:00";
	$data_fim = dataMY($_GET['data_fim'])." 23:59:59";

	$periodo_sql = "AND u.data_cadastro BETWEEN '$data_ini' AND '$data_fim'"; // Monta sql com datas filtradas no período
}  

$header  = "Nome"."\t"."CPF"."\t"."Email"."\t"."Celular"."\t"."Nascimento"."\t".'Data Cadastro'."\t".'Status Conta'."\t"; 

$data    = "";

$sql20   = "SELECT 
			u.nome, 
			u.cpf, 
			u.email, 
			u.telefone_celular, 
			u.nascimento, 
			u.data_cadastro, 
			u.status, 
			u.id
			FROM bejobs_usuarios AS u
			WHERE 1 $periodo_sql
			ORDER BY u.nome";
$query20 = $dba->query($sql20);
$qntd20  = $dba->rows($query20);
if ($qntd20 > 0) {
	for ($y=0; $y<$qntd20; $y++) {
		$vet20 = $dba->fetch($query20);
		// $tpl->newBlock('usuarios');
		$id_usuario = $vet20[7];
		$nome = stripslashes($vet20[0]);
		$cpf = $vet20[1];
		$email = stripslashes($vet20[2]);
		$telefone_celular = stripslashes($vet20[3]);
		$nascimento = dataBR($vet20[4]);
		$data_cadastro = datetime_date_ptbr($vet20[5])." ".datetime_time_ptbr($vet20[5]);

		$status_usuario = $vet20[6];
		$status = "";
		if ($status_usuario == 1) {
			$status = 'Ativo';
		} else {
			$status = 'Bloqueado';
		}

		$data .= $nome. "\t";
		$data .= $cpf. "\t";
		$data .= $email. "\t";
		$data .= $telefone_celular. "\t";
		$data .= $nascimento. "\t";
		$data .= $data_cadastro. "\t";
		$data .= $status. "\t";
		$data .= "\n";	
	}
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Relatório-Usuários-".date('Y-m-d').".xls");  

echo utf8_decode($header)."\n".utf8_decode($data)."\n"; 

?>