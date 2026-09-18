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
	
	if(isset($_GET['tbl_name'])){
	     $strip = str_replace("_" ," ", $_GET['tbl_name']);
		 
	}
?>






<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Tabel <?php  echo ucfirst($strip) ; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="" href="css/print_tabel.css" />
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
                	<a href="detalii_tabel_competitie_duatlon.php?tabelid=<?php echo $_GET['tbl_name'];?><?php if(isset($_GET['coloana'])){echo "&coloana={$_GET['coloana']}";}?>&flag=<?php echo $_GET['flag']; ?>" target="_self"><button >Înapoi la tabel</button></a>
                </div>
                
                	<?php
						if(isset($_GET['coloana'])) {
							
						$query = "SELECT * FROM `". $_GET['tbl_name']. "` Where 1 ORDER BY {$_GET['coloana']} asc";	
							}
							else
							{
						$query = "SELECT * FROM `". $_GET['tbl_name']. "` Where 1 ORDER BY id asc";	
							}
					
							$select = "SET NAMES 'utf8'";
							$db->execute($select);
							$result = $db->execute($query); ?>
						
						
                                <table id="print_tabel" cellpadding="2px" cellspacing="0">
                                	
                                	<tr>
                                    	<td id="cap_tabel" colspan="19"><?php  echo ucfirst($strip) ;?></td>
                                    </tr>
                                    <tr class="cap_coloane">
                                    		<td class="cells">Id</td>
                                    		<td class="cells">Cod Unic</td>
                                            <td class="cells">Nume</td>
                                            <td class="cells">Prenume</td>
                                            <td class="cells">Vârsta</td>
                                            <td class="cells">Cat. vârstă</td>
                                            <td class="cells">Sex</td>
                                            <td class="cells">Traseu alergare</td>
                                            <td class="cells">Traseu bicicleta</td>
                                            <td class="cells">Family</td>
                                            <td class="cells">Pachet vip</td>
                                            <td class="cells">Tricou</td>
                                            <td class="cells">Print Vip</td>
                               				<td class="cells">Locul</td>
                               				<td class="cells">Puncte</td>
                                            <td class="cells">Număr</td>
                                            <td class="cells">Timpul</td>
                                            <td class="cells">Taxă</td>                                    
                                    </tr>       
                                    
                         <?php  if(mysqli_num_rows($result) > 0){
							
									while($row = mysqli_fetch_array($result)){ ?>
								    
                                    <tr class="cap_coloane">
                                    		<td><?php echo $row['id'] ;?></td>
                                    		<td><?php echo $row['cod_unic_participant'] ;?></td>
                                            <td><?php echo $row['numele'] ;?></td>
                                            <td><?php echo $row['prenumele'] ;?></td>
                                            <td><?php echo $row['varsta'] ;?></td>
                                            <td><?php echo $row['cat_varsta'] ;?></td>
                                            <td><?php echo $row['sex'] ;?></td>
                                            <td><?php echo $row['traseu_alergare'] ;?></td>
                                            <td><?php echo $row['traseu_bicicleta'] ;?></td>
                                            <td><?php echo $row['insotitor'] ;?></td>
                                            <td><?php echo $row['pachet_vip'] ;?></td>
                                            <td><?php echo $row['tricou'] ;?></td>
                                            <td><?php echo $row['print_vip'] ;?></td>
                               				<td><?php echo $row['locul'] ;?></td>
                              				<td><?php echo $row['puncte'] ;?></td>
                                            <td><?php echo $row['numar_concurs'] ;?></td>
                                            <td><?php echo $row['timp'] ;?></td>
                                            <td><?php echo $row['taxa'] ;?></td>                                    
                                    </tr>       
                                
                                
                              
								
								
							<?php	} // sfarsit while
							
							} else
							{
								
								echo "<tr class=\"cap_coloane\"><td colspan=\"19\" style=\"text-align:center; font-size:16px\">Nu sunt date de afisat</td></tr>";
							}// sfarsit isset
							
					?>
                
                  </table>
                
                
                
                </div>
		
</body>
</html>
<?php
ob_flush();
?>