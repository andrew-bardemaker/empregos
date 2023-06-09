<?php
session_start();
require_once('./inc/class.TemplatePower.php');

$tpl = new TemplatePower('./tpl/login.html');
$tpl->prepare();

include_once('./inc/inc.mensagens.php');

if (isset($_SESSION['app_user_id'])) {
	header('location: ./painel');

}

$tpl->printToScreen();
?>