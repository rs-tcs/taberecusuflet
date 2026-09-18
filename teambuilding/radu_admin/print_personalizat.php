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
	
	if(isset($_GET['tabel'])){
	     $strip = str_replace("_" ," ", $_GET['tabel']);
		 
	}
?>






<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Tabel <?php  echo ucfirst($strip) ; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="css/print_tabel.css" />
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

				<div id="page">
                <script type="text/javascript">
				
					function printDiv(div) {
      						
   							window.print(); 
							
   							
   					}
					
					
					
				</script>
                <div id="butoane">
                	<br>
                	<button onClick="printDiv('print_tabel');">Printează</button>
                	<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" target="_self"><button >Înapoi la tabel</button></a>
                </div>
                
                	<?php
						if(isset($_GET['traseu'])) {
							
						$query = "SELECT * FROM `". $_GET['tabel']. "` Where `traseu` = '".$_GET['traseu']."' AND `taxa` = 'achitata' ORDER BY `traseu` asc";	
						}
						
						if(isset($_GET['categoria'])){
						$query = "SELECT * FROM `". $_GET['tabel']. "` Where `categoria` = '".$_GET['categoria']."' AND `taxa` = 'achitata' ORDER BY `categoria` asc";		
						}
							
					
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$result = $db->execute($query); ?>
						
							<cfoutput>
                                <table id="print_tabel" cellpadding="2px" cellspacing="0">
                                	
                                    
                                   <thead> 
                                	<tr>
                                    	<td id="cap_tabel" colspan="18"><?php  echo ucfirst($strip) ;?></td>
                                    </tr>
                                    <tr class="cap_coloane">
                                    		<td class="cells">Id</td>
                                    		<td class="cells">Cod Unic</td>
                                            <td class="cells">Nume</td>
                                            <td class="cells">Prenume</td>
                                            <td class="cells">Vârsta</td>
                                            <td class="cells">Cat. vârstă</td>
                                            <td class="cells">Sex</td>
                                            <?php
											if(isset($_GET['categoria'])){
											?>
                                            <td class="cells">Categoria</td>
                                            <?php
											}
											else
											{
											?>
                                            <td class="cells">Traseu</td>                                            
                                            <?php
											}
											if(($_GET['flag'] == "Comana Bike Fest") && ($_GET['flag'] == "Cupa Tabere cu Suflet"))
											{
											?>
											<td class="cells">Family</td>
                                            <?php
											}
											else
											{
											// nu se afiseaza aceasta optiune pentru On top of the World, Cupa Malinului	
											}
											?>                                       
                                            
                                            <td class="cells">Pachet vip</td>
                                            <td class="cells">Tricou</td>
                                            <td class="cells">Print Vip</td>
                                            <?php
											if($_GET['flag'] == "Cupa Tabere cu Suflet"){ ?>
												
											<td class="cells">Duatlon</td>	
											<?php
												}
											?>                               				
                                            <td class="cells">Număr</td>                                            
                                            <td class="cells">Taxă</td>                                    
                                    </tr>       
                                  </thead>
                                  
                                    
                         <?php  if(mysqli_num_rows($result) > 0){
							
									while($row = mysqli_fetch_array($result)){ ?>
								    
                                    
                                   <tbody>
                                    <tr class="cap_coloane">
                                    		<td><?php echo $row['id'] ;?></td>
                                    		<td><?php echo $row['cod_unic_participant'] ;?></td>
                                            <td><?php echo $row['numele'] ;?></td>
                                            <td><?php echo $row['prenumele'] ;?></td> 
                                            <td><?php echo $row['varsta'] ;?></td>                                           
                                            <td><?php echo $row['cat_varsta'] ;?></td>
                                            <td><?php echo $row['sex'] ;?></td>
                                            <?php
											if(isset($_GET['categoria'])){
											?>
                                            <td><?php echo $row['categoria'] ;?></td>
                                            <?php	
											}
											else
											{
											?>
                                            <td><?php echo $row['traseu'] ;?></td>
                                            <?php
											}
											if(($_GET['flag'] == "Comana Bike Fest") && ($_GET['flag'] == "Cupa Tabere cu Suflet"))
											{
											?>
											<td><?php echo $row['insotitor'] ;?></td>
                                            <?php	
											}
											?>                                            
                                            <td><?php echo $row['pachet_vip'] ;?></td>
                                            <td><?php echo $row['tricou'] ;?></td>
                                            <td><?php echo $row['print_vip'] ;?></td>
                                            <?php
											if($_GET['flag'] == "Cupa Tabere cu Suflet"){ ?>
												
											<td><?php echo $row['duatlon'] ;?></td>	
											<?php
												}
											?>                               				
                                            <td><?php echo $row['numar_concurs'] ;?></td>
                                            <td><?php echo $row['taxa'] ;?></td>                                    
                                    </tr>       
                                
                                  </tbody>
                              
								
								
							<?php	} // sfarsit while
							
							} 
							else
							{
								
								echo "<tr class=\"cap_coloane\"><td colspan=\"17\" style=\"text-align:center; font-size:16px\">Nu sunt date de afișat</td></tr>";
							}// sfarsit isset
							
					?>
                
                  </table>
                
                </cfoutput>
                
                </div>
		
</body>
</html>
<?php
ob_flush();
?>