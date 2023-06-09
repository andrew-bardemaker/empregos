<?php
include('../inc/inc.configdb.php');

$data = array();

if (isset($_POST['searchTerm'])) {
	$search = addslashes($_POST['searchTerm']);   

	$sql   = "SELECT * FROM bejobs_usuarios WHERE nome LIKE '%".$search."%' || cpf LIKE '%".$search."%'";
	$query = $dba->query($sql);
	$qntd  = $dba->rows($query);
	if ($qntd > 0) {
		for ($i=0; $i<$qntd; $i++) {
			$vet = $dba->fetch($query);
			$data[] = array("id" => $vet['id'], "text" => $vet['nome']." - ".$vet['cpf']);
		}
	}
}

echo json_encode($data);

?>