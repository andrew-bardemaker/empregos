<?php
include('../inc/inc.configdb.php');

$data = array();

$search = "";
if (isset($_POST['searchTerm'])) {
	$search = addslashes($_POST['searchTerm']);   
}

$sql   = "SELECT * FROM bejobs_produtos WHERE titulo LIKE '%".$search."%' || codigo LIKE '%".$search."%' ORDER BY titulo";
$query = $dba->query($sql);
$qntd  = $dba->rows($query);
if ($qntd > 0) {
	for ($i=0; $i<$qntd; $i++) {
		$vet = $dba->fetch($query);
		$data[] = array("id" => $vet['codigo'], "text" => $vet['codigo']." - ".$vet['titulo']);
	}
}

echo json_encode($data);

?>