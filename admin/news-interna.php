<?php
require_once('./inc/class.TemplatePower.php');
require_once('./inc/inc.lib.php');
require_once('./inc/inc.configdb.php');
require_once('./inc/youtube-vimeo-embed-urls.php');

$tpl = new TemplatePower('./tpl/default.html');
$tpl->assignInclude('content', './tpl/news-interna.html');
$tpl->prepare();

include('./inc/inc.whatsapp.php');
include_once('menu.php');

$tpl->gotoBlock('_ROOT');
$tpl->assign('description', 'Tapetes, Móveis e Objetos - Porto Alegre, RS');	
$tpl->assign('image', 'http://'.$_SERVER['SERVER_NAME'].'');
$tpl->assign('url', 'http://'.$_SERVER['SERVER_NAME'].''.$_SERVER ['REQUEST_URI'].'');

$idn = $_GET['id'];

if (is_numeric($idn)) { // verifica se váriavel é numerica - sqlinjection
	$sql = "SELECT * FROM bejobs_news WHERE id = '$idn'";
	$query = $dba->query($sql);
	$qntd = $dba->rows($query);
	if ($qntd > 0) {
		$vet = $dba->fetch($query);
		$tpl->assign('id', $vet['id']);
		$tpl->assign('titulo', stripslashes($vet['titulo']));
		$tpl->assign('data', dataBR($vet['data']));
		$tpl->assign('texto', stripslashes($vet['texto']));
		$tpl->assign('tags', stripslashes($vet['tags']));
		$link = stripslashes($vet['link']);
		$link_video = parseVideos($link) ;
		if (!empty($vet['link'])) {
            $tpl->assign('video', '<div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="'.$link_video['0']['url'].'"
              allowfullscreen></iframe>
          </div>');
		}


        // Twitter Share
        $tpl->assign('title', $vet['titulo'].'| Império Persa');	
		$tpl->assign('twitter', "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>");
	} else {
		header('location: ./');
	}
} else {
	header('location: ./');
}

$tpl->printToScreen();