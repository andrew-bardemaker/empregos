<?php
/**
 *
 * Verficação da variável de sessão de identificação do usuário logado
 *
*/

session_start();
if (!isset($_SESSION['app_user_id'])) {
	session_destroy();
	header('location: https://bejobsapp.com.br/admin/?msg=456');
}

?>