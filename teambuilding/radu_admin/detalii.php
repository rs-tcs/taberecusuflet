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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Detalii</title>
<link rel="stylesheet" type="text/css" media="all" href="css/detalii.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script type="text/javascript" src="js/floating-1.12.js" ></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>


<script type="text/javascript">
	function confirmPost()
		{
			var agree = confirm("Ești sigur că vrei să ștergi acest user?");
				if (agree)
				return true ;
		else
				return false ;
		}


</script>
	<?php
	$cod = $_GET['cod'];
	
	// stergerea unei intrari din baza de date
	
	if(isset($_POST['delete'])){
		
		$query = "DELETE FROM `inscrisi_teamexpert` WHERE `cod_unic` = '".$cod."'";
		$rezultat = $db->execute($query);
		if($rezultat){
			header('Location: baza_date.php');
			}
			else
			{
			echo "<div id=\"rezultat_nul\">Intrarea nu a fost stearsa!</div>";	
			}
		}
		
	
		
	
	?>


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
                    		<td style="text-align:center"><a href="baza_date.php"><div class="text">Home</div></a></td>
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
				if((isset($_GET['cod'])) && (!isset($_POST['trimis']))){ 
					$select = "SET NAMES 'utf8'";
					$db->execute($select);
					$query = "SELECT * FROM `inscrisi_teamexpert` WHERE cod_unic = '".$cod."' LIMIT 0, 1";
					$rezultat = $db->execute($query);
					while($row = mysqli_fetch_array($rezultat)){
				
				
				?> 
                 <div class="tabel_date">
                 <div id="admin">
                 <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
				 ?>
                 </div>
                 <br>
                 <div id="breadcrumbs">
                    	<ul>
                        	<li><a href="baza_date.php">Bază date înscriși</a>&nbsp;&nbsp;>></li>
                            <li class="active">Detalii membru înscris</li>
                        </ul>                    
                 </div>
                 <br>	
                 	<table id="tabel_date_inscris">
                    	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                        	<tr>
                            	<td id="header" colspan="2">Vizualizare detalii <?php echo $row['nume']." ".$row['prenume'];?></td>
                            </tr>
                    		<tr>
                        		<td class="celule"><label for="cod_unic">Codul Unic generat</label></td>
                           		<td class="input"><input type="text" name="cod" value = "<?php echo $cod; ?> " disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="nume">Numele</label></td>
                           		<td class="input"><input type="text" name="nume" value ="<?php echo $row['nume']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="prenume">Prenumele</label></td>
                           		<td class="input"><input type="text" name="prenume" value ="<?php echo $row['prenume']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="sex">Sexul</label></td>
                           		<td class="input"><input type="text" name="sex" value = "<?php echo $row['sex']; ?> " disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="data_nasterii">Data nașterii</label></td>
                           		<td class="input"><input type="date" name="data_nasterii" value ="<?php echo $row['data_nasterii']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="varsta">Vârsta</label></td>
                           		<td class="input"><input type="number" name="varsta" value ="<?php echo $row['varsta']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="cat_varsta">Categorie Vârstă</label></td>
                           		<td class="input"><input type="text" name="categorie_varsta" value ="<?php echo $row['categorie_varsta']; ?>" disabled /></td>
                       		 </tr>                                       
                             <tr>
                        		<td class="celule"><label for="telefon">Telefon</label></td>
                           		<td class="input"><input type="number" name="telefon" value ="<?php echo $row['telefon']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="email">Email</label></td>
                           		<td class="input"><input type="text" name="email" value ="<?php echo $row['email']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="activ">Activ newsletter</label></td>
                           		<td class="input"><input type="number" name="activ" value ="<?php echo $row['activ']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="activ">Activ campanie newsletter</label></td>
                           		<td class="input"><input type="number" name="news_activ" value ="<?php echo $row['news_activ']; ?>" disabled /></td>
                       		 </tr>
                             
                             <tr>
                        		<td class="celule"><label for="oras">Oraș reședință</label></td>
                           		<td class="input"><input type="text" name="oras" value ="<?php echo $row['oras']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="club">Club</label></td>
                           		<td class="input"><input type="text" name="club" value ="<?php echo $row['club']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="descriere">Descriere</label></td>
                           		<td class="input"><textarea  name="descriere" disabled ><?php echo $row['descriere']; ?></textarea></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="data_inscriere">Data înscrierii</label></td>
                           		<td class="input"><input type="date" name="data_inscriere" value ="<?php echo $row['data_inscriere']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="ip">IP-ul de la care s-a făcut înregistrarea</label></td>
                           		<td class="input"><input type="text" name="ip" value ="<?php echo $row['ip']; ?>" disabled /></td>
                       		 </tr>
                             <tr>
                             	
                             	<td class="input" colspan="2"><input type="submit" name="submit" value="Editează" class="modifica"/>
                                				  <input type="hidden" name="trimis" value="ok" />
                                                  <input type="hidden" name="cod" value="<?php echo $_GET['cod']; ?>"/>
                            					  <input type="submit" name="delete" value="Delete" id="delete" onClick="return confirmPost()"/></td>
                             </tr>
                    	</form>
                    </table>
                 	<br>
                 </div>
                <?php
					}					
					
				}
				
				
				if((isset($_POST['trimis'])) && ($_POST['trimis'] == "ok")){
					
					$cod = $_POST['cod'];
					$select = "SET NAMES 'utf8'";
					$db->execute($select);
					$query = "SELECT * FROM `inscrisi_teamexpert` WHERE cod_unic = '".$cod."' LIMIT 0, 1";
					$rezultat = $db->execute($query);
					
					while($row = mysqli_fetch_array($rezultat)){
					
					
					
					?>			
                    
                    <div class="tabel_date">
                    <div id="admin">
                    <?php
					echo "<font style=\"font-size:18px\">Welcome"." ".$text." "."<font style=\"color:red; font-size:18px\">".ucfirst($_SESSION['username'])."</font>";		
					 ?>
                     </div>
                     <br>
                     <div id="breadcrumbs">
                    	<ul>
                        	<li><a href="baza_date.php">Bază date înscriși</a>&nbsp;&nbsp;>></li>
                            <li class="active">Detalii membru înscris</li>
                        </ul>                    
                 	 </div>
                     <br>
                 	<table id="tabel_date_inscris_modifica">
                    	<form action="update.php" method="post">
                        	<tr>
                            	<td id="header" colspan="2">Modificare detalii <?php echo $row['nume']." ".$row['prenume'];?></td>
                            </tr>
                    		<tr>
                        		<td class="celule"><label for="cod_unic">Codul Unic generat</label></td>
                           		<td class="input"><input type="text" name="cod_unic" value ="<?php echo $cod; ?>" readonly/></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="nume">Numele</label></td>
                           		<td class="input"><input type="text" name="nume" value ="<?php echo $row['nume']; ?>"/></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="prenume">Prenumele</label></td>
                           		<td class="input"><input type="text" name="prenume" value ="<?php echo $row['prenume']; ?>"/></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="sex">Sexul</label></td>
                           		<td class="input">
                                	<select name="sex">
                                    	<option value="">Alege sexul</option>
                                        <?php
											$query = "SELECT `sexul` FROM `sex` WHERE 1";
											$result = $db->execute($query);
											while($rows = mysqli_fetch_array($result)){ ?>
												
										<option value="<?php echo $rows['sexul']; ?>"<?php if($rows['sexul'] == $row['sex']){?>selected="selected" <?php } else {} ?>>
										<?php echo $rows['sexul']; ?></option>
											<?php	}
                                    		?>
                                        
                                    </select>
                                </td>
                       		 </tr>
                             
                             <tr>
                        		<td class="celule"><label for="data_nasterii">Data nașterii</label></td>
                           		<td class="input"><input type="date" name="data_nasterii" value ="<?php echo $row['data_nasterii']; ?>"/></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="varsta">Vârsta</label></td>
                           		<td class="input"><input type="number" name="varsta" value ="<?php echo $row['varsta']; ?>"/></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="categorie_varsta">Categorie Vârstă</label></td>
                           		<td class="input">
                                	<select name="categorie_varsta">
                                    	<option value="">Alege categoria de vârstă</option>
                                        	<?php
											$query = "SELECT `categorii` FROM `cat_varsta` WHERE 1";
											$result = $db->execute($query);
											while($rows = mysqli_fetch_array($result)){ ?>
												
										<option value="<?php echo $rows['categorii']; ?>"<?php if($rows['categorii'] == $row['categorie_varsta']){?>selected="selected" <?php } else {} ?>>
										<?php echo $rows['categorii']; ?></option>
											<?php	}
                                    		?>
                                    </select>
                                </td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="telefon">Telefon</label></td>
                           		<td class="input"><input type="text" name="telefon" value ="<?php echo $row['telefon']; ?>"/></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="email">Email</label></td>
                           		<td class="input"><input type="text" name="email" value ="<?php echo $row['email']; ?>"/></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="activ">Activ newsletter</label></td>
                           		<td class="input"><input type="number" name="activ" value ="<?php echo $row['activ']; ?>"/></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="activ">Activ campanie newsletter</label></td>
                           		<td class="input"><input type="number" name="news_activ" value ="<?php echo $row['news_activ']; ?>"/></td>
                       		 </tr>
                            
                             <tr>
                        		<td class="celule"><label for="oras">Oraș reședință</label></td>
                           		<td class="input"><input type="text" name="oras" value ="<?php echo $row['oras']; ?>"/></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="club">Club</label></td>
                           		<td class="input"><input type="text" name="club" value ="<?php echo $row['club']; ?>"/></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="descriere">Descriere</label></td>
                           		<td class="input"><textarea  name="descriere"><?php echo $row['descriere']; ?></textarea></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="data_inscriere">Data înscrierii</label></td>
                           		<td class="input"><input type="date" name="data_inscriere" value ="<?php echo $row['data_inscriere']; ?>"/></td>
                       		 </tr>
                             <tr>
                        		<td class="celule"><label for="ip">IP-ul de la care s-a făcut înregistrarea</label></td>
                           		<td class="input"><input type="text" name="ip" value ="<?php echo $row['ip']; ?>" /></td>
                       		 </tr>
                             <tr>
                             	
                             	<td class="input" colspan="2"><input type="submit" name="modifica_date" value="Actualizează" class="modifica"/>
                                				  <input type="hidden" name="trimis_detalii_user" value="<?php echo $cod; ?>" />
                                                  <input type="submit" name="anuleaza" value="Anulează" class="modifica"/>
                                                  <input type="hidden" name="anuleaza_update" value="true" /></td>
                            					  
                             </tr>
                    	</form>
                    </table>
                    <br>
                 </div>
				<?php
				}
				}
				?>
                 
        </div>

</body>
</html>
<?php
ob_flush();
?>