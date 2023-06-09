<?php
require_once('./inc/inc.verificasession.php');
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/inc.lib.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/produtos-editar.html');
$tpl->prepare();

// Verificação de acesso página e permissões
$id_pagina_permissao = 9;
$act_permissao_editar = true;
require_once('./inc/inc.verificaacessopermissao.php');

include_once('./menu.php'); //monta o menu de acordo com o usuário
include_once('./inc/inc.mensagens.php'); //mensagens e alerts

$tpl->gotoBlock('_ROOT');

$id = $_GET['id'];
if (!is_numeric($id)) {
	header('Location: ./produtos');
}

$sql = "SELECT * FROM bejobs_produtos WHERE id = $id"; // print_r($sql);
$query = $dba->query($sql);
$qntd = $dba->rows($query);
if ($qntd > 0) {
	$vet = $dba->fetch($query);
	$tpl->assign('id', $vet['id']);
	$tpl->assign('codigo', $vet['codigo']);
	$tpl->assign('titulo', stripslashes($vet['titulo']));
	$tpl->assign('texto', stripslashes($vet['texto']));
	$tpl->assign('preco', moeda($vet['preco']));
	$tpl->assign('preco_promo', moeda($vet['preco_promo']));
	$tpl->assign('tags', $vet['tags']);

	$tpl->assign('codigo_ncm', stripslashes($vet['codigo_ncm']));
    $tpl->assign('cfop', stripslashes($vet['cfop']));

    $icms_origem = $vet['icms_origem'];
	$options_icms_origem1 = array("0", "1", "2", "3", "4", "5", "6", "7");
	$options_icms_origem2 = array("0: nacional", "1: estrangeira (importação direta)", "2: estrangeira (adquirida no mercado interno)", "3: nacional com mais de 40% de conteúdo estrangeiro", "4: nacional produzida através de processos produtivos básicos", "5: nacional com menos de 40% de conteúdo estrangeiro", "6: estrangeira (importação direta) sem produto nacional similar", "7: estrangeira (adquirida no mercado interno) sem produto nacional similar");
	$output_icms_origem = '';
	for( $i=0; $i<count($options_icms_origem1); $i++ ) {
	  $output_icms_origem .= '<option value="'.$options_icms_origem1[$i].'" ' 
	             . ( $icms_origem == $options_icms_origem1[$i] ? 'selected="selected"' : '' ) . '>' 
	             . $options_icms_origem2[$i] 
	             . '</option>';
	}	
	$tpl->assign('icms_origem', $output_icms_origem);

    $icms_situacao_tributaria = $vet['icms_situacao_tributaria'];
	$options_icms_situacao_tributaria1 = array("102", "300", "500", "00", "40", "41", "60");
	$options_icms_situacao_tributaria2 = array("102 - Tributada pelo Simples Nacional sem permissão de crédito", "300 - Imune", "500 - CMS cobrado anteriormente por substituição tributária (substituído) ou por antecipação", "00 - Tributada integralmente", "40 - Isenta", "41 - Não tributada", "60 - ICMS cobrado anteriormente por substituição tributária");
	$output_icms_situacao_tributaria = '';
	for( $i=0; $i<count($options_icms_situacao_tributaria1); $i++ ) {
	  $output_icms_situacao_tributaria .= '<option value="'.$options_icms_situacao_tributaria1[$i].'" ' 
	             . ( $icms_situacao_tributaria == $options_icms_situacao_tributaria1[$i] ? 'selected="selected"' : '' ) . '>' 
	             . $options_icms_situacao_tributaria2[$i] 
	             . '</option>';
	}	
	$tpl->assign('icms_situacao_tributaria', $output_icms_situacao_tributaria);

    $tpl->assign('icms_aliquota', stripslashes($vet['icms_aliquota']));

    // $tpl->assign('icms_base_calculo', stripslashes($vet['icms_base_calculo']));

    $icms_modalidade_base_calculo = $vet['icms_modalidade_base_calculo'];
	$options_icms_modalidade_base_calculo1 = array("0", "1", "2", "3");
	$options_icms_modalidade_base_calculo2 = array("0 - margem de valor agregado (%)", "1 - pauta (valor)", "2 - preço tabelado máximo (valor)", "3 - valor da operação");
	$output_icms_modalidade_base_calculo = '';
	for( $i=0; $i<count($options_icms_modalidade_base_calculo1); $i++ ) {
	  $output_icms_modalidade_base_calculo .= '<option value="'.$options_icms_modalidade_base_calculo1[$i].'" ' 
	             . ( $icms_modalidade_base_calculo == $options_icms_modalidade_base_calculo1[$i] ? 'selected="selected"' : '' ) . '>' 
	             . $options_icms_modalidade_base_calculo2[$i] 
	             . '</option>';
	}	
	$tpl->assign('icms_modalidade_base_calculo', $output_icms_modalidade_base_calculo);

	$id_categoria = $vet['id_categoria'];

	$sql1   = "SELECT id, titulo FROM bejobs_produtos_categorias ORDER BY titulo";
	$query1 = $dba->query($sql1);
	$qntd1  = $dba->rows($query1);
	if ($qntd1 > 0) {
		for ($i=0; $i<$qntd1; $i++) {
			$vet1 = $dba->fetch($query1);
			$tpl->newBlock('categorias');
			$tpl->assign('id', $vet1[0]);
			$tpl->assign('titulo', $vet1[1]);

			if ($vet1[0] == $id_categoria) {
				$tpl->assign('selected', 'selected');
			}
		}
	}

	$id_marca = $vet['id_marca'];

	$sql2   = "SELECT id, titulo FROM bejobs_produtos_marcas ORDER BY titulo";
	$query2 = $dba->query($sql2);
	$qntd2  = $dba->rows($query2);
	if ($qntd2 > 0) {
		for ($i=0; $i<$qntd2; $i++) {
			$vet2 = $dba->fetch($query2);
			$tpl->newBlock('marcas');
			$tpl->assign('id', $vet2[0]);
			$tpl->assign('titulo', $vet2[1]);

			if ($vet2[0] == $id_marca) {
				$tpl->assign('selected', 'selected');
			}
		}
	}

	if (file_exists('../images/produtos/'.$id.'.jpg')) {
		$tpl->newBlock('img');
		$tpl->assign('id', $vet['id']);
	} else {
		$tpl->newBlock('noimg');
	}

	// if (file_exists('../images/produtos/'.$id.'_1.jpg')) {
	// 	$tpl->newBlock('img1');
	// 	$tpl->assign('id', $vet['id']);
	// } else {
	// 	$tpl->newBlock('noimg1');
	// }

	if (file_exists('../images/produtos/'.$id.'_2.jpg')) {
		$tpl->newBlock('img2');
		$tpl->assign('id', $vet['id']);
	} else {
		$tpl->newBlock('noimg2');
	}

	// if (file_exists('../images/produtos/'.$id.'_3.jpg')) {
	// 	$tpl->newBlock('img3');
	// 	$tpl->assign('id', $vet['id']);
	// } else {
	// 	$tpl->newBlock('noimg3');
	// }

	// if (file_exists('../images/produtos/'.$id.'_4.jpg')) {
	// 	$tpl->newBlock('img4');
	// 	$tpl->assign('id', $vet['id']);
	// } else {
	// 	$tpl->newBlock('noimg4');
	// }

} else {
	header("location: ./produtos");
}


//--------------------------
$tpl->printToScreen();
?>