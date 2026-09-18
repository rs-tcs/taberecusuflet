<?php

include("../setari/Database.php");
// conectare la baza de date
$db = new Database();

	
	
	
	
	// stabilim variabilele de timp
	$Y = date('Y');
	$ziCurr = date('Y-m-d');
	
	// interogarea care selecteaza doar competitiile care s-au desfasurat
	$query = "SELECT `nume_competitie` 
	FROM `tabel_competitii`
	WHERE `data_competitiei` LIKE '$Y%' 
	AND `data_competitiei` <= '".$ziCurr."' 
	AND `activ` = 0";
	$result = $db->execute($query);

	// cream o matrice in care stocam competitiile valide din interogare
	$competitii = array();

	while($row = $db->getObject($result)){
		
		array_push($competitii, $row['nume_competitie']);
	}
	
		
	
	if(!empty($competitii)){
	// cream o matrice in care vom stoca codurile unice ale tuturor participantilor ce au participat in toate competitiile salvate mai sus
 	$participanti = array();
 
 		foreach($competitii as $competitie){
		$query = "SELECT `cod_unic_participant` FROM `".$competitie."` WHERE `locul` <> ''";	
		
		$result = $db->execute($query);		
			while($row = $db->getObject($result)){
			
				array_push($participanti, $row['cod_unic_participant']);
		
			}
			
		}
		
		// se curata matricea de intrari duplicat si se reindexeaza ordinea obiectelor in matrice
		$participanti = array_values(array_unique($participanti));
	}
	else
	{
		$truncate = "Truncate Table `General$Y`";
		$db->execute($truncate);		
		
	}		
	
		
	
	
	// se creeaza tabelul care va stoca informatiile concurentilor despre punctele castigate la fiecare competitie
	if(!empty($participanti)){
	
 	$tabel = "CREATE TABLE IF NOT EXISTS `General$Y`
	(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`locul` int(5) NOT NULL,
	`cod_unic` char(20) NOT NULL,
	`numele` varchar(50) NOT NULL,
	`prenumele` varchar(50) NOT NULL,
	`sex` varchar(20) NOT NULL,
	`categoria` varchar(50) NOT NULL,
	`rezultat` int(5) NOT NULL,	
	`competitii` text NOT NULL,
	`participari` int(2) NOT NULL,
	`puncte_bonus` int(3) NOT NULL,
	`bonus` int(1) NOT NULL,
	PRIMARY KEY (`id`)	
	) ENGINE=InnoDB DEFAULT CHARSET=utf8";
 	
	$temporary_tbl = $db->execute($tabel);
	
	
	
	// se introduce tabelul creat in tabelul de evidenta `clasament_general`
	 
	 $query = "SELECT `id` FROM `clasament_general` WHERE `numeClasament` = 'General$Y'";
	 $result = $db->execute($query);
	 	if($db->getCount($result) == 0){
		$insert = "INSERT INTO `clasament_general` (`id`,`numeClasament`,`anCompetitional`,`genul`) VALUES ('','General{$Y}','$Y','general')";
			$db->execute($insert);
			
		}
	
	}
	
	
	if(!empty($participanti)){
	// se goleste tabelul in momentul cand se reapeleaza	
	$truncate = "Truncate Table `General$Y`";
	$db->execute($truncate);
				
		// sunt introduse codurile unice in tabel
		foreach($participanti as $participant){
		
		$select = "SELECT `cod_unic` FROM `General$Y` WHERE `cod_unic` = '".$participant."'";
		$resSelect = $db->execute($select);
			if($db->getCount($resSelect) == 0){
	
				$insert = "INSERT INTO `General$Y` (`id`,`cod_unic`)
				 VALUES ('','".$participant."')";			 
				 $result = $db->execute($insert);	
			}
		
		}
	}
	
	
	
	// se updateaza tabelul cu restul informatiilor din tabelul `inscrisi_teamexpert`
			
	$query = "UPDATE `General$Y` LEFT JOIN `inscrisi_teamexpert` ON `General$Y`.cod_unic = `inscrisi_teamexpert`.cod_unic SET 
	`General$Y`.numele = `inscrisi_teamexpert`.nume, `General$Y`.prenumele = `inscrisi_teamexpert`.prenume, `categoria` = `inscrisi_teamexpert`.categorie_varsta, `General$Y`.sex = `inscrisi_teamexpert`.sex WHERE 1";	
	$result = $db->execute($query);		
	
	
	
	
	
	
	
	// se calculeaza automat punctele participantilor la competitii inclusiv suma acestora daca au participat la mai multe competitii
	// daca nu exista completat locul concurentului acestea vor fi omise
	foreach($competitii as $competitie){		
	
		$update = "UPDATE `General$Y` LEFT JOIN `".$competitie."` ON `General$Y`.cod_unic = `".$competitie."`.cod_unic_participant SET  `rezultat` = (`rezultat` + `".$competitie."`.puncte), `competitii` = trim(TRAILING ',' FROM CONCAT_WS(',','".$competitie."',`competitii`)), `participari` = (`participari` + 1) WHERE `cod_unic` = `".$competitie."`.cod_unic_participant AND `".$competitie."`.locul <> ''";
		$db->execute($update);
		
	}
	
	
	
	// se reconstruieste ordinea clasamentului
	
	$select = "SELECT `cod_unic` FROM `General$Y` WHERE 1 ORDER BY `rezultat` desc, `numele` asc";
	$result = $db->execute($select);
	
	$clasGeneral = array();
	if($result){
		while($row = $db->getObject($result)){
			
			array_push($clasGeneral, $row['cod_unic']);
		}	
		
	}
	
	
	// se curata tabelul de intrarile anterioare
	$truncate = "TRUNCATE TABLE `General$Y`";
	$db->execute($truncate);
	
	// se reintroduc informatiile cu noile id-uri
	foreach($clasGeneral as $cod_unic){
		
		$insert = "INSERT INTO `General$Y` (`id`,`cod_unic`)
		VALUES ('','".$cod_unic."')";			 
		$result = $db->execute($insert);			
		
	}
	
	// se updateaza tabelul cu restul informatiilor din tabelul `inscrisi_teamexpert`
			
	$query = "UPDATE `General$Y` LEFT JOIN `inscrisi_teamexpert` ON `General$Y`.cod_unic = `inscrisi_teamexpert`.cod_unic SET 
	`General$Y`.numele = `inscrisi_teamexpert`.nume, `General$Y`.prenumele = `inscrisi_teamexpert`.prenume, `categoria` = `inscrisi_teamexpert`.categorie_varsta, `General$Y`.sex = `inscrisi_teamexpert`.sex, `General$Y`.locul = `General$Y`.id WHERE 1";	
	$result = $db->execute($query);		
	
	
	// se recalculeaza automat punctele participantilor la competitii inclusiv suma acestora daca au participat la mai multe competitii
	// daca nu exista completat locul concurentului acestea vor fi omise
	foreach($competitii as $competitie){		
	
		$update = "UPDATE `General$Y` LEFT JOIN `".$competitie."` ON `General$Y`.cod_unic = `".$competitie."`.cod_unic_participant SET  `rezultat` = (`rezultat` + `".$competitie."`.puncte), `competitii` = trim(TRAILING ',' FROM CONCAT_WS(',','".$competitie."',`competitii`)), `participari` = (`participari` + 1) WHERE `cod_unic` = `".$competitie."`.cod_unic_participant AND `".$competitie."`.locul <> ''";
		$db->execute($update);
			
	}
	
	// se creeaza tabelul general feminin care va stoca informatiile concurentilor despre punctele castigate la fiecare competitie
	if(!empty($participanti)){
	
 	$tabel = "CREATE TABLE IF NOT EXISTS `General{$Y}feminin`
	(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`locul` int(5) NOT NULL,
	`cod_unic` char(20) NOT NULL,
	`numele` varchar(50) NOT NULL,
	`prenumele` varchar(50) NOT NULL,
	`sex` varchar(20) NOT NULL,
	`categoria` varchar(50) NOT NULL,
	`rezultat` int(5) NOT NULL,	
	`competitii` text NOT NULL,
	`participari` int(2) NOT NULL,
	`puncte_bonus` int(3) NOT NULL,
	`bonus` int(1) NOT NULL,
	PRIMARY KEY (`id`)	
	) ENGINE=InnoDB DEFAULT CHARSET=utf8";
 	
	$temporary_tbl = $db->execute($tabel);
	
	
	
	// se introduce tabelul creat in tabelul de evidenta `clasament_general`
	 
	 $query = "SELECT `id` FROM `clasament_general` WHERE `numeClasament` = 'General{$Y}feminin'";
	 $result = $db->execute($query);
	 	if($db->getCount($result) == 0){
			$insert = "INSERT INTO `clasament_general` (`id`,`numeClasament`,`anCompetitional`,`genul`) VALUES ('','General{$Y}feminin','$Y','feminin')";
			$db->execute($insert);
			
			}
			
	}
	
	
	// se creeaza tabelul general masculin care va stoca informatiile concurentilor despre punctele castigate la fiecare competitie
	if(!empty($participanti)){
	
 	$tabel = "CREATE TABLE IF NOT EXISTS `General{$Y}masculin`
	(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`locul` int(5) NOT NULL,
	`cod_unic` char(20) NOT NULL,
	`numele` varchar(50) NOT NULL,
	`prenumele` varchar(50) NOT NULL,
	`sex` varchar(20) NOT NULL,
	`categoria` varchar(50) NOT NULL,
	`rezultat` int(5) NOT NULL,	
	`competitii` text NOT NULL,
	`participari` int(2) NOT NULL,
	`puncte_bonus` int(3) NOT NULL,
	`bonus` int(1) NOT NULL,
	PRIMARY KEY (`id`)	
	) ENGINE=InnoDB DEFAULT CHARSET=utf8";
 	
	$temporary_tbl = $db->execute($tabel);
	
	
	 // se introduce tabelul creat in tabelul de evidenta `clasament_general`
	 
	 $query = "SELECT `id` FROM `clasament_general` WHERE `numeClasament` = 'General{$Y}masculin'";
	 $result = $db->execute($query);
	 	if($db->getCount($result) == 0){
			$insert = "INSERT INTO `clasament_general` (`id`,`numeClasament`,`anCompetitional`,`genul`) VALUES ('','General{$Y}masculin','$Y','masculin')";
			$db->execute($insert);
			
			}
			
	}
			

?>