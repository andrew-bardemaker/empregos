<?php
// header('Content-Type: text/html; charset=utf-8');
include('../inc/inc.verificasession.php');
// include('../inc/fpdf/fpdf.php');
include('../inc/inc.configdb.php');
include('../inc/inc.lib.php');


if ((empty($_GET['data_ini']) || !isset($_GET['data_ini'])) && (empty($_GET['data_fim']) || !isset($_GET['data_fim']))) {
    header('Location: ./relatorios/usuarios?msg=rlt001');
    exit;
}


// Data dos filtros
$periodo_sql = "";

if (isset($_GET['data_ini']) && !empty($_GET['data_ini']) && isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
    // $tpl->assign('data_ini', $_GET['data_ini']);
    // $tpl->assign('data_fim', $_GET['data_fim']);

    $data_ini = dataMY($_GET['data_ini']) . " 00:00:00";
    $data_fim = dataMY($_GET['data_fim']) . " 23:59:59";

    $periodo_sql = "AND vigencia BETWEEN '$data_ini' AND '$data_fim'"; // Monta sql com datas filtradas no período
}

$header  = "Nome" . "\t" . "Vigência" . "\t" . "Título" . "\t" . "Área" . "\t" . 'Status Conta' . "\t";

$data    = "";


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
    for ($y = 0; $y < $rows; $y++) {
        $vet20 = $dba->fetch($query);
        // $tpl->newBlock('usuarios');
        $id_usuario = $vet20[0];
        $nome = stripslashes($vet20[1]);
        $vigencia = datetime_date_ptbr($vet20[2]) . " " . datetime_time_ptbr($vet20[2]);
        $titulo = stripslashes($vet20[3]);
        $area = stripslashes($vet20[5]);

        $status_usuario = $vet20[4];
        $status = "";
        if ($status_usuario == 1) {
            $status = 'Ativo';
        } else {
            $status = 'Bloqueado';
        }

        $data .= $nome . "\t";
        $data .= $vigencia . "\t";
        $data .= $titulo . "\t";
        $data .= $area . "\t";
        $data .= $status . "\t"; 
        $data .= "\n";
    }
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Relatório-Usuários-" . date('Y-m-d') . ".xls");

echo utf8_decode($header) . "\n" . utf8_decode($data) . "\n";
