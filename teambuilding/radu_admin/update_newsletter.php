<?php
ob_start();
session_start();
require("includes/Database.php");
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
<title>Update Newsletter</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
		<?php
			if(isset($_GET['uniqcod'])){
				
				$cod_unic = $_GET['uniqcod'];
				
				$query = "SELECT * FROM `sabloane_newsletter` WHERE `uniq_id` = '". $cod_unic ."'";
				$result = $db->execute($query);
				
				$newsletter = $db->getObject($result);
				
				$cod_newsletter = base64_decode($newsletter['editor']);
				$folder = $newsletter['nume_folder']."/";
				$pagina = $newsletter['nume_sablon'].".php";
				
				
				
				
				$root_folder = "../newsletter/";
				echo $file_news = htmlspecialchars($root_folder.$folder.$pagina);
				
				// verific existenta paginii
				if(file_exists($file_news)){
				
				// fac update-ul in pagina newsletter-ului
				$file = fopen($file_news, 'w') or die("Nu pot scrie în pagina creată");
				$update = fwrite($file,$cod_newsletter);
				fclose($file);	
				
					if($update){
						
						header("Location: editeaza_newsletter.php");
						
						}	
							
				}
				else
				{
				echo "Nu am gasit pagina";	
				}
				
				
				
				
				}
		
		?>

		
</body>
</html>
<?php
ob_flush();
?>