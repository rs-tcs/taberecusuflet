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

?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Modificare tabel competitie</title>
<link rel="stylesheet" type="text/css" media="all" href="css/modificare_date_concurent.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
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
                    		<td><a href="tabele_competitii.php">Tabele competiții</a></td>
                        </tr>
                        <tr>
                        	<td><a href="clasament_general.php">Clasament general</a></td>
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
                    <div id="breadcrumbs">
                    	<ul>
                        	<li><a href="tabele_competitii.php">Tabele competiții </a>&nbsp;&nbsp;>></li>
                            <li><a href="detalii_tabel_competitie_duatlon.php?tabelid=<?php echo $_GET['table_name'];?>&flag=<?php echo $_GET['flag']; ?>">Detalii Tabel Competiții</a>&nbsp;&nbsp;>></li>
                            <li class="active">Modifică date concurent</li>
                        </ul>                    
                   </div>
                    <br>
                    <?php
					
					if(isset($_GET['err'])){$mesage = base64_decode($_GET['err']); echo "<div class=\"eroare\"><img src=\"pics/cross1.png\" />{$mesage}</div><br>";}
					?>
                    <table id="tabel_competitie" cellpadding="2px" cellspacing="2px" >
                    	
                    		<tr class="cap_tabel">
                            <td colspan="2" style="font-size:14px">Modifică detalii concurent competiție "<?php echo $_GET['table_name'];?>"</td>
                    		</tr>
                    		
                    		<form action="update.php" method="post" > 
                            <?php
								if((isset($_GET['comp_id'])) && (isset($_GET['table_name']))){
									
									$tabel = $_GET['table_name'];
									$concurent = $_GET['comp_id'];
									
									$query = "SELECT * FROM  `".$tabel."`  WHERE `id` = '".$concurent."'";
									
									$select = "SET NAMES 'utf8'";
									$db->execute($select);
									$result = $db->execute($query);
									if($result){
										
										while($row = $db->getObject($result)){
											
										 ?>
                                         
                                     <tr >
                            			<td class="cap_tabel">Id</td>
                                        <td class="celule"><input type="text" name="id" 
                                        value="<?php echo $row['id'];?>" disabled/>
                                        </td>
                                     </tr>
                                     <tr>
                                       <td class="cap_tabel">Cod Unic</td>
                                       <td class="celule"><input type="text" name="cod_unic" 
                                        value="<?php echo $row['cod_unic_participant'];?>" readonly />
                                       </td>
                                     </tr>
                               		 <tr>
                                     	<td class="cap_tabel">Nume</td>
                                     	<td class="celule"><input type="text" name="numele" 
                                        value="<?php echo $row['numele'] ;?>" />
                                        </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Prenume</td>
                                     	<td class="celule"><input type="text" name="prenumele"
                                         value="<?php echo $row['prenumele'];?>" />
                                        </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Vârsta</td>
                                     	<td class="celule"><input type="text" name="varsta" 
                                        value="<?php echo $row['varsta'];?>" />
                                        </td>
                                     </tr>
                                     <tr>
                                    	 <td class="cap_tabel">Cat. vârstă</td>
                                     	 <td class="celule">
                                         <select name="cat_varsta">
                                         	<option value="">Selectați categoria</option>
                                         <?php						
																				
                                         	$query = "SELECT `categorii` FROM `cat_varsta` WHERE 1";
											$result = $db->execute($query);										
											while($cat = mysqli_fetch_array($result)){												
												
											?>
												
											<option value="<?php echo $cat['categorii'] ;?>" 
											<?php if($row['cat_varsta'] == $cat['categorii']) { ?> selected="selected" <?php }?>>
											<?php echo $cat['categorii'] ;?>
                                            </option>											
											<?php 											
											
											} //sfarsit while
                                         
                                         ?>
                                         
                                         </select>           
                                                         
                                       
                                        </td>
                                     </tr>
                                     <tr>
                                    	 <td class="cap_tabel">Sex</td>
                                     	<td class="celule">
                                        <select name="sex" >
                                        	<option value="">Alege sexul</option>
                                        	<?php
											$query = "SELECT `sexul` FROM `sex` WHERE 1";
											$result = $db->execute($query);
											while($cat = mysqli_fetch_array($result)){ ?>
												
											<option value="<?php echo $cat['sexul']; ?>" 
											<?php if($row['sex'] == $cat['sexul']){?> selected="selected"<?php } ?>>
                                            <?php echo $cat['sexul']; ?>
                                            </option>	
												
											<?php	}
                                        
                                        	?>
                                        </select>
                                        </td>
                                     </tr>
                                     
                                     <tr>
                                     	 <td class="cap_tabel">Traseu alergare</td>
                                     	 <td class="celule">
                                         <select name="traseu_alergare">
                                     <option value="">Selectați o opțiune</option>
                                         
                                         		<?php
												
												$query = "SELECT `traseu` FROM `traseu_maraton` WHERE 1 ";
												$result = $db->execute($query);
												
												while($traseu = mysqli_fetch_array($result)){ 
												
													
												?>													
													
									<option value="<?php echo $traseu['traseu'] ;?>" 
									<?php if($row['traseu_alergare'] == $traseu['traseu']){ ?> selected="selected" <?php } ?>>
									<?php echo $traseu['traseu'] ;?>
                                    </option>			
													
												<?php 
												} // sfarsit while
												
												?>                        
                                         
                                         
                                         
                                         </select>                                        
                                         
                                         </td>
                                     </tr>
                                     
                                     <tr>
                                     	 <td class="cap_tabel">Traseu bicicletă</td>
                                     	 <td class="celule">
                                         <select name="traseu_bicicleta">
                                         
                                    <option value="">Selectați o opțiune</option>                                         
                                         		<?php
												
												$query = "SELECT `traseul` FROM `traseu` WHERE 1 ";
												$result = $db->execute($query);
												
												while($traseu = mysqli_fetch_array($result)){ 
												
													
												?>													
													
									<option value="<?php echo $traseu['traseul'] ;?>" 
									<?php if($row['traseu_bicicleta'] == $traseu['traseul']){ ?> selected="selected" <?php } ?>>
									<?php echo $traseu['traseul'] ;?>
                                    </option>			
													
												<?php 
												} // sfarsit while
												
												?>                        
                                         
                                         
                                         
                                         </select>                                        
                                         
                                         </td>
                                     </tr>
                                     
                                     <tr>
                                    	 <td class="cap_tabel">Family</td>
                                     	 <td class="celule" style="text-align:left">
                                         <?php 
                                         	if($row['insotitor'] == "da"){	?>
												
                                                <input type="checkbox" name="insotitor" checked value="da" />
                                          <?php
												}
												else
												{
										  ?>
												<input type="checkbox" name="insotitor" value="" />	
                                          <?php
												}
                                         
                                          ?>
                                         
                                         </td>
                                     </tr>
                                     
                                     <tr>
                                    	 <td class="cap_tabel">Pachet Vip</td>
                                     	 <td class="celule" style="text-align:left">
                                         <?php 
                                         	if($row['pachet_vip'] == "vip"){ ?>
												
												<input type="checkbox" name="vip" checked value="vip" />
											<?php
												}
												else
												{
											?>	
												
												<input type="checkbox" name="vip" value="" />	
												
                                         	<?php
												}
                                         	?>                              
                                         </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Tricou</td>
                                     	<td class="celule">
                                        <select name="tricou">
                                        <option value="">Fără tricou</option>
                                        <?php
                                        $query = "SELECT `marime` FROM `marime_tricou` WHERE 1";
                                        $result = $db->execute($query);
                                        while($marime = mysqli_fetch_array($result)){ ?>
											
										<option value="<?php echo $marime['marime']; ?>" 
										<?php if($row['tricou']==$marime['marime']){?> selected="selected" <?php } ?>>
                                        <?php echo $marime['marime'];?>
                                        </option>	
											
										<?php	
										}// sfarsit while
                                        ?>
                                        </select>
                                        </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Print Personalizat</td>
                                        <td class="celule"><input type="text" name="print_vip" value="<?php echo $row['print_vip']?>"/></td>
                                     </tr>
                                     
                                     <tr>
                                    	 <td class="cap_tabel">Locul</td>
                                     	 <td class="celule"><input type="text" name="locul"
                                         value="<?php echo $row['locul'];?>" />
                                         </td>
                                     </tr>
                                     <tr>
                                    	 <td class="cap_tabel">Puncte</td>
                                     	 <td class="celule"><input type="text" name="puncte"
                                         value="<?php echo $row['puncte'];?>" />
                                         </td>
                                     </tr>
                                     <tr>
                                    	 <td class="cap_tabel">Timp bicicleta</td>
                                     	 <td class="celule"><input type="text" name="timp_bicicleta"
                                         value="<?php echo $row['timp_bicicleta'];?>" />
                                         </td>
                                     </tr>
                                     <tr>
                                    	 <td class="cap_tabel">Timp alegare</td>
                                     	 <td class="celule"><input type="text" name="timp_alergare"
                                         value="<?php echo $row['timp_alergare'];?>" />
                                         </td>
                                     </tr>
                                      <tr>
                                    	 <td class="cap_tabel">Timp total</td>
                                     	 <td class="celule"><input type="text" name="timp"
                                         value="<?php echo $row['timp'];?>" />
                                         </td>
                                     </tr>
                                      <tr>
                                    	 <td class="cap_tabel">Dificultate</td>
                                     	 <td class="celule"><input type="text" name="dificultate"
                                         value="<?php echo $row['dificultate'];?>" />
                                         </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Număr concurs</td>
                                     	<td class="celule"><input type="text" name="numar" 
                                        value="<?php echo $row['numar_concurs'];?>" />
                                        </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Email</td>
                                     	<td class="celule"><input type="text" name="email" 
                                        value="<?php echo $row['email'];?>" />
                                        </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Modalitate plata</td>
                                     	<td class="celule">
                                        	<select name="modalitate">
                                            	<option value="">Alege modalitatea de plată</option>
                                                <?php
													$query = "SELECT * FROM `modalitate_plata` WHERE `activ` = 1";
													$result = $db->execute($query);
														while($rand = mysqli_fetch_array($result)){ ?>
                                                        
									<option value="<?php echo $rand['val_modalitate'] ;?>" <?php if($row['modalitate_plata'] == $rand['val_modalitate']){?>selected="selected"<?php }?>><?php echo $rand['tip_plata'];?></option>			
												<?php	}
												?>
                                       		</select>
                                        </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Suma achitată</td>
                                     	<td class="celule"><input type="text" name="suma" 
                                        value="<?php echo $row['suma'];?>" />
                                        </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Nr. chitanță / Nr. OP</td>
                                     	<td class="celule"><input type="text" name="chitanta" 
                                        value="<?php echo $row['nr_chitanta'];?>" />
                                        </td>
                                     </tr>
                                     <tr>
                                     	<td class="cap_tabel">Confirmat</td>
                                     	<td class="celule"><input type="text" name="confirma" 
                                        value="<?php echo $row['confirmare'];?>" />
                                        </td>
                                     </tr>
                                     <tr>
                                     	 <td class="cap_tabel">Taxa</td>                                     
                                    	 <td class="celule">
                                         <select name="taxa">
                                         <?php
                                         $query = "SELECT `stare_plata` FROM `taxa` WHERE 1";
                                         $result = $db->execute($query);
                                         while($taxa = mysqli_fetch_array($result)){ ?>
											 
										<option value="<?php echo $taxa['stare_plata']; ?>" 
										<?php if($row['taxa'] == $taxa['stare_plata']){?> selected="selected" <?php } ?>>
                                        <?php echo $taxa['stare_plata']; ?>
                                        </option>	 
											 
										<?php }// sfarsit while 
                                         ?>
                                         </select>
                                         
                                        </td>		
                                     </tr>     
                                     <tr>
                                     	 <td class="cap_tabel">Data</td>                                     
                                    	 <td class="celule"><input type="text" name="data" 
                                        value="<?php echo $row['data_competitiei'];?>" disabled/>
                                        </td>		
                                     </tr>                      
                                       
                                   
											<?php 	}// sfarsit while										
										
										} // sfarsit $result
										
									}// sfarsit isset							
							?>
                            		<tr class="celule">
                                			<td colspan="12">
                                        <input type="submit" name="submit" value="Modifică date concurent" />
                                    	<input type="hidden" name="modifica_date_concurent_duatlon" value="ok" />
                                        <input type="hidden" name="nume_tabel" value="<?php echo $tabel; ?>" />
                                        <input type="hidden" name="id_concurent" value="<?php  echo $concurent; ?>" />
                                     <input type="hidden" name="flag" value="<?php if(isset($_GET['flag'])){echo $_GET['flag'];}?>" />                                                                              
                                   			 </td>
                               		 </tr>
                                     
                    			</form>				
                            	
                    </table>
                   
                  </div>
                  
           </div>  
		
</body>
</html>
<?php
ob_flush();
?>