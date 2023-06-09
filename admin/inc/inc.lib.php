<?php
/**
 * Biblioteca de funções
 **/

/**
 * Função que retorna a data de hoje no formato português brasileiro
 */
function data() {
	return date("d/m/Y");
}

/**
 * Função que retorna a hora : minuto : segundo de agora
 */
function hora() {
	return date("H:i:s");
}

/**
 * Recebe um número e retorna no formato moeda BR 
 */
function moeda($val) {
	$val = number_format($val, 2, ',', '.');
	return $val;
}

/**
 * Recebe um número e retorna no formato moeda BR 
 */
function moeda2($val) {
	$val = floor($val * 100) / 100;
	$val = number_format((float)$val, 2, ',', '.');
	return $val;
}

/**
 * Recebe um número e retorna no formato bonificação APP BUFFON 
 */
function bonificacao($val) {
	$val = number_format($val, 4, ',', '.');
	return $val;
}

/**
 * Recebe um número e retorna no formato bonificação APP BUFFON 
 */
function bonificacao_reputacao($val) {
	$val = number_format($val, 2, ',', '.');
	return $val;
}

/**
 * Recebe um número e retorna no formato bonificação APP BUFFON 
 */
function bonificacao_promo($val) {
	$val = number_format($val, 2, ',', '.');
	return $val;
}

/**
 * Recebe um valor no formato moeda BR e retorna um número
 */
function numero($val) {
	$val = str_replace('.', '', $val); //primeiro tira o ponto 1.519,80
	$val = str_replace(',', '.', $val); //troca a vírgula por ponto 1519.8
	return $val * 1; //gambeta pra tirar o zero do final se existir
}

/**
 * Recebe uma data no formato aaaa-mm-dd e retorna no formato BR
 */
function sendEmail($from, $to,$assunto,$html) { 
	
	include('phpmailer/PHPMailerAutoload.php');

    $mail = new PHPMailer; 
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';              // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'andrew.bardemaker1997@gmail.com';   // SMTP username
    $mail->Password = '********';                        // SMTP password
    $mail->SMTPSecure = false;                            // Define se é utilizado SSL/TLS - Mantenha o valor "false"
    $mail->SMTPAutoTLS = false;                           // Define se, por padrão, será utilizado TLS - Mantenha o valor "false"
    $mail->Port = 587;                                    // TCP port to connect to

	

    $mail->From = $from; 
	$mail->AddAddress($from,$to);                			  // Name is optional 
	$mail->isHTML(true);
	$mail->CharSet = 'UTF-8';

	$mail->Subject = $assunto;
	$mail->MsgHTML($html);
	if($mail->send()){
		echo "YIPEE";
	}else{
		echo $mail->ErrorInfo;
	} 
}


/**
 * Recebe uma data no formato aaaa-mm-dd e retorna no formato BR
 */
function dataBR($val, $sep = '-') {
	$vet = explode($sep, $val);
	return $vet[2].'/'.$vet[1].'/'.$vet[0];
}

/**
 * Recebe uma data no formata dd/mm/aaaa e retorna no formato mysql
 */
function dataMY($val, $sep = '/') {
	$vet = explode($sep, $val);
	return $vet[2].'-'.$vet[1].'-'.$vet[0];
}

/**
 * Recebe um datetime e retorna somente a data em pt-br
 */
function datetime_date_ptbr($val, $sep = '-') {
	$vet = explode(' ', $val);
	$vet = explode($sep, $vet[0]);
	return $vet[2].'/'.$vet[1].'/'.$vet[0];
}

/**
 * Recebe um datetime e retorna somente a hora em pt-br
 */
function datetime_time_ptbr($val, $sep = ':') {
	$vet = explode(' ', $val);
	$vet = explode($sep, $vet[1]);
	return $vet[0].':'.$vet[1];
}

/**
 * Recebe a data e a hora e converte para datetime de mysql
 */
function datetime_mysql($dat, $hor, $sep = '/') {
	$vet = explode($sep, $dat);
	$dat = $vet[2].'-'.$vet[1].'-'.$vet[0];
	return $dat.' '.$hor;
}

/**
 * Recebe um datetime e retorna somente a hora em pt-br
 */
function datetime_time_full_ptbr($val, $sep = ':') {
	$vet = explode(' ', $val);
	$vet = explode($sep, $vet[1]);
	return $vet[0].':'.$vet[1].':'.$vet[2];
}

/**
 * Função que retorna data no formato ex: 01 de mar de 2020
 */
function diaMesAnoExtenso($data){	
	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');
	// $data = dataMY($data);
	$date = strftime('%d de %b de %Y', strtotime($data));
	return ucfirst($date);
}

/**
 * Função que limita caracteres de uma string
 */
function limitaCaracteres($txt, $limite) {
	$txt = strip_tags($txt);
	$trecho = substr($txt, 0, $limite);
	if (strlen($trecho) < strlen($txt)) {
		$trecho = substr($trecho, 0, strrpos($trecho, " ") + 1) . "...";
	}
	return $trecho;
}

/**
 * Recebe a data e valida
 */
function validaData($data) {	
	$t=explode("/",$data);

	if ($t=="") {return false;}

	$dia=$t[0];
	$mes=$t[1];
	$ano=$t[2];

	if (!is_numeric($dia) || !is_numeric($mes) || !is_numeric($ano)){return false;}	
	if ($dia<1 || $dia>31) {return false;}	
	if ($mes<1 || $mes>12) {return false;}
	if ($ano<1910 || $ano>2200){return false;}

	return true;
}

/**
 * Recebe a hora e valida
 */
function validaHora($hora) {
	$t=explode(":",$hora);
	if ($t=="")
		return false;
	$h=$t[0];
	$m=$t[1];
	
	if (!is_numeric($h) || !is_numeric($m) )
		return false;
		
	if ($h<0 || $h>24)
		return false;
	if ($m<0 || $m>59)
		return false;
		
	return true;
}

/**
 * Recebe o email e valida
 */
function validaEmail($email) {
	$regex = '/([a-z0-9_.-]+)'. # name
	'@'. # at
	'([a-z0-9.-]+){2,255}'. # domain & possibly subdomains
	'.'. # period
	'([a-z]+){2,10}/i'; # domain extension 
	
	if($email == '') 
		return false;
	else
		$eregi = preg_replace($regex, '', $email);
	return empty($eregi) ? true : false;
}

/**
 * Recebe a data mm/yyyy e valida
 */
function validaDataMesAno($data) {	
	$t=explode("/",$data);

	if ($t=="") {return false;}

	// $dia=$t[0];
	$mes=$t[0];
	$ano=$t[1];

	// if (!is_numeric($dia) || !is_numeric($mes) || !is_numeric($ano)){return false;}	
	if (!is_numeric($mes) || !is_numeric($ano)){return false;}	
	// if ($dia<1 || $dia>31) {return false;}	
	if ($mes<1 || $mes>12) {return false;}
	if ($ano<1910 || $ano>2200){return false;}

	return true;
}

/**
 * Máscara de formatação cpf/ cnpj
 */
function MascaraCpfCnpj($mask,$str){
    $str = str_replace(" ","",$str);
    for($i=0;$i<strlen($str);$i++){
        $mask[strpos($mask,"#")] = $str[$i];
    }
    return $mask;
}

/**
 * Limpa formatação cpf/ cnpj
 */
function LimpaCpfCnpj($valor){
	$valor = trim($valor);
	$valor = str_replace(".", "", $valor);
	$valor = str_replace(",", "", $valor);
	$valor = str_replace("-", "", $valor);
	$valor = str_replace("/", "", $valor);
	return $valor;
}

/**
* Função para gerar número aleatório
*/
function geraNumero($tamanho, $numeros){
	$nu = "123456789"; // $nu contem os números 

    if ($numeros){
        // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
        $senha = str_shuffle($nu);
    }
  
    // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
    return substr(str_shuffle($senha),0,$tamanho);
}

/**
* Função para gerar token aleatório
*/
function geraToken(){
    return substr(md5(uniqid(rand(), true)),0,10);
}

/**
 * Função p salvar log's do sistema
 */
function logs($x) {
 	// include('./inc/inc.configdb.php');

 	$user_type = $_SESSION['app_user_type']; 
	$user_id   = $_SESSION['app_user_id']; 
	$ip        = $_SERVER['REMOTE_ADDR']; 
	$data_hora = date('Y-m-d H:i:s'); 
 
	$sql99 = "INSERT INTO app_logs (user_type, user_id, data_hora, ip, mnsg) VALUES ('$user_type', '$user_id', '$data_hora', '$ip', '$x')";
 	$dba->query($sql99);
 
	// if (mysqli_query($this->conn, $sql)or die(mysqli_error())){ 
	// 	return true;  
	// } else {
	// 	return false; 
	// }
}


/**
 * Função que retorna IP 
 */
function getIp() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/**
 *
 * Função para upload de imagens
 *
 */
function upload($arquivo, $destino, $lar, $alt)
{
	$quality = 100; //qualidade (de 0 a 100)
	$wmax = $lar; //largura máxima
	$hmax = $alt; //altura máxima
	$source = imabejobsreatefromjpeg($arquivo['tmp_name']);
	$orig_w = imagesx($source);
	$orig_h = imagesy($source);
		
	if ($orig_w>$wmax || $orig_h>$hmax){
	   $thumb_w = $wmax;
	   $thumb_h = $hmax;
	   if ($thumb_w/$orig_w*$orig_h > $thumb_h) {
		   $thumb_w = round($thumb_h*$orig_w/$orig_h);
	   } else {
		   $thumb_h = round($thumb_w*$orig_h/$orig_w);
	   }
	} 
	else {
	   $thumb_w = $orig_w;
	   $thumb_h = $orig_h;
	}
		
	$thumb = imabejobsreatetruecolor($thumb_w,$thumb_h);
	imabejobsopyresampled($thumb,$source,0,0,0,0,$thumb_w,$thumb_h,$orig_w,$orig_h);
		
	if (imagejpeg($thumb, $destino, $quality)){
		return true;
		exit;
	} else {
		return false;
		exit;
	}
		
	imagedestroy($thumb);
}

/**
 * Função que gera GUID 
 */
function createGUID() { 
    
    // Create a token
    $token      = $_SERVER['HTTP_HOST'];
    $token     .= $_SERVER['REQUEST_URI'];
    $token     .= uniqid(rand(), true);
    
    // GUID is 128-bit hex
    $hash        = strtoupper(md5($token));
    
    // Create formatted GUID
    $guid        = '';
    
    // GUID format is XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX for readability    
    $guid .= substr($hash,  0,  8) . 
         '-' .
         substr($hash,  8,  4) .
         '-' .
         substr($hash, 12,  4) .
         '-' .
         substr($hash, 16,  4) .
         '-' .
         substr($hash, 20, 12);
            
    return $guid;
}

/**
 * Função que valida senha numérica de 6 digitos
 */
function validaSenha($senha) {
	if (empty($senha)) { // Verifica se variável é vazia
		return false;
	}

	if (strlen($senha) < 6) { // Verifica se quantidade caracteres da variável é diferente de 6
		return false;
	}

	// if (strlen($senha) != 6) { // Verifica se quantidade caracteres da variável é diferente de 6
	// 	return false;
	// }

	// if (!is_numeric($senha)) { // Verifica se senha não é númerica
	// 	return false;
	// }

	// if ($senha == '123456' || $senha == '654321' || $senha == '456789' || $senha == '987654') { // Verifica se senha é sequência numérica 
	// 	return false;
	// }

	// if($senha == '000000' || $senha == '111111' || $senha == '222222' || $senha == '333333' || $senha == '444444' || $senha == '555555' || $senha == '666666' || $senha == '777777' || $senha == '888888' || $senha == '999999') { // Verifica se caracteres são iguais
	// 	return false;
	// }

	return true;
}

/**
 * Função que formata string para utilização como URL
 */
setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
function urlMaker($str, $replace=array(), $delimiter='-') {
    if( !empty($replace) ) {
        $str = str_replace((array)$replace, ' ', $str);
    }
 
    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
 
    return $clean;
}

/**
 * Função que envia email de boas vindas - novos usuários
 */
function enviaEmailNovoCadastro($email, $nome) {
	include('phpmailer/PHPMailerAutoload.php');

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
	//$mail->addAddress('joe@example.net', 'Joe User');   // Add a recipient
	$mail->addAddress($email);                			  // Name is optional
	// $mail->addAddress('roberto@dedstudio.com.br');
	//$mail->addReplyTo('site@buffon.com.br');
	//$mail->addCC('cc@example.com');
	//$mail->addBCC('anderson@dedstudio.com.br');

	//$mail->addAttachment('/var/tmp/file.tar.gz');        // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');   // Optional name
	$mail->isHTML(true);
	$mail->CharSet = 'UTF-8';

	$mail->Subject = '[bejobs APP] Bem-vindo ao bejobs APP';

	$body =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0"/><title></title><style>/* ----------- *//* -- Reset -- *//* ----------- */body{margin: 0;padding: 0;mso-padding-alt: 0;mso-margin-top-alt: 0;width: 100% !important;height: 100% !important;mso-margin-bottom-alt: 0;/*background-color: #f0f0f0;*/}body, table, td, p, a, li, blockquote{-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;}table{border-spacing: 0;}table, td{mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;}img, a img{border: 0;outline: none;text-decoration: none;}img{-ms-interpolation-mode: bicubic;}p, h1, h2, h3, h4, h5, h6{margin: 0;padding: 0;}.ReadMsgBody{width: 100%;}.ExternalClass{width: 100%;}.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height: 100%;}#outlook a{padding: 0;}img{max-width: 100%;height: auto;}/* ---------------- *//* -- Responsive -- *//* ---------------- */@media only screen and (max-width: 620px){#foxeslab-email .table1{width: 91% !important;}#foxeslab-email .table1-2{width: 98% !important;}#foxeslab-email .table1-3{width: 98% !important;}#foxeslab-email .table1-4{width: 98% !important;}#foxeslab-email .tablet_no_float{clear: both;width: 100% !important;margin: 0 auto !important;text-align: center !important;}#foxeslab-email .tablet_wise_float{clear: both;float: none !important;width: auto !important;margin: 0 auto !important;text-align: center !important;}#foxeslab-email .tablet_hide{display: none !important;}#foxeslab-email .image1{width: 100% !important;}#foxeslab-email .image1-290{width: 100% !important;max-width: 290px !important;}.center_content{text-align: center !important;}.center_button{width: 50% !important;margin-left: 25% !important;max-width: 300px !important;}}@media only screen and (max-width: 479px){#foxeslab-email .table1{width: 95% !important;}#foxeslab-email .no_float{clear: both;width: 100% !important;margin: 0 auto !important;text-align: center !important;}#foxeslab-email .wise_float{clear: both;float: none !important;width: auto !important;margin: 0 auto !important;text-align: center !important;}#foxeslab-email .mobile_hide{display: none !important;}}@media (max-width: 480px){.container_400{width: 95%;}}</style></head><body style="padding: 0;margin: 0;" id="foxeslab-email"><table class="table_full editable-bg-color bg_color_eebf2f editable-bg-image" bgcolor="#eebf2f" width="100%" align="center" mc:repeatable="castellab" mc:variant="Header" cellspacing="0" cellpadding="0" border="0"><tr><td><table class="table1 editable-bg-color bg_color_1f3a4e" bgcolor="#1f3a4e" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;"><tr><td height="15"></td></tr><tr><td><table class="table1" width="520" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;"><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="100%" align="center"><a href="https://bejobsapp.com.br/" class="editable-img"><img editable="true" mc:edit="image013" src="https://bejobsapp.com.br/mails/images/logo-text.png" width="150" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="Logo bejobs APP"/></a></td></tr></table></td></tr></table></td></tr><tr><td height="15"></td></tr></table></td></tr><tr><td><table class="table1 editable-bg-color bg_color_ffffff" bgcolor="#ffffff" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;"><tr><td height="25"></td></tr><tr><td><table class="table1" width="520" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;"><tr><td mc:edit="text022" align="left" class="text_color_1f3a4e" style="color: #1f3a4e; font-size: 30px; font-weight: 700; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule: exactly;"><div class="editable-text"><span class="text_container"><multiline>Bem-vindo ao bejobs APP</multiline></span></div></td></tr><tr><td height="40"></td></tr><tr><td mc:edit="text011" align="left" class="text_color_1f3a4e" style="color: #1f3a4e; font-size: 20px; font-weight: 700; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;"><div class="editable-text"><span class="text_container"><multiline>Olá '.$nome.', </multiline></span></div></td></tr><tr><td height="10"></td></tr><tr> <td mc:edit="text024" align="left" class="text_color_1f3a4e" style="color: #1f3a4e; font-size: 14px;line-height: 2; font-weight: 500; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule: exactly;"> <div class="editable-text" style="line-height: 2;"> <span class="text_container"> <multiline> Tchê, estamos muito felizes em te ter aqui conosco. Tua conta foi criada com sucesso e já está pronta para tu poder fazer teus pedidos. </multiline> </span> </div></td></tr><tr><td height="20"></td></tr><tr> <td mc:edit="text013" align="left" class="text_color_1f3a4e" style="color: #1f3a4e; font-size: 14px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; mso-line-height-rule: exactly;"> <div class="editable-text" style="line-height: 2;"> <span class="text_container"> <multiline> Vamos estar sempre cultivando nossos princípios: praticidade, agilidade e qualidade. Com a certeza de uma entrega diferenciada, para tua segurança e conforto. ⠀ </multiline> </span> </div></td></tr><tr><td height="30"></td></tr><tr><td align="center"><table class="button_bg_color_eebf2f bg_color_eebf2f" bgcolor="#eebf2f" width="225" height="50" align="left" border="0" cellpadding="0" cellspacing="0" style="background-color:#eebf2f; border-radius:3px; height: 50px;"><tr><td mc:edit="text028" align="center" valign="middle" style="color: #1f3a4e; font-size: 16px; font-weight: 600; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule: exactly;" class="text_color_1f3a4e"><div class="editable-text"><span class="text_container"><multiline><a href="https://bejobsapp.com.br/" style="text-decoration: none; color: #1f3a4e;">Acesse sua conta</a></multiline></span></div></td></tr></table></td></tr><tr><td height="30"></td></tr><tr><td mc:edit="text030" align="left" class="text_color_1f3a4e" style="color: #1f3a4e; font-size: 14px;line-height: 2; font-weight: 500; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule: exactly;"><div class="editable-text" style="line-height: 2;"><span class="text_container"><multiline>E não esqueça, a melhor cerveja é a bejobs!</multiline></span></div></td></tr><tr><td height="30"></td></tr><tr><td mc:edit="text029" align="left" class="text_color_1f3a4e" style="color: #1f3a4e; font-size: 14px;line-height: 2; font-weight: 500; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule: exactly;"><div class="editable-text" style="line-height: 2;"><span class="text_container"><multiline>Obrigado,</multiline></span></div></td></tr><tr><td height="5"></td></tr><tr><td mc:edit="text030" align="left" class="text_color_1f3a4e" style="color: #1f3a4e; font-size: 14px;line-height: 2; font-weight: 500; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule: exactly;"><div class="editable-text" style="line-height: 2;"><span class="text_container"><multiline>Equipe bejobs APP</multiline></span></div></td></tr><tr><td height="20"></td></tr><tr><td><a href="https://play.google.com/store/apps/details?id=com.app.bejobs" class="editable-img" style="text-decoration: none;"><img src="https://bejobsapp.com.br/mails/images/google-play-badge.png" width="143" style="display:inline-block; line-height:0; font-size:0; border:0;" border="0" alt=""/></a><a href="https://apps.apple.com/us/app/gelada-em-casa-app/id1541225001" class="editable-img" style="text-decoration: none;"><img src="https://bejobsapp.com.br/mails/images/app-store-badge.png" width="125" style="display:inline-block; line-height:0; font-size:0; border:0;" border="0" alt=""/></a></td></tr><tr><td height="20"></td></tr><tr><td mc:edit="text031" align="left" class="text_color_1f3a4e" style="color: #1f3a4e; font-size: 14px;line-height: 2; font-weight: 500; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule: exactly;"><div class="editable-text" style="line-height: 2;"><span class="text_container"><multiline>Dúvidas? Entre em contato pelo e-mail <a href="mailto:contato@bejobsapp.com.br" class="text_color_1f3a4e" style="color:#1f3a4e; text-decoration: none; font-weight: bold;">contato@bejobsapp.com.br</a> ou acesse nossa página de <a href="https://bejobsapp.com.br/app/#/faq" class="text_color_1f3a4e" style="color:#1f3a4e; text-decoration: none; font-weight: bold;">Perguntas Frequentes</a></multiline></span></div></td></tr></table></td></tr><tr><td height="60"></td></tr></table></td></tr><tr><td><table class="table1" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;"><tr><td height="40"></td></tr><tr><td><table class="table1-2" width="350" align="left" border="0" cellspacing="0" cellpadding="0"><tr><td mc:edit="text032" align="left" class="center_content text_color_1f3a4e" style="color: #1f3a4e; font-size: 14px; line-height: 2; font-weight: 400; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule: exactly;"><div class="editable-text" style="line-height: 2;"><span class="text_container"><multiline>Copyright © '.date('Y').' bejobs APP, Todos os direitos reservados. <a href="https://bejobsapp.com.br" style="color: #1f3a4e;text-decoration: none; font-weight: bold;"> bejobsapp.com.br</a></multiline></span></div></td></tr><tr><td height="20"></td></tr><tr><td mc:edit="text033" align="left" class="center_content" style="font-size: 14px;font-weight: 400; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule: exactly;"><div class="editable-text"><span class="text_container"><multiline><a href="https://bejobsapp.com.br/app/#/faq" class="text_color_1f3a4e" style="color:#1f3a4e; text-decoration: none;">Perguntas Frequentes</a></multiline></span></div></td></tr><!-- <tr><td height="10"></td></tr><tr><td mc:edit="text034" align="left" class="center_content" style="font-size: 14px;font-weight: 400; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule: exactly;"><div class="editable-text"><span class="text_container"><multiline><a href="#" class="text_color_1f3a4e" style="color:#1f3a4e; text-decoration: none; display: block;">Cancelar assinatura</a></multiline></span></div></td></tr>--><tr><td height="30"></td></tr></table><table class="tablet_hide" width="50" align="left" border="0" cellspacing="0" cellpadding="0"><tr><td height="1"></td></tr></table><table class="table1-2" width="200" align="right" border="0" cellspacing="0" cellpadding="0"><tr><td mc:edit="text034" align="center" class="center_content" class="text_color_1f3a4e" style="color: #1f3a4e; font-size: 14px;font-weight: 400; font-family: Arial, Helvetica, sans-serif; mso-line-height-rule: exactly; line-height: 2;"><div class="editable-text"><span class="text_container"><multiline>Te aprochega vivente e nos acompanha nas Redes Sociais:</multiline></span></div></td></tr><tr><td height="10"></td></tr><tr><td><table width="120" class="center_content" style="margin: 0 auto;"><tr><td align="center" width="35"><a href="https://www.facebook.com/bejobsapp" target="_blank" style="border-style: none !important; display: inline-block;; border: 0 !important;" class="editable-img"><img editable="true" mc:edit="image016" src="https://bejobsapp.com.br/mails/images/icon-fb.png" width="35" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt=""/></a></td><td width="15"></td><td align="center" width="35"><a href="https://twitter.com/bejobsapp/" target="_blank" style="border-style: none !important; display: inline-block; border: 0 !important;" class="editable-img"><img editable="true" mc:edit="image017" src="https://bejobsapp.com.br/mails/images/icon-twitter.png" width="35" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt=""/></a></td><td width="15"></td><td align="center" width="35"><a href="https://www.instagram.com/bejobsapp/" target="_blank" style="border-style: none !important; display: inline-block;; border: 0 !important;" class="editable-img"><img editable="true" mc:edit="image018" src="https://bejobsapp.com.br/mails/images/icon-ig.png" width="35" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt=""/></a></td></tr></table></td></tr><tr><td height="30"></td></tr></table></td></tr><tr><td height="70"></td></tr></table></td></tr></table></body>';

	$mail->MsgHTML($body);
	$mail->send();

	// if(!$mail->send()) {
	//     echo 'Message could not be sent.';
	//     echo 'Mailer Error: ' . $mail->ErrorInfo;
	// } else {
	//     echo "success";
	// }
	return true;
}

?>