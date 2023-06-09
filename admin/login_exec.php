<?php
include('./inc/inc.configdb.php');
include('./inc/inc.lib.php');

if (isset($_REQUEST['act']) && !empty($_REQUEST['act'])) {
    session_start(); //SEMPRE QUE FOR USAR A SESSION
    $act = $_REQUEST['act']; 
    $recaptcha = $_POST['g-recaptcha-response']; 
    $secret_key = '6LeetE4kAAAAADUgVB5U3gtyv97t6QE2xYQyfgZc'; 
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret='
          . $secret_key . '&response=' . $recaptcha;

	$response = file_get_contents($url);
	$response = json_decode($response);
	
    switch ($act) {

		case 'login_admin':
			$user = $_POST['user'];
			$pass = md5($_POST['pass']);
			
			$sql   = "SELECT * FROM bejobs_usuario_admin WHERE user = '$user' AND pass = '$pass' AND status = 1";
	
			$query = $dba->query($sql);
			$qntd  = $dba->rows($query);
			if ($qntd > 0 && $response->success == true) {
				$vet = $dba->fetch($query);	
			    $_SESSION['app_user_id']   = $vet['id'];
                $_SESSION['app_user_nom']  = stripslashes($vet['name']);
				$_SESSION['app_user_type'] = $vet['type'];

				// logs('Realizou login no sistema');
			    header('location: ./painel');

			} else {
			    header("location: ./?msg=123");

			}

			break;

		case 'login_gerente':
			$cpf   = preg_replace("/[^0-9]/", "", trim($_POST['cpf'])); // Retira formatação do CPF ou CNPJ
			$pass = addslashes(trim($_POST['pass']));

			$sql1   = "SELECT * FROM bejobs_usuarios WHERE cpf = '$cpf' AND status = 1 AND acesso_gerente = 1";
			$query1 = $dba->query($sql1);
			$qntd1  = $dba->rows($query1);
			if ($qntd1 > 0 && $response->success == true) {
				$vet1  = $dba->fetch($query1);

				$id           = $vet1['id'];
				$nome         = $vet1['nome'];
				$pass_usuario = $vet1['senha']; 

				if (password_verify($pass, $pass_usuario)) {
					$_SESSION['app_user_id']   = $id;
	                $_SESSION['app_user_nom']  = stripslashes($nome);
					$_SESSION['app_user_type'] = 3;

					// logs('Realizou login no sistema');
				    header('location: ./painel');

				} else {
					header("location: ../gerentes/?msg=123");
				}

			} else {
				header("location: ./gerentes/?msg=123");
			}

			break;

		case 'logout':
			
			if ($_SESSION['app_user_type'] == 1 || $_SESSION['app_user_type'] == 2) {
				session_destroy();
				header('location: ./');

			} else if ($_SESSION['app_user_type'] == 3) {
				session_destroy();
				header('location: ../gerentes/');

			} else {
				session_destroy();
				header('location: ./');
			}
			
			// logs('Realizou logout no sistema');
			break;


		default:
            header("location: ./");

    } //fim do switch
    
} else {
    header("location: ./");
}

?>