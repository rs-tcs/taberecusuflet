<?php

include("../setari/Database.php");
$db = new Database();
date_default_timezone_set('Europe/Bucharest');


	$query = "SELECT `nume_competitie` FROM `tabel_competitii` 
			  WHERE `activ` = 1 
			  AND `data_competitiei` <= '".date('Y-m-d')."'" ;
			  
	$result = $db->execute($query);
	
	
	$numeComp = array();	
	while($row = $db->getAssoc($result)){
		
		array_push($numeComp, $row['nume_competitie']);
		
	}
	
	//print_r($numeComp);
		
	foreach($numeComp as $comp){
			
			// fiecare competitie ce respecta criteriul de cautare
			$query = "UPDATE `tabel_competitii` SET `activ` = 0
					  WHERE `nume_competitie` = '".$comp."' 
					  AND `data_competitiei` <= '".date('Y-m-d')."'"; 
					  	
			$result = $db->execute($query);
			
					// se verifica flagul competitiei care corespunde datei curente sau celor anterioare ce sunt active 
				echo	$select =  "SELECT DISTINCT `flag` FROM `tabel_competitii` 
								WHERE `nume_competitie` = '".$comp."' 
								AND `data_competitiei` <= '".date('Y-m-d')."'";
				echo "<br />";
					$result = $db->execute($select);
					$row = $db->getAssoc($result);
					
							
							if($result){
								
							echo	$numberOfComp = "SELECT `id` FROM `tabel_competitii` WHERE `flag` = '".$row['flag']."' AND `activ` = 1";
							echo "<br />";
								$result = $db->execute($numberOfComp);
								$number = $db->getCount($result);
								
								
										if ($number > 1 || $number == 1){
												// mai multe competitii cu acelasi flag sunt active deci clasa competitiei ramane activ
										}
										else if($number == 0)
										{									
												// nicio competitie cu flagul existent nu este activa						
									
												// se updateaza la inactiv competitiile din tabelul nume_competitii pentru 
												// a nu mai fi afisate in pagina inscriere_competitie	
												
									
												$query = "UPDATE `nume_competitii` ";									
												$query .= " SET `competitii_activ` = 0 ";
												$query .= " WHERE `nume_competitie` = '".$row['flag']."'";								
												$result = $db->execute($query);	
										}	
								
							}
							else
							{
								echo "Mai multe intrari";
								
							}
		
			}
			
	
?>