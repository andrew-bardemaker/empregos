<?php
include('./inc/inc.verificasession.php');
include('./inc/inc.configdb.php');
include('./inc/class.TemplatePower.php');
include('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html'); 
$tpl->assignInclude('content', './tpl/sms-analytics.html');
$tpl->prepare();

include('./menu.php'); //monta o menu de acordo com o usuário
include('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$id_sms = $_GET['id'];

	$sql2 = "SELECT * FROM bejobs_sms WHERE id = $id_sms";
	$query2 = $dba->query($sql2);
	$qntd2 = $dba->rows($query2);
	if ($qntd2 > 0) {
		$vet2 = $dba->fetch($query2);
		$tpl->assign('id_sms', $vet2['id']);
		$tpl->assign('titulo', stripslashes($vet2['titulo']));
		$tpl->assign('texto', stripslashes($vet2['texto']));
		$tpl->assign('data_registro', datetime_date_ptbr($vet2['data_registro']));
		$tpl->assign('hora_registro', datetime_time_full_ptbr($vet2['data_registro']));

		$vet2['usuarios_proclube'] == 1 ? $tpl->assign('usuarios_proclube', 'checked="checked"') : $tpl->assign('usuarios_proclube', '');
		// $vet2['grupos_economicos'] == 1 ? $tpl->assign('grupos_economicos', 'checked="checked"') : $tpl->assign('grupos_economicos', '');
		// $vet2['lojas'] == 1 ? $tpl->assign('lojas', 'checked="checked"') : $tpl->assign('lojas', '');	
		$vet2['grupos_usuarios'] == 1 ? $tpl->assign('grupos_usuarios', 'checked="checked"') : $tpl->assign('grupos_usuarios', '');
		$vet2['usuarios_individual'] == 1 ? $tpl->assign('usuarios_individual', 'checked="checked"') : $tpl->assign('usuarios_individual', '');
		// $vet2['usuarios_reputacao'] == 1 ? $tpl->assign('usuarios_reputacao', 'checked="checked"') : $tpl->assign('usuarios_reputacao', '');

		$sql   = "SELECT * FROM bejobs_sms_usuarios WHERE id_sms = $id_sms";
		$query = $dba->query($sql);
		$qntd  = $dba->rows($query);
		if ($qntd > 0) {
		    for ($i=0; $i<$qntd; $i++) {
		        $vet = $dba->fetch($query);
		        $tpl->newBlock('usuarios');
		        $tpl->assign('id', $vet['id']);
		        $tpl->assign('status_requisicao', $vet['status_requisicao']);
		        $tpl->assign('telefone', $vet['telefone']);

		        $id_sms_usuario = $vet['id'];
		        $id_usuario     = $vet['id_usuarios'];

	            $sql5 = "SELECT nome, cpf, telefone_celular FROM bejobs_usuarios WHERE id = $id_usuario";
	            $query5 = $dba->query($sql5);
	            $vet5 = $dba->fetch($query5);
	            $tpl->assign('nome', $vet5[0]);
	            $tpl->assign('cpf_cnpj', $vet5[1]);

		        $url_api  = "https://www.iagentesms.com.br/webservices/http.php?metodo=consulta&usuario=administrativo@bejobsapp.com.br&senha=Geladaapp123&codigosms=$id_sms_usuario"; 
                $api_http = file_get_contents($url_api);
                $tpl->assign('status_envio', $api_http);
		    }
		}

	} else {
		header("Location: ./sms");
	}

} else {
	header("Location: ./sms");
}
 
$tpl->printToScreen();
?>