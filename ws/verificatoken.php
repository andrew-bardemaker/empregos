<?php
header('Content-Type: text/html; charset=utf-8');

// Conexão com o banco de dados
define('HOSTDB2','mysql.dedstudio.com.br');
define('USERDB2','dedstudio08');
define('PASSDB2','p4ineld3d');
define('BASEDB2','dedstudio08');


function verificaToken($token, $id_usuario) {

	include_once('../admin/inc/class.DbAdmin.php');
	$dba = new DbAdmin('mysqli');
	$dba->connect(HOSTDB2, USERDB2, PASSDB2, BASEDB2);
	$dba->query("SET SQL_BIG_SELECTS=1");

	$tmp = false;

	$sql99   = "SELECT * FROM bejobs_usuarios_token WHERE id_usuarios = $id_usuario AND token = '$token'";
	$query99 = $dba->query($sql99);
	$qntd99  = $dba->rows($query99); 
	//print_r($sql99);
	
	if ($qntd99 > 0) {
		$tmp = true;

		$sql98   = "SELECT status FROM bejobs_usuarios WHERE id = $id_usuario";
		$query98 = $dba->query($sql98);
		$vet98   = $dba->fetch($query98);
		$status  = $vet98[0];

		if ($status == 0) {
			$tmp = false;
		}
	}

	return $tmp;
}

function verificaTokenEntregador($token, $id_usuario) {

	include_once('../admin/inc/class.DbAdmin.php');
	$dba = new DbAdmin('mysqli');
	$dba->connect(HOSTDB2, USERDB2, PASSDB2, BASEDB2);
	$dba->query("SET SQL_BIG_SELECTS=1");

	$tmp = false;

	$sql99   = "SELECT * FROM gec_entregadores_token WHERE id_entregadores = $id_usuario AND token = '$token'"; // print_r($sql99);
	$query99 = $dba->query($sql99);
	$qntd99  = $dba->rows($query99);
	if ($qntd99 > 0) {
		$tmp = true;

		$sql98   = "SELECT status FROM gec_entregadores WHERE id = $id_usuario";
		$query98 = $dba->query($sql98);
		$vet98   = $dba->fetch($query98);
		$status  = $vet98[0];

		if ($status == 0) {
			$tmp = false;
		}
	}
	
	return $tmp;
}
