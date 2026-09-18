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
<title>Vizualizare Newsletter</title>
<link rel="stylesheet" type="text/css" media="all" href="css/vizualizare_newsletter.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js" ></script>
<script type="text/javascript" src="js/floating-1.12.js" ></script>
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
                    
                   <!-- editare meniu --> 
                    <div id="meniu_stanga">
                
                	<table id="lista_meniu">
                    	<tr>
                    		<td style="text-align:center;"><a href="baza_date.php">Home</a></td>
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
                        	<li><a href="editeaza_newsletter.php">Tabel newslettere </a>&nbsp;&nbsp;>></li>
                            <li class="active">Vizualizare newsletter</li>
                        </ul>                    
                 </div>
                 <br>
                	<div id="main_content_data">
                
                <?php
						if((isset($_GET['cod'])) && (!$_GET['cod'] == "")){
							
							$newsletter = $_GET['cod'];
							
												
								
								
								include('sablon_newsletter1.php');
								
								
								
							
							
							}
							else
							{
							echo "<font style=\"color:red\">Informații insuficiente pentru afișarea newsletter-ului</font>";	
							}
				
				?>
                
                	 </div>
                </div>
                
           </div>     
</body>
</html>
<?php
ob_flush();
?>