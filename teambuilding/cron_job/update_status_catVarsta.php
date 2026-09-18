<?php

include("../php/functii.php");
include("../setari/Database.php");
$db = new Database();




							$date = date("Y");
							
							$select = "SELECT * FROM `inscrisi_teamexpert` WHERE 1";
							$result = $db->execute($select);
													
							if($result){								
								
								
								$id = "";
								while($row = $db->getObject($result)){
									
									$id[] = $row['id'];
									
									}								
								
								foreach($id as $number){								
									
									$select = "SELECT * FROM `inscrisi_teamexpert` WHERE `id` = {$number}";
									$result = $db->execute($select);
									
									$row = $db->getAssoc($result);
									
									$data_nasterii = $row['data_nasterii'];
									$varsta = $row['varsta'];
									$categorie_varsta = $row['categorie_varsta'];
									
									// se recalculeaza varsta
									$an = str_replace("-","",$data_nasterii);
									$an = substr($an,0,4);
									$varsta = ($date-$an);
									
									// se reintegreaza in categoria de varsta
									$categorie = catVarsta($varsta);
									
									// se procedeaza la update
									$query = "UPDATE `inscrisi_teamexpert` SET `varsta` = '".$varsta."', 
									`categorie_varsta` = '".$categorie."' WHERE id = {$number}";
									
									$result = $db->execute($query);										
										
										
									}
									
								
								}

?>