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
<link rel="stylesheet" type="text/css" media="all" href="css/modificare_tabel_competitie.css" />
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
                   <!-- meniul stanga
                    
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
                        	<td><a href="#">Compară date</a></td>
                        </tr>
                        <tr>
						<?php
						/*
							if($user <> 1) 
                        	echo "<td style=\"color:white; background-color:red\">Zona admin</td>";
							else
							echo "<td><a href=\"admin_area.php\">Zona admin</a></td>";
						*/
						?>
                    	</tr>
                        <tr>
                        	<td style="text-align:center; background-color:#A60000"><a href="logout.php">Logout</a></td>
                    	</tr>
                    </table>
                
                </div>
                 sfarsit meniu stanga -->
                
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
                            <li><a href="detalii_tabel_competitie_sinaia.php?tabelid=<?php echo $_GET['tbl_name'];?>&coloana=<?php if(( $_GET['coloana']) <> ""){echo $_GET['coloana'];}else{echo "id";};?>&pagina=<?php if(isset($_GET['pagina']) <> ""){echo $_GET['pagina'];}else{echo 1;}?>&flag=<?php echo $_GET['flag'];?>">Detalii Tabel Competiție</a>&nbsp;&nbsp;>></li>
                            <li class="active">Modifică tabel competiție</li>
                        </ul>                    
                   </div>
                    <br>
                    <?php     
                    // paginatia
						
						$afisari_pagina = 75;
		  				$query = "SELECT `id` FROM `".$_GET['tbl_name']."` WHERE 1";
		   				$result = $db->execute($query);										 				 
		  				$nr_pagini = ceil($db->getCount($result) / $afisari_pagina);
		   
		  				$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
		  				$start = ($pagina - 1) * $afisari_pagina;
					?>
                    
                     <?php
					 // mesaj de eroare
						if(isset($_GET['err'])){echo "<div class=\"eroare\"><img src=\"pics/cross1.png\" />{$_GET['err']}</div><br>";}
					  ?>
                    <table id="tabel_competitie" cellpadding="2px" cellspacing="2px" >
                    	
                    		<tr class="cap_tabel">
                            	<td colspan="17" style="font-size:14px">Modifică detalii tabel "<?php echo $_GET['tbl_name'];?>"</td>
                    		</tr>
                    		<tr class="cap_coloana">
                            	<td>Id</td>
                                <td>Cod Unic</td>
                                <td>Nume</td>
                                <td>Prenume</td>
                                <td>Vârsta</td>
                                <td>Cat. vârstă</td>
                                <td>Sex</td>
                                <td>Traseu</td>
                                <td>Vip</td>
                                <td>Tricou</td>
                                <td>Print Vip</td>
                                <td>Locul</td>
                                <td>Puncte</td>
                                <td>Timp</td>
                                <td>Număr</td>                               
                                <td>Taxa</td>
                                
                                                        
                            </tr>
                    		<form action="update.php" method="post" > 
                            <?php
								if(isset($_GET['tbl_name'])){
									
									$tabel = $_GET['tbl_name'];
									
									if($_GET['coloana'] <> ""){
										
										$query = "SELECT * FROM `".$tabel."` WHERE 1 ORDER BY {$_GET['coloana']} asc LIMIT $start, $afisari_pagina";
										}
										else
										{
											
										$query = "SELECT * FROM `".$tabel."` WHERE 1 ORDER BY id asc LIMIT $start, $afisari_pagina";	
										}
									
									$select = "SET NAMES 'utf8'";
									$db->execute($select);
									$result = $db->execute($query);
									
									if($result){
										
										while($row = mysqli_fetch_array($result)){
											
										 ?>
                                       
                                   <tr class="celule">     
										<td style="width:20px"><input type="text" name="id[]"
                                        value="<?php echo $row['id'];?>" readonly />
                                        </td>
                                		<td style="width:50px"><input type="text" name="cod[]" 
                                        value="<?php echo $row['cod_unic_participant'];?>"  readonly/>
                                        </td>
                               			<td style="width:80px"><input type="text" name="numele[]" 
                                        value="<?php echo $row['numele'];?>"  />
                                        </td>
                              			<td style="width:80px"><input type="text" name="prenumele[]"
                                         value="<?php echo $row['prenumele'];?>" />
                                        </td>
                                		<td style="width:25px"><input type="text" name="varsta[]" 
                                        value="<?php echo $row['varsta'];?>" />
                                        </td>
                                        <td style="width:50px"><input type="text" name="cat_varsta[]" 
                                        value="<?php echo $row['cat_varsta'];?>" />
                                        </td>
                               			<td style="width:50px"><input type="text" name="sex[]" 
                                        value="<?php echo $row['sex'];?>" />
                                        </td>
                              			<td style="width:55px"><input type="text" name="traseu[]"
                                         value="<?php echo $row['traseu'];?>" />
                                        </td>
                                        <td style="width:20px"><input type="text" name="pachet_vip[]" 
                                        value="<?php echo $row['pachet_vip'];?>"  />
                                        </td>
                               			<td style="width:25px"><input type="text" name="tricou[]" 
                                        value="<?php echo $row['tricou'];?>"  />
                                        </td>
                                        <td style="width:100px"><input type="text" name="print_vip[]" 
                                        value="<?php echo $row['print_vip'];?>" />
                                        </td>
                                        <td style="width:20px"><input type="text" name="locul[]" 
                                        value="<?php echo $row['locul'];?>" />
                                        </td>
                                        <td style="width:30px"><input type="text" name="puncte[]" 
                                        value="<?php echo $row['puncte'];?>" />
                                        </td>
                              			<td style="width:50px"><input type="text" name="timp[]"
                                         value="<?php echo $row['timp'];?>" />
                                        </td>
                              			<td style="width:25px"><input type="text" name="numar[]" 
                                        value="<?php echo $row['numar_concurs'];?>" />
                                        </td>                                        
                                        <td style="width:55px"><input type="text" name="taxa[]" 
                                        value="<?php echo $row['taxa'];?>" />
                                        </td>
                               			
									</tr>
                                        
											
										<?php 	}// sfarsit while										
										
										} // sfarsit $result						
									
									}// sfarsit isset							
							?>
                            		
                            		<tr style="text-align:center">
                                			<td colspan="17">
                                        <input type="submit" name="submit" value="Modifică date competiție" />
                                    	<input type="hidden" name="modifica_competitie_sinaia" value="ok" />
                                        <input type="hidden" name="modifica_competitie_tabel" value="<?php echo $_GET['tbl_name'];?>" />                                      <input type="hidden" name="coloana" value="<?php if(isset($_GET['coloana'])){echo $_GET['coloana'];}else{echo "id";}?>" /> 
                                        <input type="hidden" name="start" value="<?php echo $start;?>" /> 
                                        <input type="hidden" name="afisari_pagina" value="<?php echo $afisari_pagina;?>" />
                                        <input type="hidden" name="pagina" value="<?php if(isset($_GET['pagina'])){echo $_GET['pagina'];}else{ echo $_GET['pagina']=1;};?>"  /> 
                                         <input type="hidden" name="flag" value="<?php echo $_GET['flag'];?>" /> 
                                   			 </td>
                               		 </tr>
                    			</form>	
                                
                                	<tr>
                                    	<td colspan="16">
                                    	
                                    	<!--- paginatia ------->
                                        
                  							  <div class="pagination" >
                    
                    								<div class="pagination_content">
                                                    
          											 <?php
													 
		  											 if(($nr_pagini >= 1) and ($pagina <= $nr_pagini)){
			  												 for($x=1; $x<=$nr_pagini; $x++){
													 ?>
               
               													<div class="page_number">	
				    											<?php
				echo ($x == $pagina) ? '<div id="strong"><a href="?pagina='.$x.'&tbl_name='.$_GET['tbl_name'].'&coloana='.$_GET['coloana'].'&flag='.$_GET['flag'].'" >'.$x.'</a></div>'
                        		 : '<a href="?pagina='.$x.'&tbl_name='.$_GET['tbl_name'].'&coloana='.$_GET['coloana'].'&flag='.$_GET['flag'].'"  >'.$x.'</a> '
						
																?>
				   												</div>
																<?php
																 
																}
			   	
			 			 								 }
													
						 										?>
                          
                        							</div>

      										 </div>	
                     					</td>
                                    </tr>			
                            		
                    </table>
                    <br>
                  </div>
                  
           </div>  
		
</body>
</html>
<?php
ob_flush();
?>