<?php

/**
 * @author			Andrew Bardemaker - v 1.0 - mar/2019
 * @description 	Serviço cadastro chamado
 * @params

 */
// session_start();
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Credentials: true');
header('Content-type: application/json');
header('X-Content-Type-Options: nosniff');

include('../admin/inc/inc.configdb.php');
include('../admin/inc/inc.lib.php');
include('../admin/inc/phpmailer/PHPMailerAutoload.php');

$array = array();

$id_usuario = $_GET['id_usuario'];

$assunto = $_GET['assunto'];
$categoria = $_GET['categoria'];
$mensagem = $_GET['mensagem'];
$ip_registro = getIp();


if (
	!empty($id_usuario) && isset($id_usuario) && is_numeric($id_usuario) &&
	!empty($assunto) && isset($assunto) &&
	!empty($categoria) && $categoria != "" && is_numeric($categoria) &&
	!empty($mensagem) && isset($mensagem)
) {

	$anexo = "teste";
	// Verifica se foi enviado algum arquivo
	/* if (!empty($_FILES['anexo'])) {  
		$arquivo = $_FILES['anexo']['name'];
		$extensoes_invalidas = array("exe");
		// Verifica se extensão do arquivo está presente no array de extenções inválidas
		if (in_array(pathinfo($arquivo, PATHINFO_EXTENSION), $extensoes_invalidas)) { echo "arquivo_extensao"; exit; }
		$diretorio_upload = "../files/chamados/".date("Y")."/".date("m")."/".date("d")."/"; // Cria diretório com data atual

		if(!is_dir($diretorio_upload)) { // Verifica se já existe o diretório
		    mkdir($diretorio_upload, 0775, true); // Cria o diretório
		    chmod($diretorio_upload, 0775);
		}

		// Move arquivo para pasta selecionada
		move_uploaded_file($_FILES["anexo"]["tmp_name"], $diretorio_upload.$arquivo);     
		// Caminho do arquivo anexo
		$anexo = str_replace("../", "", $diretorio_upload).$arquivo;                  
    }
 */

	$sql = "INSERT INTO bejobs_chamados (assunto, mensagem, id_categoria, telefone, id_usuario, status, data_registro, ip_registro, anexo) VALUES ('$assunto', '$mensagem', $categoria, '$telefone', $id_usuario, 1, NOW(), '$ip_registro', '$anexo')"; //die($sql);
	$query = $dba->query($sql);

	$id_chamado = $dba->lastid();

	/*----------  Notificação & email interno  ----------*/

	// Defini informações da notificação
	$notificacoes_titulo = "Novo Chamado Aberto";
	$notificacoes_texto  = addslashes("Um novo chamado foi aberto ID: <a href='chamados-visualizar?id=" . $id_chamado . "'>#" . $id_chamado . "</a>");

	// Grava nova notificação
	$sql2 = "INSERT INTO bejobs_notificacoes_admin (data_registro, titulo, texto, status, tipo, id_chamado) VALUES (NOW(), '$notificacoes_titulo', '$notificacoes_texto', 1, 1, $id_chamado)";
	$dba->query($sql2);

	// Email
	/* $mail2 = new PHPMailer;
	// $mail2->SMTPDebug = 3;                              // Enable verbose debug output
	$mail2->isSMTP();                                      // Set mailer to use SMTP
	$mail2->Host = 'smtp.bejobs.com.br';          // Specify main and backup SMTP servers
	$mail2->SMTPAuth = true;                               // Enable SMTP authentication
	$mail2->Username = 'noreply@bejobs.com.br';   // SMTP username
	$mail2->Password = 'bejobs2020';                          // SMTP password
	$mail2->SMTPSecure = false; 							  // Define se é utilizado SSL/TLS - Mantenha o valor "false"
	$mail2->SMTPAutoTLS = false; 						  // Define se, por padrão, será utilizado TLS - Mantenha o valor "false"
	$mail2->Port = 587;                                    // TCP port to connect to

	$mail2->setFrom('andreywillian@dedstudio.com.br', 'Teste Llevo');
	$mail2->addAddress('andreywillian@dedstudio.com.br');
	// $mail2->addBCC('roberto@dedstudio.com.br');
	$mail2->isHTML(true);
	$mail2->CharSet = 'UTF-8';

	$mail2->Subject = "[Novo Chamado " . $id_chamado . " Aberto] " . $assunto;
	$mail2->Body    = "Um novo chamado foi aberto ID: <a href='https://bejobs.com.br/admin/chamados-visualizar?id=" . $id_chamado . "'>#" . $id_chamado . "</a>";

	$mail2->send(); */

	/*----------  Notificação & email usuário  ----------*/
	$sql4   = "SELECT nome, email FROM bejobs_usuarios WHERE id = $id_usuario";
	$query4 = $dba->query($sql4);
	$vet4   = $dba->fetch($query4);
	$nome_usuario  = addslashes($vet4[0]);
	$email_usuario = addslashes($vet4[1]);

	$notificacoes_titulo = addslashes("[Chamado " . $id_chamado . " Criado] " . $assunto);
	$notificacoes_texto  = addslashes("Olá, " . $nome_usuario . ", <br><br>
									   Acabamos de receber e registrar o seu contato e um chamado (" . $id_chamado . ") foi criado e encaminhado para nossa equipe de suporte. No prazo de 24h a partir do recebimento desta notificação, iremos respondê-lo.<br><br>
									   Atenciosamente,<br>
									   Equipe Llevo APP");

	// Grava nova notificação
	$sql2 = "INSERT INTO bejobs_notificacoes_usuarios (data_registro, titulo, texto, status, id_usuario) VALUES (NOW(), '$notificacoes_titulo', '$notificacoes_texto', 1, $id_usuario)";
	$dba->query($sql2);

	/* $mail = new PHPMailer;
	// $mail->SMTPDebug = 3;                              // Enable verbose debug output
	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.bejobs.com.br';          // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'noreply@bejobs.com.br';   // SMTP username
	$mail->Password = 'bejobs2020';                          // SMTP password
	$mail->SMTPSecure = false; 							  // Define se é utilizado SSL/TLS - Mantenha o valor "false"
	$mail->SMTPAutoTLS = false; 						  // Define se, por padrão, será utilizado TLS - Mantenha o valor "false"
	$mail->Port = 587;                                    // TCP port to connect to

	$mail->setFrom('noreply@bejobs.com.br', 'Llevo App');
	$mail->addAddress($email_usuario);
	// $mail->addBCC('anderson@dedstudio.com.br');
	// $mail->addBCC('roberto@dedstudio.com.br');
	// $mail->addReplyTo($email);
	$mail->isHTML(true);
	$mail->CharSet = 'UTF-8';

	$mail->Subject = "[Chamado " . $id_chamado . " Criado] " . $assunto;
	$mail->Body    = "Olá, " . $nome_usuario . ", <br><br>
					  Acabamos de receber e registrar o seu contato e um chamado (" . $id_chamado . ") foi criado e encaminhado para nossa equipe de suporte. No prazo de 24h a partir do recebimento desta notificação, iremos respondê-lo.<br><br>
					  Atenciosamente,<br>
					  Equipe Llevo APP";

	$mail->send(); */

	$array = array("success" => "true");
} else {
	$array = array("error" => "true", "type" => "parametros", "msg" => "Parâmetros inválidos.");
}

header('Content-type: application/json');
echo json_encode($array);
