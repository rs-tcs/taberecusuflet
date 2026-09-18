<?php

include("../setari/Database.php");
$db = new Database();

$anCompetitional = date('Y');

// calea directorului pentru salvarea competitiilor
$caleSalvare = "../salvari/an_competitional_".$anCompetitional;

		// verific daca directorul exista altfel trebuie creat
		if(!is_dir($caleSalvare)){
	
			mkdir($caleSalvare);
		}
		
		// interogarea pentru a afla ce tabele trebuiesc salvate
		$query = "SELECT `nume_competitie` FROM `tabel_competitii` WHERE ";
		$query .= " `data_competitiei` LIKE '".$anCompetitional."%' AND `activ` = 0";
		$result = $db->execute($query);
		
		$flags = array();
		$competitii = array();
		while($row = $db->getObject($result)){
			
			array_push($competitii, $row['nume_competitie']);			
			
		}
			
		
		if(is_array($competitii)){		
			
			foreach($competitii as $competitie){	
						
				$tabel = $caleSalvare."/".$competitie.".csv";
			
						if(!file_exists($tabel)){								
				 
								$fisier = fopen($tabel,'w');
				
								// se scriu numele coloanelor tabelelor
								$query = "SHOW COLUMNS FROM `".$competitie."`";
								$result = $db->execute($query);
									for($i=0; $i < $db->getCount($result); $i++){
											$coloana[$i] = $db->getAssoc($result);
											$campuri[$i] = $coloana[$i]['Field'];
						
									}
					
								fputcsv($fisier, $campuri);
						
				
				
				
								// se scriu informatiile coloanelor	
								$result = $db->execute("SELECT * FROM `".$competitie."` WHERE 1");			
									if($db->getCount($result) > 0){
											for($i=0; $i < $db->getCount($result); $i++){
													$cellinfo[$i] = $db->getAssoc($result);
													fputcsv($fisier, $cellinfo[$i]);	
											}						
						
									}
				
		   						fclose($fisier);
						}
						else
						{
							// fisierul exista, nu trebuie facut backup
							
						}
			
			
				}
		
		}
		
?>