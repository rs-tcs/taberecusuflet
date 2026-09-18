<?php
ob_start();
session_start();
require("includes/Database.php");
require("includes/constante.php");

$db = new Database();
	if(!$_SESSION['username']){
		
		header("Location: index.php");
		}
	else
	{
		$user = $_SESSION['username'];
		
	}
	
	$query = "SELECT `acces` FROM `login` WHERE `uniq_id` = '".$_SESSION['uniq_id']."'";
	$result = $db->execute($query);
	
	$row = mysqli_fetch_array($result);
	
	
		if($row['acces'] <> 1){
			
			header("Location: baza_date.php");
			
			}
			

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Clasament general</title>
<link rel="stylesheet" type="text/css" href="css/tabele_competitii.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script type="text/javascript" src="js/functii.js" ></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

			<div id="page">
        		<div id="text_top">
                		<p>Zona de administrare <b style="color:darkblue">Team</b><b style="color: #F00000">Xpert</b> Race Club</p>
                </div>
                 <?php
                $select = "SELECT `acces` FROM `login` WHERE username = '".$_SESSION['username']."'";
					$acces = $db->execute($select);
					
					$row = mysqli_fetch_array($acces);
										
					$user = $row['acces'];
						if($user == 1){
							$text = "Admin :";
							}
							else
							{
							$text = "User :";
							}           
				
                	?>
                <script type="text/javascript">
                $(document).ready(function(){

  						  //run once
   					 var el=$('#meniu_stanga');
   					 var originalelpos=el.offset().top; // take it where it originally is on the page

   						 //run on scroll
   				  $(window).scroll(function(){
     				   var el = $('#meniu_stanga'); // important! (local)
       				   var elpos = el.offset().top; // take current situation
                       var windowpos = $(window).scrollTop();
                       var finaldestination = windowpos+originalelpos;
                       el.stop().animate({'top':finaldestination},500);
                        });

                });
				</script>
                
                
                <div id="meniu_stanga">
                
                	<table id="lista_meniu">
                    	<tr>
                    		<td style="text-align:center; "><a href="baza_date.php">Home</a></td>
                        </tr>
                    	<tr>
                    		<td><a href="adauga_participant_baza.php">Adaugă participant</a></td>
                        </tr>
                        <tr>
                        	<td><a href="creeaza_newsletter1.php">Creează newsletter</a></td>
                        </tr>
                        <tr>
                    		<td><a href="editeaza_newsletter.php">Editează newsletter</a></td>
                        </tr>
                        <tr>
                        	<td><a href="retrimite_codul_unic.php">Retrimite codul</a></td>
                        </tr>
                        <tr>
                    		<td ><a href="tabele_competitii.php">Tabele competiții</a></td>
                        </tr>
                        <tr>
                        	<td style="background-color:#0F87FF"><a href="clasament_general.php">Clasament general</a></td>
                        </tr>
                        <tr>
						<?php
							if($user <> 1) 
                        	echo "<td style=\"color:white; background-color:red\">Zona admin</td>";
							else
							echo "<td><a href=\"admin_area.php\">Zona admin</a></td>";
						?>
                    	</tr>
                        <tr>
                        	<td style="text-align:center; background-color:#A60000"><a href="logout.php">Logout</a></td>
                    	</tr>
                    </table>
                
                </div>
                
                
                 <div class="main_content">	
                 
                  <div id="admin">
                    <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
					?>
                    
					</div>	
                    <br>
                    <div id="text">
                    <?php echo "Tabel clasament general"; ?>
             	    </div>
              		<br>
                    <script type="text/javascript">
					function confirmPost(){
					
						var agree = confirm("Ești sigur că vrei să ștergi această competiție?");
						
						if(agree){
						
						return true ;
						}
						else
						{
						return false ;
						}
						}
					</script>
                 	
                    	<br />
                        <!-- selectul care sorteaza anii competitionali -->
                        <form method="post" id="selAnComp" action="<?php $_SERVER['PHP_SELF'];?>">
                        	<select id="anComp" onChange="location.href=selAnComp.anComp.options[selectedIndex].value;">
                            		<option value="?">Selectați anul competițional</option>
                            	<?php
									$anCurent = date('Y');
									for($i=2014; $i <= $anCurent; $i++){
									?>
                                   <option value="?anComp=<?php echo $i ;?><?php if(isset($_GET['pagina'])){echo "&pagina=".$_GET['pagina'];}else{} ?>" <?php echo (isset($_GET['anComp']) == $i)? "selected" : "";?>><?php echo $i; ?></option>
                                    <?php	
									}
								?> 
                        	</select>
                        </form>
                        <br />
                        
                        
                        <?php
						// afisarea butoanelor ce opresc sau pornesc generarea clasamentului
						$file = '../cron_job/pagini_inactive/genereaza_clasament_general_stop.php';
						$exist = file_exists($file);
						
						if($exist)
						{
						?> 
                        <button id="porneste" style="background-color:green;color:white; font-size:16px; cursor:pointer; border:none !important" value="porneste" onClick="pornesteScript(this.value);" >Pornește generare clasament</button>  
                       <button id="opreste" style="font-size:16px; border:none !important" value="opreste" onClick="opresteScript(this.value);" disabled>Oprește generare clasament</button>
                      
                       <?php
					   }
					   else
					   {
						?>
						<button id="porneste" style="font-size:16px; border:none !important"  value="porneste" onClick="pornesteScript(this.value);" disabled >Pornește generare clasament</button>  
                       <button id="opreste" style="background-color:red;color:white; font-size:16px; cursor:pointer; border:none !important" value="opreste" onClick="opresteScript(this.value);" >Oprește generare clasament</button>
                      
                       <?php  
					   }
					   ?>
                       
                       <?php
						// afisarea butonului bonus
						$file = '../cron_job/pagini_inactive/genereaza_clasament_general_stop.php';
						$exist = file_exists($file);
						
						if($exist){
						?>
						&nbsp;
                      <button value="bonus" onClick="updateBonus(this.value);" style="cursor:pointer;">Adaugă bonusul participanților</button>
					  <?php
						}
						else
						{
						?>
						<button value="bonus" onClick="updateBonus(this.value);"  disabled>Adaugă bonusul participanților</button>
                        <?php	
						}
						?>
                        <br />
                        <br />
                        
                        
                        
                       <?php
					   // functiile de oprire si pornire a scriptului de generare clasament general
					   if((isset($_GET['act'])) && ($_GET['act'] == 'opreste')){

	
			rename('../cron_job/genereaza_clasament_general.php','../cron_job/pagini_inactive/genereaza_clasament_general_stop.php');
			header('Location: clasament_general.php');
						}
						if((isset($_GET['act'])) && ($_GET['act'] == 'porneste')){

	
			rename('../cron_job/pagini_inactive/genereaza_clasament_general_stop.php','../cron_job/genereaza_clasament_general.php');
			header('Location: clasament_general.php');
						}
					   ?>
                       
                       <?php
					   // functia de update bonus
					   
					   if(isset($_GET['bonus']) && ($_GET['bonus'] == 'bonus')){
						   $anCurent = date('Y');
						   $select = "SELECT `numeClasament` FROM `clasament_general` WHERE `anCompetitional` = {$anCurent}"; 
						   $result = $db->execute($select);
						   $competitie = $db->getObject($result);
						   $tabel = $competitie['numeClasament'];
						   
						   // se updateaza tabelul clasamentului general cu bonusurile corespondente numarului de participari
						   $checkBonus = "SELECT `id` FROM `".$tabel."` WHERE `bonus` > 0";
						   $result = $db->execute($checkBonus);
						   
						   if($db->getCount($result) == 0){
						   
						   $update15 = "UPDATE `".$tabel."` SET `rezultat` = (`rezultat` + 15), puncte_bonus = 15 WHERE `participari` = 2";
						   $db->execute($update15);
						   
						   $update30 = "UPDATE `".$tabel."` SET `rezultat` = (`rezultat` + 30), puncte_bonus = 30 WHERE `participari` = 3";
						   $db->execute($update30);
						   
						   $update60 = "UPDATE `".$tabel."` SET `rezultat` = (`rezultat` + 60), puncte_bonus = 60 WHERE `participari` BETWEEN 4 AND 20";
						   $db->execute($update60);
						   
						   $updateBonus = "UPDATE `".$tabel."` SET `bonus` = 1 WHERE 1 ";
						   $db->execute($updateBonus);
						   
						   $mesaj = base64_encode("Bonusurile au fost alocate cu succes");
						   header("Location: clasament_general.php?mesaj=".$mesaj);
						   }
						   else
						   {
						    $mesaj1 = base64_encode("Bonusurile au fost alocate deja");
						   header("Location: clasament_general.php?mesaj1=".$mesaj1);   
						   }
						}
					   ?>
                       
                        <?php
							// afisez mesajele venite prin get
							if(isset($_GET['mesaj'])){
								
								$mesaj = base64_decode($_GET['mesaj']);
								echo "<div class=\"succes\"><img src=\"pics/tick1.png\" />&nbsp;{$mesaj}</div><br />";							
								
							}
							if(isset($_GET['mesaj1'] )){
									
								$mesaj1 = base64_decode($_GET['mesaj1']);
								echo "<div class=\"eroare\"><img src=\"pics/cross1.png\" />&nbsp;{$mesaj1}</div><br />";	
							}
						?>
                        
                 		 <table id="main_content_data" cellpadding="2px" cellspacing="2px">
                         		                         
                         	<tr class="cap_tabel">
                            	<td>Nr. crt</td>
                                <td>Nume clasament</td>
                                <td>An competițional</td>
                                <td>Tipul clasamentului</td>                                
                                <td>Vizualizare</td>
                                <td>Șterge competiție</td>
                            </tr>
                            
                            
                            <?php
							
							// paginatie
							
							
							$afisari_pagina = 50;
		  					$query = "SELECT `id` FROM `clasament_general` WHERE 1 ";
		   					$result = $db->execute($query);						 				 
		  					$nr_pagini = ceil($db->getCount($result) / $afisari_pagina);
		   
		  					$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
		  					$start = ($pagina - 1) * $afisari_pagina;
							
							if(isset($_GET['anComp'])){
							$query = "SELECT * FROM `clasament_general` WHERE 
							`anCompetitional` = '".$_GET['anComp']."' 
							ORDER BY `anCompetitional` desc LIMIT $start, $afisari_pagina";	
								
							}
							else
							{
							$query = "SELECT * FROM `clasament_general` WHERE 1 ORDER BY `anCompetitional` desc LIMIT $start, $afisari_pagina";
							}
							
							$rezultat = $db->execute($query);
							
							if($db->getCount($rezultat) >= 1){
								
							
							while($row = $db->getObject($rezultat)){							
							
							?> 
                            
                            <tr class="celule">
                            
                            	<td><?php echo $row['id'] ; ?></td>
                                <td><?php echo $row['numeClasament'] ; ?></td>
                                <td><?php echo $row['anCompetitional'] ; ?></td>                        
                                <td><?php echo $row['genul'] ; ?></td> 
                                <td><a href="detalii_clasament_general.php?tabel=<?php echo $row['numeClasament'];?>&gen=<?php echo $row['genul']; ?>"><button <?php echo ($row['genul'] !== 'general')? "" : "";?>>Vizualizare</button></a></td>
                               	
                                <!-- butonul de stergere tabel -->
                               <td>
                               <form method="post" action="update.php" > 
                                	<input type="submit" name="deleteGeneral" onClick="return confirmPost();" value="Șterge tabel" disabled/>
                               		<input type="hidden" name="idTabel" value="<?php echo $row['id'] ;?>" />
                               		<input type="hidden" name="numeTabel" value="<?php echo $row['numeClasament'] ;?>" />
                                </form>
                                
                                </td>
                            
                            </tr>
                          	
                            <?php
							}					
							
							}
							else
							{ ?>
                            	<tr>
                                	<td colspan="9">
                                    <?php echo "<div class=\"eroare\" style=\"margin:auto\"><img src=\"pics/cross1.png\"/>Nu ai niciun clasament general</div>";  ?>
                                    </td>
                                </tr>
							<?php }
                        	?>
                         </table> 
                 		 <br><br>
                         
                         
                         <!--- paginatia ------->
                    <div class="pagination" >
                    
                    	<div class="pagination_content">
          				 <?php
		  					 if(($nr_pagini >= 1) and ($pagina <= $nr_pagini)){
			  					 for($x=1; $x<=$nr_pagini; $x++){ 
								 ?>
               
               			<div class="page_number">	
				    		<?php
							
							if(isset($_GET['anComp'])){
								echo ($x == $pagina) ? '<div id="strong"><a href="?pagina='.$x.'&anComp='.$_GET['anComp'].'" >'.$x.'</a></div>'
                        		 : '<a href="?pagina= '.$x.'&anComp='.$_GET['anComp'].'" >'.$x.'</a> ';
							}
							else
							{
								echo ($x == $pagina) ? '<div id="strong"><a href="?pagina= '.$x.'" >'.$x.'</a></div>'
                        		 : '<a href="?pagina= '.$x.'"  >'.$x.'</a> ';
							}
							?>
				   		</div>
						<?php 
						  }
			   	
			 			  }
				
						  ?>
                          
                        </div>

      				 </div>	
                     
                     <br><br>            
                         
                 </div>
                
                
                
                
                
         </div>

</body>
</html>
<?php
ob_flush();
?>