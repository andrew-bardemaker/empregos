<?php
/**
 *
 * Arquivo de configuração dos diretórios do sistema
 *
 */

// Constante que define o caminho relativo do diretorio das imagens
// if ($_SERVER['HTTP_HOST'] == 'dev.sitedofeito.com.br') {
// 	define('IMGPATH', '../../img/');

// } else {
// 	define('IMGPATH', '../img/');
// }

if ($_SERVER['HTTP_HOST'] == 'localhost') {
	define('BASE_URL', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/bejobsapp/');

} else {
	define('BASE_URL', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/');
}


?>