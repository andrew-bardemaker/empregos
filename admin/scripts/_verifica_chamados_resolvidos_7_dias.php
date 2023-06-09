<?php
/**
 *
 * 
 *
 */
include('../inc/inc.configdb.php');
include('../inc/inc.lib.php');
include('../inc/phpmailer/PHPMailerAutoload.php');

$sql   = "SELECT id FROM bejobs_chamados WHERE status = 4";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i < $qntd ; $i++) { 
		$vet = $dba->fetch($query);
		$id_chamado = $vet[0];

		$sql2   = "SELECT data_registro FROM bejobs_chamados_interacao WHERE id_chamado = $id_chamado ORDER BY data_registro DESC LIMIT 1";
		$query2 = $dba->query($sql2); 
		$vet2   = $dba->fetch($query2);
		$data_ultima_interacao = $vet2[0];
		$data_atual = date('Y-m-d H:i:s');

		$data1 = new DateTime($data_atual);
		$data2 = new DateTime($data_ultima_interacao);

		$intervalo  = $data1->diff($data2);
		$total_dias = $intervalo->days;

		if ($total_dias >= 7) {
			$mensagem = "Chamado fechado automaticamente após 7 dias de inatividade.";

			$sql5 = "INSERT INTO bejobs_chamados_interacao (mensagem, tipo_usuario, id_usuario, data_registro, id_chamado) VALUES ('$mensagem', 4, 1, NOW(), $id_chamado)"; //die($sql);
            $dba->query($sql5);

            $sql4 = "UPDATE bejobs_chamados SET status = 5 WHERE id = $id_chamado";
            $dba->query($sql4);

            $sql5 = "INSERT INTO bejobs_chamados_avaliacao (id_chamado, nota) VALUES ($id_chamado, 6)"; //die($sql);
            $dba->query($sql5);

            $sql3   = "SELECT id_usuario, assunto FROM bejobs_chamados WHERE id = $id_chamado"; // print_r($sql3);
            $query3 = $dba->query($sql3);
            $qntd3  = $dba->rows($query3);
            if ($qntd3 > 0) {
				$vet3       = $dba->fetch($query3);
				$id_usuario = $vet3[0];                  
				$assunto    = $vet3[1];

				$sql4   = "SELECT nome, email FROM bejobs_usuarios WHERE id = $id_usuario";
				$query4 = $dba->query($sql4);
				$qntd4  = $dba->rows($query4);
				if ($qntd4 > 0) {
					$vet4          = $dba->fetch($query4);
					$nome_usuario  = addslashes($vet4[0]);
					$email_usuario = addslashes($vet4[1]);
				}

				// Defini informações da notificação
				$notificacoes_titulo = addslashes("[Chamado ".$id_chamado."] ".$assunto);
				$notificacoes_texto  = addslashes("Olá ".$nome_usuario.", <br>Seu chamado #".$id_chamado." foi atualizado. <br>Equipe bejobs APP");

				// Grava nova notificação
				$sql2 = "INSERT INTO bejobs_notificacoes_usuarios (data_registro, titulo, texto, status, id_usuario) VALUES (NOW(), '$notificacoes_titulo', '$notificacoes_texto', 1, $id_usuario)";
				$dba->query($sql2);

				if (!empty($email_usuario) && validaEmail($email_usuario)) {
					/* Envia email de notificação */
					$mail = new PHPMailer;
                    //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'smtp.bejobsapp.com.br';              // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'noreply@bejobsapp.com.br';   // SMTP username
                    $mail->Password = 'bejobs2020';                        // SMTP password
                    $mail->SMTPSecure = false;                            // Define se é utilizado SSL/TLS - Mantenha o valor "false"
                    $mail->SMTPAutoTLS = false;                           // Define se, por padrão, será utilizado TLS - Mantenha o valor "false"
                    $mail->Port = 587;                                    // TCP port to connect to

                    $mail->setFrom('noreply@bejobsapp.com.br', 'bejobs APP');

					$mail->addAddress($email_usuario);                            // Name is optional
					// $mail->addReplyTo('meajuda@buffon.com.br');

					$mail->isHTML(true);                                   // Set email format to HTML
					$mail->CharSet = 'UTF-8';

					$mail->Subject = "[Chamado $id_chamado] $assunto";
					$mail->Body    = "
					                  Olá $nome_usuario,<br><br>
					                  Seu chamado #$id_chamado foi atualizado. Acesse sua conta para saber mais.<br>
					                  <a href='https://bejobsapp.com.br/'>https://bejobsapp.com.br/</a><br><br>
					                  Equipe bejobs APP<br><br>";          
					$mail->send();
				}                  
            }
		}		
	}
}


?>