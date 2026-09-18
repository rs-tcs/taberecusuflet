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
<title>Baza de Date</title>
<link rel="stylesheet" type="text/css" media="all" href="css/baza_date.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script type="text/javascript" src="js/floating-1.12.js" ></script>
<script type="text/javascript" src="js/functii.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
		<div id="page">
        		<div id="text_top">
                	<p>Zona de administrare <b style="color:darkblue">Team</b><b style="color: #F00000">Xpert</b> Race Club</p>             	</div>
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
                <?php     
                    // paginatia
						
						$afisari_pagina = 75;
		  				$query = "SELECT `id` FROM `inscrisi_teamexpert` WHERE 1 ";
		   				$result = $db->execute($query);						 				 
		  				$nr_pagini = ceil($db->getCount($result) / $afisari_pagina);
		   
		  				$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
		  				$start = ($pagina - 1) * $afisari_pagina;
					?>
                
                
                
                <div id="meniu_stanga">
                
                	<table id="lista_meniu">
                    	<tr>
                    		<td style="text-align:center; background-color:#0F87FF"><a href="baza_date.php">Home</a></td>
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
                
               
                 <?php
				 	
				 
				 
				 	// validarea informatiilor venite prin post din tabelul cautare avansata
					if((isset($_POST['trimis'])) && ($_POST['trimis'] == "ok")){
					 
						if((!empty($_POST['nume'])) && (!ctype_space($_POST['nume']))){
							
								$nume = str_replace(" ","",$_POST['nume']);
							}
							else
							{
								$nume = '';
							}
						if((!empty($_POST['prenume'])) && (!ctype_space($_POST['prenume']))){
							
								$prenume = str_replace(" ","",$_POST['prenume']);
							}
							else
							{
								$prenume = '';
							}
						if((!empty($_POST['email'])) && (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
							
								$email = str_replace(" ","",$_POST['email']);
							}
							else
							{
								$email = '';
							}
							
						if((!empty($_POST['cod'])) && (!ctype_space($_POST['cod']))){
							
								$cod = str_replace(" ","",$_POST['cod']);
							}
							else
							{
								$cod = '';
							}
						if((!empty($_POST['telefon'])) && (is_numeric($_POST['telefon']))){
							
								$telefon = str_replace(" ","",$_POST['telefon']);
							}
							else
							{
								$telefon = '';
							}
						if((!empty($_POST['data'])) && (!ctype_space($_POST['data']))){
							
								$data = str_replace(" ","",$_POST['data']);
							}
							else
							{
								$data = '';
							}
							
							    
                   		 
					
							
							$query = "SELECT * FROM `inscrisi_teamexpert` WHERE nume = '".$nume."' OR prenume = '".$prenume."'";
							$query .= " OR email = '".$email."' OR cod_unic = '".$cod."' OR telefon = '".$telefon."'";
							$query .= " OR data_inscriere = '".$data."' ";
							$rezultat = $db->execute($query);
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							
							
							if(mysqli_num_rows($rezultat) == 0){
								
									echo "<div class=\"main_content\">";
									echo "<div class=\"rezultat_nul\">Căutarea nu a găsit niciun rezultat!";
									echo "<div ><button onClick=\"history.go(-1);\" >Mergi înapoi</button></div>";
									echo "</div>";
									echo "</div>";
									
								}
								else
									//
								{ 
							?>
                          
                                           
                     <div class="main_content">	
                     
                 
                                         
                     <div id="admin">
                    <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
					?>
                    
					</div>
                    <br>
                    <div id="text">
                    <?php echo "Tabel înscriși TeamXpert Sport Events"; ?>
                    </div>
                     <br>
                     
                   <div id="cautare_avansata">               
                
                	<input type="button" id="buton" value="Căutare avansată" onClick="vizibilitate();" />
                    
                    <div id="cautare_formular">
                    	<table id="cautare_tabel" cellpadding="2px" cellspacing="2px" style="border-collapse:separate; border-spacing: 5px;">
                        <form method="post" action="<?php $_SERVER['PHP_SELF'];?>">
                        	<tr>
                            	<td class="celule">    
                                   <input type="text" name="nume" value="Nume" id="nume" onFocus="focusNume();" onBlur="blurNume();"/>
                                </td>
                               
                                <td class="celule">
                                    <input type="text" name="prenume" value="Prenume" id="prenume" onFocus="focusPrenume();" onBlur="blurPrenume();"/>
                                </td>
                            </tr>
                            <tr>
                            	<td class="celule">    
                                    <input type="text" name="email" value="Email" id="email" onFocus="focusEmail();" onBlur="blurEmail();"/>
                                </td>
                                
                                <td class="celule">
                                    <input type="text" name="cod"  value="Cod unic" id="cod" onFocus="focusCod();" onBlur="blurCod();"/>
                                </td>
                            </tr>
                            <tr>
                            	<td class="celule">    
                                    <input type="text" name="telefon" value="Telefon" id="telefon" onFocus="focusTel();" onBlur="blurTel();" />
                                </td>
                                
                                <td class="celule">
                                    <input type="text" name="data" value="Data înregistrării (AAAA-LL-ZZ)" id="data" onFocus="focusData();" onBlur="blurData();"/>
                                </td>
                             <tr>
                             	
                                <td class="celule" colspan="2" style="text-align:center">
                                	<input type="submit" name="submit" value="Caută"/>
                                    <input type="hidden" name="trimis" value="ok"/>
                                </td>
                                
                                    
                            </tr>
                            
                        </form>
                        </table>
                        
                    </div>
                
                </div>
                
                <br><br> 
                
                		<table id="main_content_data" cellpadding="2px" cellspacing="2px">
                    		<tr class="cap_tabel">
                               	<td>Cod</td>
                                <td>Nume</td>
                                <td>Prenume</td>
                                <td>Sex</td>                                
                                <td>D.O.B</td>
                                <td>Vârsta</td> 
                                <td>Cat. Vârstă</td>                                
                                <td>Telefon</td>
                                <td>Email</td>                                
                                <td>Detalii</td>       
                                
                            </tr>
                            
                            <?php
							while($row = mysqli_fetch_array($rezultat)){ ?>
								
							<tr>
                            	<td><?php echo $row['cod_unic'];?></td>
                                <td><?php echo $row['nume'];?></td>
                                <td><?php echo $row['prenume'];?></td>
                                <td><?php echo $row['sex'];?></td>                                
                                <td><?php echo $row['data_nasterii'];?></td>
                                <td><?php echo $row['varsta'];?></td> 
                                <td><?php echo $row['categorie_varsta'];?></td> 
                                <td><?php echo $row['telefon'];?></td>
                                <td><?php echo $row['email'];?></td>                                                               
                                <td><a href="detalii.php?cod=<?php echo $row['cod_unic'];?>"><button>Detalii</button></a></td>       
                                
                            </tr>
                           
							<?php
                            }
							
							?>
                             <tr>
                            	<td colspan="10" ><button style="font-size:14px;" onClick="history.go(-1);">Resetează căutarea</button></td>
                            </tr>
                        </table>  
                        <br><br>
                        							
              		 </div> 
               
                           
							
				<?php
				}		
				}
				?>
				
                
              	<?php
				if(!isset($_POST['trimis'])){ ?>
				  
                <div class="main_content"> 
                 
                <div id="admin">
                    <?php
					
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
					?>
                    
				</div>	               
                <br> 
                <div id="text">
                    <?php echo "Tabel înscriși TeamXpert Sports Events"; ?>
                </div>
                <br>
                
                      <div id="cautare_avansata">
                
                
                	<input type="button" id="buton" value="Căutare avansată" onClick="vizibilitate();" />
                    
                    <div id="cautare_formular">
                    	<table id="cautare_tabel" cellpadding="2px" cellspacing="2px" style="border-collapse:separate; border-spacing: 5px;">
                        <form method="post" action="<?php $_SERVER['PHP_SELF'];?>">
                        	<tr>
                            	<td class="celule">    
                                   <input type="text" name="nume" value="Nume" id="nume" onFocus="focusNume();" onBlur="blurNume();"/>
                                </td>
                               
                                <td class="celule">
                                    <input type="text" name="prenume" value="Prenume" id="prenume" onFocus="focusPrenume();" onBlur="blurPrenume();"/>
                                </td>
                            </tr>
                            <tr>
                            	<td class="celule">    
                                    <input type="text" name="email" value="Email" id="email" onFocus="focusEmail();" onBlur="blurEmail();"/>
                                </td>
                                
                                <td class="celule">
                                    <input type="text" name="cod"  value="Cod unic" id="cod" onFocus="focusCod();" onBlur="blurCod();"/>
                                </td>
                            </tr>
                            <tr>
                            	<td class="celule">    
                                    <input type="text" name="telefon" value="Telefon" id="telefon" onFocus="focusTel();" onBlur="blurTel();" />
                                </td>
                                
                                <td class="celule">
                                    <input type="text" name="data" value="Data înregistrării (AAAA-LL-ZZ)" id="data" onFocus="focusData();" onBlur="blurData();"/>
                                </td>
                             <tr>
                             	
                                <td class="celule" colspan="2" style="text-align:center">
                                	<input type="submit" name="submit" value="Caută"/>
                                    <input type="hidden" name="trimis" value="ok"/>
                                </td>
                                
                                    
                            </tr>
                            
                        </form>
                        </table>
                    </div>
                
                </div>
                <br><br>
                
                <div id="mesaj">
                <?php
					if(isset($_GET['mesaj'])){
						$mesaj = base64_decode($_GET['mesaj']);
						
						echo "<div class=\"succes\"><img src=\"pics/tick1.png\" />{$mesaj}</div>";
						
						}
				?>
                </div>
                <div id="date_inscrisi">
                
                	<?php 
					// nr. inscrisilor din baza de date
					$query = "SELECT `id` FROM `inscrisi_teamexpert` WHERE 1";
					$result = $db->execute($query);
					$nr_inscrisi = $db->getCount($result);
                	echo "<button>Înscriși baza de date&nbsp;&nbsp;<b style=\"bold; color:red;\">".$nr_inscrisi."</b></button>";
					
					// nr celor activi pentru newsletter
					$query = "SELECT `id` FROM `inscrisi_teamexpert` WHERE `activ` = 1";
					$result = $db->execute($query);
					$nr_activ = $db->getCount($result);
					echo "<button>Activi newsletter&nbsp;&nbsp;<b style=\"bold; color:red;\">".$nr_activ."</b></button>";
					
					// nr celor unsubscribed
					$query = "SELECT `id` FROM `inscrisi_teamexpert` WHERE `activ` = 0";
					$result = $db->execute($query);
					$nr_inactiv = $db->getCount($result);
					echo "<button>Unsubscribed&nbsp;&nbsp;<b style=\"bold; color:darkorange;\">".$nr_inactiv."</b></button>";
					
					// buton salvare baza de date CSV
					echo "<a href=\"export_dateContact_baza.php?numeTabel=inscrisi_teamexpert\" target=\"_blank\"><button>Export Baza Date CSV</button></a>";
					
					// buton arata adrese mail active
					echo "<button onClick=\" animIn(); \">Arată email-uri active</button>";					
					
					// buton update varsta si categoria de varsta
					 echo "<a href=\"update.php?updateCatVarst=varsta\"><button style=\"float:right\"><img src=\"pics/hand.png\" />Updatează vârsta și categoria de vârstă </button></a>";
                	?>
                    
                    
                </div>
                
                 <br>
                    <!--- paginatia ------->
                    <div class="pagination" >
                    
                    	<div class="pagination_content">
          				 <?php
		  					 if(($nr_pagini >= 1) and ($pagina <= $nr_pagini)){
			  					 for($x=1; $x<=$nr_pagini; $x++){ ?>
               
               			<div class="page_number">	
				    		<?php
								echo ($x == $pagina) ? '<div id="strong"><a href="?pagina=  '. $x .'" >'. $x .'</a></div>'
                        		 : '<a href="?pagina= ' . $x .'"  >' .$x.'</a> '
						
							?>
				   		</div>
						<?php 
						  }
			   	
			 			  }
				
						  ?>
                          
                        </div>
					<br />
      				 </div>	             
                	<!-- afisarea tabelului cu baza de date la prima accesare-->  	     	           
                	<table id="main_content_data" cellpadding="2px" cellspacing="2px">
                    		<tr class="cap_tabel">
                            	<td>Cod</td>
                                <td>Nume</td>
                                <td>Prenume</td>
                                <td>Sex</td>
                                <td>D.O.B</td>
                                <td>Vărsta</td>
                                <td>Cat. Vărstă</td> 
                                <td>Telefon</td>
                                <td>Email</td>
                                <td>Detalii</td>       
                                
                            </tr>
                            <?php
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$query = "SELECT * FROM `inscrisi_teamexpert` WHERE 1 ORDER BY `id` desc LIMIT $start, $afisari_pagina";
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$rezultat = $db->execute($query);
							while($row = $db->getObject($rezultat)){
							
							?> 
                            
                            <tr>
                            	<td><?php echo $row['cod_unic'];?></td>
                                <td><?php echo ucfirst($row['nume']);?></td>
                                <td><?php echo ucfirst($row['prenume']);?></td>
                                <td><?php echo $row['sex'];?></td>
                                <td><?php echo $row['data_nasterii'];?></td>
                                <td><?php echo $row['varsta'];?></td> 
                                <td><?php echo $row['categorie_varsta'];?></td> 
                                <td><?php echo $row['telefon'];?></td>
                                <td><?php echo $row['email'];?></td>                                                               
                                <td><a href="detalii.php?cod=<?php echo $row['cod_unic'];?>"><button>Detalii</button></a></td>       
                                
                            </tr>
                    <?php
							}
					?>
                    </table>
                    <br>
                    <!--- paginatia ------->
                    <div class="pagination" >
                    
                    	<div class="pagination_content">
          				 <?php
		  					 if(($nr_pagini >= 1) and ($pagina <= $nr_pagini)){
			  					 for($x=1; $x<=$nr_pagini; $x++){ ?>
               
               			<div class="page_number">	
				    		<?php
								echo ($x == $pagina) ? '<div id="strong"><a href="?pagina=  '. $x .'" >'. $x .'</a></div>'
                        		 : '<a href="?pagina= ' . $x .'"  >' .$x.'</a> '
						
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
               
              <?php
			  
			  }
			  ?>  
               
				
                   
		
                
                
                
                <div id="footer">
                
                </div>
        
        		<div id="messageDiv">
                
					<?php include('mailuri_active.php') ?>
            	</div>           
        </div>
		
		<div id="gray"></div>

</body>
</html>
<?php
ob_flush();
?>