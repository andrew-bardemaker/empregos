<?php
include('../inc/inc.configdb.php');

$data = array();

if (isset($_POST['searchTerm'])) {
	$search = addslashes($_POST['searchTerm']);   

	// $sql   = "SELECT * FROM proclube_lojas WHERE nome_fantasia LIKE '%".$search."%' || razao_social LIKE '%".$search."%' || codigo_cliente LIKE '%".$search."%'";
	$sql   = "SELECT * FROM bejobs_grupos WHERE titulo LIKE '%".$search."%' ORDER BY titulo";
	$query = $dba->query($sql);
	$qntd  = $dba->rows($query);
	if ($qntd > 0) {
		for ($i=0; $i<$qntd; $i++) {
			$vet = $dba->fetch($query);
			$data[] = array("id" => $vet['id'], "text" => $vet['titulo']);
		}
	}
}

echo json_encode($data);

?>