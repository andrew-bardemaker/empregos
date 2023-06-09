<?php
// para mostar TODOS os avisos
error_reporting(0);

header('Content-Type: text/html; charset=utf-8');

// conex�o com o banco de dados
	define('HOSTDB','mysql.dedstudio.com.br');
	define('USERDB','dedstudio12');
	define('PASSDB','p4ineld3d');
	define('BASEDB','dedstudio12');

include_once('class.DbAdmin.php');
$dba = new DbAdmin('mysqli');
$dba->connect(HOSTDB, USERDB, PASSDB, BASEDB);

// mysql_query("SET NAMES 'utf8'");
// mysql_query('SET character_set_connection=utf8');
// mysql_query('SET character_set_client=utf8');
// mysql_query('SET character_set_results=utf8');

$dba->query("SET SQL_BIG_SELECTS=1");
