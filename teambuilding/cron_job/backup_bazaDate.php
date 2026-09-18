<?php
include('../setari/Database.php');
$db = new Database();

$cale = "../salvari/bazaDate/";

	if(!is_dir($cale)){
		
		mkdir($cale);
			
	}

	$bazaDate = $cale."inscrisi_teamexpert.csv"; 
	$fisier = fopen($bazaDate, 'w');
	
	// se introduc numele coloanelor tabelului
	$query = "SHOW COLUMNS FROM `inscrisi_teamexpert` WHERE 1";
	$result = $db->execute($query);
	for($i=0; $i < $db->getCount($result); $i++){
		$cols[$i] = $db->getObject($result);
		$coloana[$i] = $cols[$i]['Field'];
	}
	fputcsv($fisier, $coloana);
	
	// se introduc informatiile coloanelor
	$query = "SELECT * FROM `inscrisi_teamexpert`";
	$result = $db->execute($query);
		if($db->getCount($result) >= 1){
			for($i=0; $i < $db->getCount($result); $i++){
				$infoColoane[$i] = $db->getAssoc($result);
			}
			
			foreach($infoColoane as $linie){
				fputcsv($fisier, $linie);
			}
		}
	
	fclose($fisier);
	
?>