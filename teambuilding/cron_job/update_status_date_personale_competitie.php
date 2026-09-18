<?php

include("../setari/Database.php");
$db = new Database();


	$select = "SELECT `nume_competitie` FROM `tabel_competitii` WHERE `activ` = 1";
	$result = $db->execute($select);
	
	// se creeaza un array cu toate competitiile in curs de desfasurare
	$competitii = array();
	if($db->getCount($result) > 0){
		
		while($row = $db->getAssoc($result)){
			
			array_push($competitii, $row['nume_competitie']);
			}	
		
	}
	
	// procesarea update-ului cu valorile obtinute in array
	
	foreach($competitii as $competitie){
		
		
		// se corecteaza lipsa datelor personale `telefon` si `email` din competitiile care inca nu s-au desfasurat
		
		$query = "UPDATE `".$competitie."` LEFT JOIN `inscrisi_teamexpert` ON `".$competitie."`.cod_unic_participant = `inscrisi_teamexpert`.cod_unic SET `".$competitie."`.telefon = `inscrisi_teamexpert`.telefon, `".$competitie."`.email = `inscrisi_teamexpert`.email WHERE 1 ";
		
		$result = $db->execute($query);	
	}
?>