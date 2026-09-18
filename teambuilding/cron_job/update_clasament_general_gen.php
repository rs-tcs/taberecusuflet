<?php
ob_start();



include("../setari/Database.php");
// conectare la baza de date
$db = new Database();


// stabilim variabila de timp
$Y = date('Y');
	
	
// se extrag tabelele din clasament general ce trebuiesc updatate
$competitii = array();
$genul = array();
$select = "SELECT `numeClasament`,`genul` FROM `clasament_general` WHERE anCompetitional = '".$Y."' AND `genul` <> 'general'";
$result = $db->execute($select);

if($db->getCount($result) > 0){
	
	while($row = $db->getAssoc($result)){
		
		// se salveaza rezultatul intr-un array
		array_push($competitii, $row['numeClasament']);
		array_push($genul, $row['genul']);
	}
	
				
	
	foreach($competitii as $competitie){
		
		// se sterg toate datele din tabel pentru a reintroduce informatiile actualizate
		$checkTable = "SHOW TABLES LIKE '".$competitie."'";
							
		if($db->execute($checkTable)){
								
			$truncate = "TRUNCATE TABLE `".$competitie."`";
			$db->execute($truncate);
													
		}		
				
	}	// sfarsit foreach competitie	
	
				
	
	
	foreach($genul as $gen){
		
		// sunt extrase toate informatiile pentru fiecare gen in parte
		$select = "SELECT * FROM `General$Y` WHERE `sex` = '{$gen}' 
		ORDER BY `rezultat` desc";
		
		$result = $db->execute($select);	
				
				if($result){
					$i = 0;
					while($row = $db->getAssoc($result)){
						// se salveaza toate informatiile din tabelul clasament general intr-un array multidimensional	
							$i++;	
							$concurent[] = array(
								'id' => $i,
								'locul' => $row['locul'],
								'cod_unic' => $row['cod_unic'],
								'numele' => $row['numele'],
								'prenumele' => $row['prenumele'],
								'sex' => $row['sex'],
								'categoria' => $row['categoria'],
								'rezultat' => $row['rezultat'],
								'competitii' => $row['competitii'],
								'participari' => $row['participari'],
								'puncte_bonus' => $row['puncte_bonus'],
								'bonus' => $row['bonus']
							);
							
					} // sfarsit while	
		
				} // sfarsit if
				
				
				// se desface array-ul continator cu datele stocate anterior	
				foreach($concurent as $array){												
				
					$campuri = '';
					$valori = '';
					foreach($array as $camp=>$valoare){
						
						$campuri .= $camp . ", ";
						$valori .= (is_numeric($valoare)) ? $valoare . " , " : "'".$valoare."', ";															
						
					}
					
					$campuri = substr($campuri,0,-2);
					$valori = substr($valori,0,-2);
					// se reintroduc datele in tabel reordonat in functie de punctajul obtinut
					$insert = "INSERT INTO `General$Y{$gen}` (".$campuri.") VALUES (".$valori.")";
						$select = "SET NAMES 'utf8'";
						$db->execute($select);
					$result = $db->execute($insert);
					
			}	// sfarsit foreach concurent									
				
					// array-ul este distrus pentru a nu permite adaugarea exponentiala a elementelor
					unset($concurent);			
		  				
	 }// sfarsit foreach genul
	
}
else
{
	
}

ob_flush();
?>

